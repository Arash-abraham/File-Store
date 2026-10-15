import { useEffect, useMemo, useState, type FormEvent } from 'react';
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { ArrowLeft, BadgePercent, Check, Factory, Info, Loader2, MapPin, Printer, Sparkles } from 'lucide-react';
import { api, apiErrorMessage, demoMode } from '../lib/api';
import { demoPrintHouses, demoPrintOptions } from '../data/demo';
import { formatPrice } from '../lib/format';
import type { PrintHouse, PrintOptions, PrintQuote, Product } from '../types';
import { useStore } from '../StoreContext';

function unwrapHouses(value: unknown): PrintHouse[] {
  if (Array.isArray(value)) return value as PrintHouse[];
  if (value && typeof value === 'object' && 'data' in value) {
    const nested = (value as { data: unknown }).data;
    if (Array.isArray(nested)) return nested as PrintHouse[];
    if (nested && typeof nested === 'object' && 'print_houses' in nested) {
      const houses = (nested as { print_houses: unknown }).print_houses;
      if (Array.isArray(houses)) return houses as PrintHouse[];
    }
  }
  return [];
}

export const printStatusLabels: Record<string, string> = {
  pending: 'در انتظار تأیید چاپخانه',
  confirmed: 'تأییدشده',
  printing: 'در حال چاپ',
  shipped: 'ارسال‌شده',
  delivered: 'تحویل‌شده',
  cancelled: 'لغوشده',
};

export default function PrintOrderForm({ product, compact = false }: { product: Product; compact?: boolean }) {
  const { user, setLoginOpen, setNotice, ownsProduct, refreshLibrary } = useStore();
  const queryClient = useQueryClient();
  const [houseId, setHouseId] = useState<number | null>(null);
  const [quantity, setQuantity] = useState(1);
  const [paperType, setPaperType] = useState('standard');
  const [binding, setBinding] = useState('paperback');
  const [colorPrint, setColorPrint] = useState(false);
  const [form, setForm] = useState({ shipping_name: '', shipping_phone: '', shipping_city: '', shipping_address: '', postal_code: '', notes: '' });
  const [error, setError] = useState('');

  const ownsDigital = ownsProduct(product.id);

  const { data: houses = [], isLoading } = useQuery<PrintHouse[]>({
    queryKey: ['print-houses'],
    staleTime: 5 * 60 * 1000,
    queryFn: async () => demoMode ? demoPrintHouses : unwrapHouses((await api.get('/print-houses')).data.data),
  });

  const options: PrintOptions = demoMode
    ? demoPrintOptions
    : { paper_types: [], bindings: [], color_surcharge: 45000, digital_owner_discount: 0.15 };

  const selectedHouse = useMemo(() => houses.find((house) => house.id === houseId) ?? houses[0], [houses, houseId]);

  useEffect(() => {
    if (!houseId && houses.length) {
      setHouseId(houses[0].id);
      setQuantity(Math.max(1, houses[0].min_quantity));
    }
  }, [houses, houseId]);

  useEffect(() => {
    if (selectedHouse) setQuantity((current) => Math.max(selectedHouse.min_quantity, Math.min(selectedHouse.max_quantity, current)));
  }, [selectedHouse]);

  useEffect(() => {
    if (user) {
      setForm((current) => ({
        ...current,
        shipping_name: current.shipping_name || user.name,
        shipping_phone: current.shipping_phone || (user.phone ?? ''),
      }));
    }
  }, [user]);

  /**
   * The server owns the pricing rules; the form only displays the returned quote.
   * In demo mode the same rules are applied locally.
   */
  const quote = useQuery<PrintQuote>({
    queryKey: ['print-quote', selectedHouse?.id, product.id, quantity, paperType, binding, colorPrint],
    enabled: !!selectedHouse,
    queryFn: async () => {
      if (demoMode && selectedHouse) return demoQuote(selectedHouse, { quantity, paperType, binding, colorPrint }, ownsDigital, demoPrintOptions);
      const response = await api.post('/print-orders/quote', {
        print_house_id: selectedHouse!.id,
        product_id: product.id,
        quantity,
        paper_type: paperType,
        binding,
        color_print: colorPrint,
      });
      return response.data.data as PrintQuote;
    },
  });

  const submitOrder = useMutation({
    mutationFn: async () => {
      if (demoMode) {
        return { id: Math.floor(3100 + Math.random() * 500), status: 'pending' };
      }
      const response = await api.post('/print-orders', {
        print_house_id: selectedHouse!.id,
        product_id: product.id,
        quantity,
        paper_type: paperType,
        binding,
        color_print: colorPrint,
        ...form,
      });
      return response.data.data;
    },
    onSuccess: async (order) => {
      setError('');
      setNotice(`سفارش نسخه‌ی چاپی #${order.id} ثبت شد؛ وضعیت آن را در حساب کاربری ببین.`);
      await queryClient.invalidateQueries({ queryKey: ['print-orders'] });
      await refreshLibrary();
      setForm((current) => ({ ...current, notes: '' }));
    },
    onError: (reason) => setError(apiErrorMessage(reason, 'ثبت سفارش نسخه‌ی چاپی انجام نشد.')),
  });

  function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!user) {
      setLoginOpen(true);
      setNotice('برای ثبت سفارش چاپی ابتدا وارد حساب شو.');
      return;
    }
    if (!selectedHouse) return;
    submitOrder.mutate();
  }

  if (isLoading) {
    return <div className={`print-order-card ${compact ? 'is-compact' : ''}`}><p className="print-loading"><Loader2 size={16} className="search-spinner" /> در حال دریافت چاپخانه‌ها…</p></div>;
  }

  if (!selectedHouse) {
    return <div className={`print-order-card ${compact ? 'is-compact' : ''}`}><p className="print-loading">چاپخانه‌ی فعالی برای سفارش ثبت نشده است.</p></div>;
  }

  const currentQuote = quote.data;

  return (
    <form className={`print-order-card ${compact ? 'is-compact' : ''}`} onSubmit={submit}>
      <div className="print-order-head">
        <span className="print-order-icon"><Printer size={19} /></span>
        <div>
          <b>سفارش نسخه‌ی چاپی</b>
          <small>همین کتاب را چاپ‌شده و صحافی‌شده درِ خانه تحویل بگیر.</small>
        </div>
      </div>

      {ownsDigital && (
        <div className="print-owner-note">
          <BadgePercent size={16} />
          <span>چون نسخه‌ی دیجیتال این کتاب را داری، {(demoPrintOptions.digital_owner_discount * 100).toLocaleString('fa-IR')}٪ تخفیف چاپ روی این سفارش اعمال می‌شود.</span>
        </div>
      )}

      <label className="field-label print-select">
        چاپخانه
        <select value={selectedHouse.id} onChange={(event) => setHouseId(Number(event.target.value))}>
          {houses.map((house) => (
            <option key={house.id} value={house.id}>
              {house.name} — {house.city} — تحویل {house.delivery_days.toLocaleString('fa-IR')} روزه — {formatPrice(house.unit_price)}
            </option>
          ))}
        </select>
      </label>

      <div className="print-options-row">
        <label className="field-label">کاغذ
          <select value={paperType} onChange={(event) => setPaperType(event.target.value)}>
            {(demoMode ? demoPrintOptions.paper_types : [
              { value: 'standard', label: 'کاغذ تحریر معمولی' },
              { value: 'cream', label: 'کاغذ کرافت/کرم' },
              { value: 'premium', label: 'کاغذ گلاسه‌ی درجه‌یک' },
            ]).map((option) => <option key={option.value} value={option.value}>{option.label}</option>)}
          </select>
        </label>
        <label className="field-label">صحافی
          <select value={binding} onChange={(event) => setBinding(event.target.value)}>
            {(demoMode ? demoPrintOptions.bindings : [
              { value: 'paperback', label: 'شومیز' },
              { value: 'hardcover', label: 'سخت (گالینگور)' },
              { value: 'spiral', label: 'فنر' },
            ]).map((option) => <option key={option.value} value={option.value}>{option.label}</option>)}
          </select>
        </label>
        <label className="field-label">تعداد نسخه
          <input
            type="number"
            min={selectedHouse.min_quantity}
            max={selectedHouse.max_quantity}
            value={quantity}
            onChange={(event) => setQuantity(Number(event.target.value) || selectedHouse.min_quantity)}
          />
        </label>
      </div>

      <button type="button" className={`toggle-row print-color-toggle ${colorPrint ? 'is-on' : ''}`} onClick={() => setColorPrint(!colorPrint)} aria-pressed={colorPrint}>
        <span>
          <b><Sparkles size={14} /> چاپ رنگی</b>
          <small>{options.color_surcharge.toLocaleString('fa-IR')} تومان اضافه برای هر نسخه</small>
        </span>
        <span className={`switch ${colorPrint ? 'is-on' : ''}`}><i /></span>
      </button>

      <div className="print-shipping">
        <span className="print-shipping-title"><MapPin size={15} /> نشانی تحویل</span>
        <div className="print-shipping-grid">
          <label className="field-label">نام گیرنده<input required value={form.shipping_name} onChange={(event) => setForm({ ...form, shipping_name: event.target.value })} maxLength={150} /></label>
          <label className="field-label">شماره تماس<input required value={form.shipping_phone} onChange={(event) => setForm({ ...form, shipping_phone: event.target.value })} maxLength={30} placeholder="۰۹۱۲…" /></label>
          <label className="field-label">شهر<input required value={form.shipping_city} onChange={(event) => setForm({ ...form, shipping_city: event.target.value })} maxLength={80} /></label>
          <label className="field-label">کد پستی<input value={form.postal_code} onChange={(event) => setForm({ ...form, postal_code: event.target.value })} maxLength={20} placeholder="اختیاری" /></label>
        </div>
        <label className="field-label">نشانی کامل<textarea required rows={2} value={form.shipping_address} onChange={(event) => setForm({ ...form, shipping_address: event.target.value })} maxLength={500} /></label>
        <label className="field-label">توضیح برای چاپخانه (اختیاری)<textarea rows={2} value={form.notes} onChange={(event) => setForm({ ...form, notes: event.target.value })} maxLength={1000} placeholder="مثلاً: جلد ساده، بدون لوگو…" /></label>
      </div>

      <div className="print-quote-box">
        <div className="print-quote-head"><Factory size={15} /> برآورد هزینه</div>
        {quote.isFetching && !currentQuote ? (
          <p className="print-loading"><Loader2 size={15} className="search-spinner" /> محاسبه…</p>
        ) : currentQuote ? (
          <>
            <ul className="print-breakdown">
              {currentQuote.breakdown.map((line) => (
                <li key={line.label}>
                  <span>{line.label}</span>
                  <b className={line.amount < 0 ? 'is-discount' : ''}>{line.amount < 0 ? '− ' : ''}{formatPrice(Math.abs(line.amount))}</b>
                </li>
              ))}
            </ul>
            <div className="print-total">
              <span>مبلغ نهایی <small>(ارسال {currentQuote.delivery_days.toLocaleString('fa-IR')} روزه)</small></span>
              <b>{formatPrice(currentQuote.total_price)}</b>
            </div>
          </>
        ) : (
          <p className="print-loading">برآورد هزینه در دسترس نیست.</p>
        )}
      </div>

      <p className="print-terms"><Info size={14} /> پرداخت چاپ پس از تأیید چاپخانه و به‌صورت جدا از خرید نسخه‌ی دیجیتال انجام می‌شود.</p>
      {error && <div className="form-error">{error}</div>}

      <button className="button button-primary button-full" disabled={submitOrder.isPending}>
        {submitOrder.isPending ? <><Loader2 size={16} className="search-spinner" /> در حال ثبت…</> : <><Printer size={16} /> ثبت سفارش نسخه‌ی چاپی <ArrowLeft size={15} /></>}
      </button>
      <p className="print-confirm-note"><Check size={14} /> بدون پرداخت آنلاین؛ پس از ثبت، کارشناس چاپخانه برای هماهنگی تماس می‌گیرد.</p>
    </form>
  );
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
