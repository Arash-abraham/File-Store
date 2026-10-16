import { api, demoMode } from './api';
import { demoPrintHouses, demoPrintOptions, demoPrintOrders } from '../data/demo';
import type { PrintDirectory, PrintHouse, PrintOptions, PrintOrder, PrintQuote } from '../types';

/**
 * Every call of the printing feature goes through this file, so the shape of
 * the API payload is unwrapped in exactly one place.
 */

/** The life cycle of a print order, as it is shown to the reader. */
export const printStatusLabels: Record<string, string> = {
  pending: 'در انتظار تأیید چاپخانه',
  confirmed: 'تأییدشده',
  printing: 'در حال چاپ',
  shipped: 'ارسال‌شده',
  delivered: 'تحویل‌شده',
  cancelled: 'لغوشده',
};

export const printStatusSteps = ['pending', 'confirmed', 'printing', 'shipped', 'delivered'];

export const emptyPrintOptions: PrintOptions = {
  paper_types: [],
  bindings: [],
  color_surcharge: 0,
  digital_owner_discount: 0,
};

export const emptyPrintDirectory: PrintDirectory = {
  houses: [],
  cities: [],
  capabilities: [],
  options: emptyPrintOptions,
  stats: { print_houses: 0, cities: 0, fastest_delivery_days: 0, from_price: 0 },
};

function asRecord(value: unknown): Record<string, unknown> {
  return value && typeof value === 'object' ? (value as Record<string, unknown>) : {};
}

function asArray<T>(value: unknown): T[] {
  return Array.isArray(value) ? (value as T[]) : [];
}

function asNumber(value: unknown, fallback = 0): number {
  return typeof value === 'number' && Number.isFinite(value) ? value : fallback;
}

function asStringArray(value: unknown): string[] {
  return asArray<unknown>(value)
    .filter((item): item is string => typeof item === 'string')
    .filter((item) => item.trim() !== '');
}

function unwrapLabelMap(value: unknown): { value: string; label: string }[] {
  return asArray<Record<string, unknown>>(value)
    .map((item) => ({ value: String(item.value ?? ''), label: String(item.label ?? item.value ?? '') }))
    .filter((item) => item.value !== '');
}

/**
 * The directory endpoint answers with `{ data: { print_houses, cities, ... } }`.
 * Older payloads and demo data answer with the bare object, so both are read.
 */
export function unwrapDirectory(value: unknown): PrintDirectory {
  const root = asRecord(value);
  const payload = asRecord(root.data ?? root);
  const houses = asArray<PrintHouse>(payload.print_houses);
  const options = asRecord(payload.options);

  return {
    houses,
    cities: asStringArray(payload.cities),
    capabilities: asStringArray(payload.capabilities),
    options: {
      paper_types: unwrapLabelMap(options.paper_types).length ? unwrapLabelMap(options.paper_types) : emptyPrintOptions.paper_types,
      bindings: unwrapLabelMap(options.bindings).length ? unwrapLabelMap(options.bindings) : emptyPrintOptions.bindings,
      color_surcharge: asNumber(options.color_surcharge, emptyPrintOptions.color_surcharge),
      digital_owner_discount: asNumber(options.digital_owner_discount, emptyPrintOptions.digital_owner_discount),
    },
    stats: {
      print_houses: asNumber(asRecord(payload.stats).print_houses, houses.length),
      cities: asNumber(asRecord(payload.stats).cities, asStringArray(payload.cities).length),
      fastest_delivery_days: asNumber(asRecord(payload.stats).fastest_delivery_days),
      from_price: asNumber(asRecord(payload.stats).from_price),
    },
  };
}

/** The partner directory with the print options and the summary numbers. */
export async function fetchPrintDirectory(): Promise<PrintDirectory> {
  if (demoMode) {
    return {
      houses: demoPrintHouses,
      cities: [...new Set(demoPrintHouses.map((house) => house.city))],
      capabilities: [...new Set(demoPrintHouses.flatMap((house) => house.capabilities))],
      options: demoPrintOptions,
      stats: {
        print_houses: demoPrintHouses.length,
        cities: new Set(demoPrintHouses.map((house) => house.city)).size,
        fastest_delivery_days: Math.min(...demoPrintHouses.map((house) => house.delivery_days)),
        from_price: Math.min(...demoPrintHouses.map((house) => house.unit_price)),
      },
    };
  }

  const response = await api.get('/print-houses');
  return unwrapDirectory(response.data);
}

export type PrintQuoteRequest = {
  print_house_id: number;
  product_id?: number | null;
  quantity: number;
  paper_type: string;
  binding: string;
  color_print: boolean;
};

/** The server prices the order, the form only shows what it returns. */
export async function fetchPrintQuote(payload: PrintQuoteRequest, ownsDigital: boolean): Promise<PrintQuote> {
  if (demoMode) {
    const house = demoPrintHouses.find((item) => item.id === payload.print_house_id) ?? demoPrintHouses[0];
    return demoQuote(
      house,
      {
        quantity: payload.quantity,
        paperType: payload.paper_type,
        binding: payload.binding,
        colorPrint: payload.color_print,
      },
      ownsDigital,
      demoPrintOptions,
    );
  }

  const response = await api.post('/print-orders/quote', {
    print_house_id: payload.print_house_id,
    product_id: payload.product_id ?? undefined,
    quantity: payload.quantity,
    paper_type: payload.paper_type,
    binding: payload.binding,
    color_print: payload.color_print,
  });

  return response.data.data as PrintQuote;
}

export type PrintOrderRequest = PrintQuoteRequest & {
  product_id: number;
  shipping_name: string;
  shipping_phone: string;
  shipping_city: string;
  shipping_address: string;
  postal_code?: string | null;
  notes?: string | null;
};

export async function createPrintOrder(payload: PrintOrderRequest): Promise<PrintOrder> {
  if (demoMode) {
    return {
      ...demoPrintOrders[0],
      id: Math.floor(3100 + Math.random() * 500),
      status: 'pending',
    } as PrintOrder;
  }

  const response = await api.post('/print-orders', {
    print_house_id: payload.print_house_id,
    product_id: payload.product_id,
    quantity: payload.quantity,
    paper_type: payload.paper_type,
    binding: payload.binding,
    color_print: payload.color_print,
    shipping_name: payload.shipping_name,
    shipping_phone: payload.shipping_phone,
    shipping_city: payload.shipping_city,
    shipping_address: payload.shipping_address,
    postal_code: payload.postal_code || null,
    notes: payload.notes || null,
  });

  return response.data.data as PrintOrder;
}

export async function fetchPrintOrders(): Promise<PrintOrder[]> {
  if (demoMode) {
    return demoPrintOrders as unknown as PrintOrder[];
  }

  const response = await api.get('/print-orders');
  return asArray<PrintOrder>(asRecord(response.data).data);
}

export async function cancelPrintOrder(id: number): Promise<PrintOrder> {
  if (demoMode) {
    return { ...demoPrintOrders[0], id, status: 'cancelled', can_cancel: false } as PrintOrder;
  }

  const response = await api.post(`/print-orders/${id}/cancel`);
  return response.data.data as PrintOrder;
}

export type PrintContractRequest = {
  company_name: string;
  contact_name: string;
  phone: string;
  email?: string | null;
  city: string;
  monthly_capacity?: number | null;
  capabilities: string[];
  message?: string | null;
};

export async function submitPrintContract(payload: PrintContractRequest): Promise<{ id: number; status: string }> {
  if (demoMode) {
    return { id: 1, status: 'pending' };
  }

  const response = await api.post('/print-houses/contract-requests', {
    ...payload,
    email: payload.email || null,
    monthly_capacity: payload.monthly_capacity ?? null,
    message: payload.message || null,
  });

  return response.data.data as { id: number; status: string };
}

/** Demo-mode mirror of `PrintPricingService`. */
export function demoQuote(
  house: PrintHouse,
  options: { quantity: number; paperType: string; binding: string; colorPrint: boolean },
  ownsDigital: boolean,
  printOptions: PrintOptions,
): PrintQuote {
  const paperSurcharge = options.paperType === 'premium' ? 60000 : options.paperType === 'cream' ? 35000 : 0;
  const bindingSurcharge = options.binding === 'hardcover' ? house.hardcover_surcharge : options.binding === 'spiral' ? 25000 : 0;
  const colorSurcharge = options.colorPrint ? printOptions.color_surcharge : 0;
  const quantity = Math.max(house.min_quantity, Math.min(house.max_quantity, options.quantity));
  const unitPrice = house.unit_price + paperSurcharge + bindingSurcharge + colorSurcharge;
  const subtotal = unitPrice * quantity;
  const discount = ownsDigital ? Math.round(subtotal * printOptions.digital_owner_discount) : 0;

  const breakdown: { label: string; amount: number }[] = [{ label: 'چاپ هر نسخه', amount: house.unit_price }];
  if (paperSurcharge) breakdown.push({ label: printOptions.paper_types.find((item) => item.value === options.paperType)?.label ?? 'کاغذ', amount: paperSurcharge });
  if (bindingSurcharge) breakdown.push({ label: `صحافی ${printOptions.bindings.find((item) => item.value === options.binding)?.label ?? ''}`, amount: bindingSurcharge });
  if (colorSurcharge) breakdown.push({ label: 'چاپ رنگی', amount: colorSurcharge });
  breakdown.push({ label: 'تعداد نسخه', amount: subtotal });
  if (house.delivery_fee) breakdown.push({ label: 'هزینه‌ی ارسال', amount: house.delivery_fee });
  if (discount) breakdown.push({ label: 'تخفیف دارندگان نسخه‌ی دیجیتال (۱۵٪)', amount: -discount });

  return {
    unit_price: unitPrice,
    subtotal,
    delivery_fee: house.delivery_fee,
    discount_amount: discount,
    total_price: Math.max(0, subtotal - discount) + house.delivery_fee,
    delivery_days: house.delivery_days,
    min_quantity: house.min_quantity,
    max_quantity: house.max_quantity,
    breakdown,
    digital_owner: ownsDigital,
  };
}
