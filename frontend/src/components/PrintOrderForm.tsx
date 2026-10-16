import { useEffect, useMemo, useState, type FormEvent } from 'react';
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { ArrowLeft, BadgePercent, Check, CircleCheck, Factory, Info, Loader2, MapPin, Minus, Phone, Plus, Printer, Sparkles, Truck } from 'lucide-react';
import { Link } from 'react-router-dom';
import { apiErrorMessage } from '../lib/api';
import { createPrintOrder, fetchPrintDirectory, fetchPrintQuote } from '../lib/print';
import { formatPrice } from '../lib/format';
import type { PrintOrder, PrintQuote, Product } from '../types';
import { useStore } from '../StoreContext';

type Props = {
  product: Product;
  compact?: boolean;
  /** Preselects a partner, for example when the reader picked one in the directory. */
  initialHouseId?: number | null;
  onPlaced?: (order: PrintOrder) => void;
};

export default function PrintOrderForm({ product, compact = false, initialHouseId = null, onPlaced }: Props) {
  const { user, setLoginOpen, setNotice, ownsProduct, refreshLibrary } = useStore();
  const queryClient = useQueryClient();
  const [houseId, setHouseId] = useState<number | null>(initialHouseId);
  const [quantity, setQuantity] = useState(1);
  const [paperType, setPaperType] = useState('standard');
  const [binding, setBinding] = useState('paperback');
  const [colorPrint, setColorPrint] = useState(false);
  const [form, setForm] = useState({ shipping_name: '', shipping_phone: '', shipping_city: '', shipping_address: '', postal_code: '', notes: '' });
  const [error, setError] = useState('');
  const [placed, setPlaced] = useState<PrintOrder | null>(null);

  const ownsDigital = ownsProduct(product.id);

  const directory = useQuery({
    queryKey: ['print-directory'],
    staleTime: 5 * 60 * 1000,
    queryFn: fetchPrintDirectory,
  });

  const houses = useMemo(() => directory.data?.houses ?? [], [directory.data]);
  const options = directory.data?.options;

  const selectedHouse = useMemo(
    () => houses.find((house) => house.id === houseId) ?? houses.find((house) => house.id === initialHouseId) ?? houses[0],
    [houses, houseId, initialHouseId],
  );

  useEffect(() => {
    if (!houseId && selectedHouse) {
      setHouseId(selectedHouse.id);
    }
  }, [selectedHouse, houseId]);

  // The directory can hand over a different partner while the form is open.
  useEffect(() => {
    if (initialHouseId && initialHouseId !== houseId) {
      setHouseId(initialHouseId);
    }
  }, [initialHouseId]);

  useEffect(() => {
    if (selectedHouse) {
      setQuantity((current) => Math.max(selectedHouse.min_quantity, Math.min(selectedHouse.max_quantity, current)));
    }
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
   */
  const quote = useQuery<PrintQuote>({
    queryKey: ['print-quote', selectedHouse?.id, product.id, quantity, paperType, binding, colorPrint],
    enabled: !!selectedHouse,
    staleTime: 30 * 1000,
    queryFn: () => fetchPrintQuote(
      {
        print_house_id: selectedHouse!.id,
        product_id: product.id,
        quantity,
        paper_type: paperType,
        binding,
        color_print: colorPrint,
      },
      ownsDigital,
    ),
  });

  const submitOrder = useMutation({
    mutationFn: () => createPrintOrder({
      print_house_id: selectedHouse!.id,
      product_id: product.id,
      quantity,
      paper_type: paperType,
      binding,
      color_print: colorPrint,
      shipping_name: form.shipping_name,
      shipping_phone: form.shipping_phone,
      shipping_city: form.shipping_city,
      shipping_address: form.shipping_address,
      postal_code: form.postal_code,
      notes: form.notes,
    }),
    onSuccess: async (order) => {
      setError('');
      setPlaced(order);
      setNotice(`سفارش نسخه‌ی چاپی #${order.id} ثبت شد؛ وضعیت آن را در حساب کاربری ببین.`);
      await queryClient.invalidateQueries({ queryKey: ['print-orders'] });
      await refreshLibrary();
      onPlaced?.(order);
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

  function changeQuantity(next: number) {
    if (!selectedHouse) return;
    setQuantity(Math.max(selectedHouse.min_quantity, Math.min(selectedHouse.max_quantity, next)));
  }

  if (directory.isLoading) {
    return <div className={`print-order-card ${compact ? 'is-compact' : ''}`}><p className="print-loading"><Loader2 size={16} className="search-spinner" /> در حال دریافت چاپخانه‌ها…</p></div>;
  }

  if (directory.isError) {
    return (
      <div className={`print-order-card ${compact ? 'is-compact' : ''}`}>
        <p className="print-loading">دریافت فهرست چاپخانه‌ها انجام نشد.</p>
        <button type="button" className="button button-outline button-small" onClick={() => directory.refetch()}>
          <Loader2 size={15} className="search-spinner" /> تلاش دوباره
        </button>
      </div>
    );
  }

  if (!selectedHouse) {
    return (
      <div className={`print-order-card ${compact ? 'is-compact' : ''}`}>
        <p className="print-loading">در حال حاضر چاپخانه‌ی فعالی برای سفارش ثبت نشده است. کمی بعد دوباره سر بزنید.</p>
      </div>
    );
  }

  if (placed) {
    return (
      <div className={`print-order-card print-order-done ${compact ? 'is-compact' : ''}`}>
        <span className="print-order-done-icon"><CircleCheck size={24} /></span>
        <b>سفارش نسخه‌ی چاپی ثبت شد</b>
        <p>
          سفارش شماره‌ی <b>#{placed.id}</b> برای «{product.title}» به چاپخانه‌ی {selectedHouse.name} فرستاده شد.
          پس از تأیید چاپخانه، وضعیت چاپ و کد رهگیری در حساب کاربری شما نمایش داده می‌شود.
        </p>
        <div className="print-order-done-actions">
          <Link className="button button-primary button-small" to="/account?tab=prints">پیگیری سفارش <ArrowLeft size={15} /></Link>
          <button type="button" className="button button-outline button-small" onClick={() => setPlaced(null)}>ثبت سفارش دیگر</button>
        </div>
      </div>
    );
  }

  const currentQuote = quote.data;
  const discountRate = Math.round((options?.digital_owner_discount ?? 0) * 100);

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
          <span>چون نسخه‌ی دیجیتال این کتاب را داری، {discountRate.toLocaleString('fa-IR')}٪ تخفیف چاپ روی این سفارش اعمال می‌شود.</span>
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

      <div className="print-house-brief">
        <span><MapPin size={14} /> {selectedHouse.city}{selectedHouse.province && selectedHouse.province !== selectedHouse.city ? ` · ${selectedHouse.province}` : ''}</span>
        <span><Truck size={14} /> تحویل حدود {selectedHouse.delivery_days.toLocaleString('fa-IR')} روزه</span>
        {selectedHouse.phone && <span><Phone size={14} /> {selectedHouse.phone}</span>}
      </div>

      <div className="print-options-row">
        <label className="field-label">کاغذ
          <select value={paperType} onChange={(event) => setPaperType(event.target.value)}>
            {(options?.paper_types ?? []).map((option) => <option key={option.value} value={option.value}>{option.label}</option>)}
          </select>
        </label>
        <label className="field-label">صحافی
          <select value={binding} onChange={(event) => setBinding(event.target.value)}>
            {(options?.bindings ?? []).map((option) => <option key={option.value} value={option.value}>{option.label}</option>)}
          </select>
        </label>
        <div className="field-label print-quantity">
          تعداد نسخه
          <div className="print-quantity-box">
            <button type="button" onClick={() => changeQuantity(quantity - 1)} disabled={quantity <= selectedHouse.min_quantity} aria-label="کمتر"><Minus size={15} /></button>
            <input
              type="number"
              min={selectedHouse.min_quantity}
              max={selectedHouse.max_quantity}
              value={quantity}
              onChange={(event) => changeQuantity(Number(event.target.value) || selectedHouse.min_quantity)}
            />
            <button type="button" onClick={() => changeQuantity(quantity + 1)} disabled={quantity >= selectedHouse.max_quantity} aria-label="بیشتر"><Plus size={15} /></button>
          </div>
        </div>
      </div>

      <button type="button" className={`toggle-row print-color-toggle ${colorPrint ? 'is-on' : ''}`} onClick={() => setColorPrint(!colorPrint)} aria-pressed={colorPrint}>
        <span>
          <b><Sparkles size={14} /> چاپ رنگی</b>
          <small>{(options?.color_surcharge ?? 0).toLocaleString('fa-IR')} تومان اضافه برای هر نسخه</small>
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
        {quote.isError ? (
          <p className="print-loading">محاسبه‌ی هزینه انجام نشد؛ اتصال را بررسی کنید.</p>
        ) : quote.isFetching && !currentQuote ? (
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

      <button className="button button-primary button-full" disabled={submitOrder.isPending || quote.isError}>
        {submitOrder.isPending ? <><Loader2 size={16} className="search-spinner" /> در حال ثبت…</> : <><Printer size={16} /> ثبت سفارش نسخه‌ی چاپی <ArrowLeft size={15} /></>}
      </button>
      <p className="print-confirm-note"><Check size={14} /> بدون پرداخت آنلاین؛ پس از ثبت، کارشناس چاپخانه برای هماهنگی تماس می‌گیرد.</p>
    </form>
  );
}
