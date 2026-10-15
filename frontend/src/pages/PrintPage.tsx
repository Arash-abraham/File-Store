import { useMemo, useState, type FormEvent } from 'react';
import { useQuery } from '@tanstack/react-query';
import { ArrowLeft, BadgeCheck, Building2, Check, ClipboardList, Factory, Handshake, Loader2, MapPin, PackageCheck, Phone, Search, Send, Sparkles, Star, Truck, Wallet } from 'lucide-react';
import { Link } from 'react-router-dom';
import { api, apiErrorMessage, demoMode } from '../lib/api';
import { demoPrintHouses } from '../data/demo';
import { formatPrice, toman } from '../lib/format';
import type { PrintHouse } from '../types';
import { useStore } from '../StoreContext';

type DirectoryPayload = { houses: PrintHouse[]; cities: string[]; capabilities: string[] };

function unwrapDirectory(value: unknown): DirectoryPayload {
  const empty: DirectoryPayload = { houses: [], cities: [], capabilities: [] };
  if (!value || typeof value !== 'object') return empty;
  const payload = (value as { data?: unknown }).data ?? value;
  if (!payload || typeof payload !== 'object') return empty;
  const record = payload as Record<string, unknown>;
  return {
    houses: (record.print_houses as PrintHouse[]) ?? [],
    cities: (record.cities as string[]) ?? [],
    capabilities: (record.capabilities as string[]) ?? [],
  };
}

const capabilityOptions = ['کاغذ تحریر', 'گلاسه', 'کاغذ کرافت', 'صحافی سخت', 'صحافی فنر', 'چاپ رنگی', 'تیراژ بالا', 'تحویل سریع'];

export default function PrintPage() {
  const { setNotice } = useStore();
  const [city, setCity] = useState('');
  const [sort, setSort] = useState('rating');
  const [contractOpen, setContractOpen] = useState(false);

  const { data, isLoading } = useQuery<DirectoryPayload>({
    queryKey: ['print-directory'],
    staleTime: 5 * 60 * 1000,
    queryFn: async () => {
      if (demoMode) {
        return {
          houses: demoPrintHouses,
          cities: [...new Set(demoPrintHouses.map((house) => house.city))],
          capabilities: [...new Set(demoPrintHouses.flatMap((house) => house.capabilities))],
        };
      }
      const response = await api.get('/print-houses');
      return unwrapDirectory(response.data);
    },
  });

  const houses = useMemo(() => {
    const list = (data?.houses ?? []).filter((house) => !city || house.city === city);
    const sorted = [...list];
    switch (sort) {
      case 'price_asc': sorted.sort((a, b) => a.unit_price - b.unit_price); break;
      case 'price_desc': sorted.sort((a, b) => b.unit_price - a.unit_price); break;
      case 'fastest': sorted.sort((a, b) => a.delivery_days - b.delivery_days); break;
      default: sorted.sort((a, b) => b.rating - a.rating);
    }
    return sorted;
  }, [data, city, sort]);

  const stats = useMemo(() => ({
    houses: data?.houses.length ?? 0,
    cities: data?.cities.length ?? 0,
    fastest: Math.min(...(data?.houses ?? []).map((house) => house.delivery_days), 99),
    from: Math.min(...(data?.houses ?? []).map((house) => house.unit_price), 0),
  }), [data]);

  return (
    <section className="container print-page section-pad">
      <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>چاپ کتاب</b></div>

      <header className="print-hero">
        <span className="eyebrow">از قفسه تا قفسه‌ی خانه</span>
        <h1>کتاب‌هایت را <em>چاپ‌شده</em> داشته باش.</h1>
        <p>بعضی کتاب‌ها باید ورق بخورند. نسخه‌ی کاغذی همان کتابی را که خریده‌ای، از میان چاپخانه‌های همکار فایل‌استور سفارش بده — با انتخاب کاغذ، صحافی و تیراژ.</p>
        <div className="print-hero-actions">
          <Link className="button button-primary" to="/catalog">انتخاب کتاب برای چاپ <ArrowLeft size={16} /></Link>
          <button className="button button-outline" onClick={() => setContractOpen(true)}><Handshake size={16} /> من چاپخانه دارم</button>
        </div>
      </header>

      <div className="print-stats">
        <article><span className="print-stat-icon"><Factory size={20} /></span><b>{stats.houses.toLocaleString('fa-IR')} چاپخانه</b><small>چاپخانه‌ی همکار فعال</small></article>
        <article><span className="print-stat-icon"><MapPin size={20} /></span><b>{stats.cities.toLocaleString('fa-IR')} شهر</b><small>پوشش سراسری ارسال</small></article>
        <article><span className="print-stat-icon"><Truck size={20} /></span><b>از {stats.fastest === 99 ? '—' :  toman(stats.fastest) } روز</b><small>سریع‌ترین تحویل</small></article>
        <article><span className="print-stat-icon"><Wallet size={20} /></span><b>از {stats.from ? formatPrice(stats.from) : '—'}</b><small>هزینه‌ی هر نسخه</small></article>
      </div>

      <section className="print-steps">
        <h2>چطور کتاب چاپی‌ات را بگیری؟</h2>
        <ol>
          <li><span><ClipboardList size={19} /></span><b>کتاب را انتخاب کن</b><p>از صفحه‌ی هر کتاب، دکمه‌ی «سفارش نسخه‌ی چاپی» را بزن.</p></li>
          <li><span><Factory size={19} /></span><b>چاپخانه و کاغذ</b><p>چاپخانه‌ی شهرت را با قیمت و زمان تحویلش انتخاب کن.</p></li>
          <li><span><PackageCheck size={19} /></span><b>ثبت و تأیید</b><p>سفارش ثبت می‌شود؛ چاپخانه تأیید می‌کند و چاپ شروع می‌شود.</p></li>
          <li><span><Truck size={19} /></span><b>تحویل درِ خانه</b><p>نسخه‌ی صحافی‌شده با پست برایت ارسال می‌شود.</p></li>
        </ol>
      </section>

      <section className="print-directory">
        <div className="print-directory-heading">
          <div>
            <span className="eyebrow">فهرست چاپخانه‌ها</span>
            <h2>چاپخانه‌های همکار</h2>
            <p>قیمت‌ها بابت چاپ هر نسخه است و هزینه‌ی ارسال جدا محاسبه می‌شود.</p>
          </div>
          <div className="print-filters">
            <label className="print-select-inline"><Search size={15} />
              <select value={city} onChange={(event) => setCity(event.target.value)}>
                <option value="">همه‌ی شهرها</option>
                {(data?.cities ?? []).map((item) => <option key={item} value={item}>{item}</option>)}
              </select>
            </label>
            <label className="print-select-inline">
              <select value={sort} onChange={(event) => setSort(event.target.value)}>
                <option value="rating">بیشترین امتیاز</option>
                <option value="price_asc">ارزان‌ترین</option>
                <option value="price_desc">گران‌ترین</option>
                <option value="fastest">سریع‌ترین تحویل</option>
              </select>
            </label>
          </div>
        </div>

        {isLoading ? (
          <div className="print-house-grid">{[0, 1, 2].map((item) => <div className="print-house-skeleton" key={item} />)}</div>
        ) : houses.length === 0 ? (
          <div className="empty-state"><span className="empty-state-icon"><Factory size={22} /></span><h3>چاپخانه‌ای با این فیلتر پیدا نشد</h3><p>شهر دیگری را امتحان کن یا فیلتر را پاک کن.</p><button className="button button-outline" onClick={() => setCity('')}>همه‌ی شهرها</button></div>
        ) : (
          <div className="print-house-grid">
            {houses.map((house) => <PrintHouseCard key={house.id} house={house} />)}
          </div>
        )}
      </section>

      <section className={`print-contract ${contractOpen ? 'is-open' : ''}`} id="contract">
        <div className="print-contract-intro">
          <span className="eyebrow">همکاری با فایل‌استور</span>
          <h2>چاپخانه‌ات را به فروشگاه وصل کن</h2>
          <p>اگر چاپخانه، صحافی یا مجموعه‌ی چاپ دیجیتال دارید، فرم همکاری را پر کنید. پس از بررسی، قرارداد همکاری و شرایط تسویه را برایتان می‌فرستیم و چاپخانه‌ی شما در فهرست بالا نمایش داده می‌شود.</p>
          <ul className="print-contract-points">
            <li><BadgeCheck size={16} /> سفارش‌های آماده‌ی کتاب‌های دیجیتال فروشگاه</li>
            <li><BadgeCheck size={16} /> تسویه‌ی منظم و قرارداد شفاف</li>
            <li><BadgeCheck size={16} /> پروفایل اختصاصی با قیمت و زمان تحویل شما</li>
          </ul>
          {!contractOpen && <button className="button button-primary" onClick={() => setContractOpen(true)}><Handshake size={16} /> پر کردن فرم قرارداد</button>}
        </div>
        {contractOpen && <ContractForm onDone={() => { setContractOpen(false); setNotice('درخواست همکاری ثبت شد؛ به‌زودی با شما تماس می‌گیریم.'); }} />}
      </section>
    </section>
  );
}

function PrintHouseCard({ house }: { house: PrintHouse }) {
  return (
    <article className="print-house-card">
      <div className="print-house-top">
        <span className="print-house-mark" aria-hidden="true">{house.name.trim().slice(0, 1)}</span>
        <div className="print-house-title">
          <b>{house.name}</b>
          <small><MapPin size={13} /> {house.city}{house.province && house.province !== house.city ? ` · ${house.province}` : ''}</small>
        </div>
        <span className="print-house-rating"><Star size={13} fill="currentColor" /> {house.rating.toLocaleString('fa-IR')}</span>
      </div>

      {house.description && <p className="print-house-desc">{house.description}</p>}

      <div className="print-house-chips">
        {house.capabilities.slice(0, 4).map((capability) => <span key={capability}>{capability}</span>)}
      </div>

      <dl className="print-house-facts">
        <div><dt>هر نسخه</dt><dd>{formatPrice(house.unit_price)}</dd></div>
        <div><dt>جلد سخت</dt><dd>+ {formatPrice(house.hardcover_surcharge)}</dd></div>
        <div><dt>ارسال</dt><dd>{formatPrice(house.delivery_fee)}</dd></div>
        <div><dt>تحویل</dt><dd>{house.delivery_days.toLocaleString('fa-IR')} روز</dd></div>
        <div><dt>تیراژ</dt><dd>{house.min_quantity.toLocaleString('fa-IR')} تا {house.max_quantity.toLocaleString('fa-IR')} نسخه</dd></div>
        {typeof house.orders_count === 'number' && <div><dt>سفارش‌ها</dt><dd>{house.orders_count.toLocaleString('fa-IR')}</dd></div>}
      </dl>

      <div className="print-house-foot">
        {house.phone && <span className="print-house-contact"><Phone size={14} /> {house.phone}</span>}
        <Link className="text-link" to="/catalog">انتخاب کتاب برای این چاپخانه <ArrowLeft size={14} /></Link>
      </div>
    </article>
  );
}

function ContractForm({ onDone }: { onDone: () => void }) {
  const [form, setForm] = useState({ company_name: '', contact_name: '', phone: '', email: '', city: '', monthly_capacity: '', message: '' });
  const [capabilities, setCapabilities] = useState<string[]>([]);
  const [error, setError] = useState('');
  const [sending, setSending] = useState(false);
  const [sent, setSent] = useState(false);

  function toggle(capability: string) {
    setCapabilities((current) => current.includes(capability) ? current.filter((item) => item !== capability) : [...current, capability]);
  }

  async function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setError('');
    setSending(true);
    try {
      if (!demoMode) {
        await api.post('/print-houses/contract-requests', {
          ...form,
          monthly_capacity: form.monthly_capacity ? Number(form.monthly_capacity) : null,
          capabilities,
          email: form.email || null,
        });
      }
      setSent(true);
      onDone();
    } catch (reason) {
      setError(apiErrorMessage(reason, 'ثبت درخواست همکاری انجام نشد.'));
    } finally {
      setSending(false);
    }
  }

  if (sent) {
    return (
      <div className="print-contract-form print-contract-success">
        <span className="print-contract-success-icon"><Check size={22} /></span>
        <b>درخواست شما ثبت شد.</b>
        <p>کارشناس قراردادهای فایل‌استور برای بررسی ظرفیت و امضای قرارداد با شما تماس می‌گیرد.</p>
        <button className="button button-outline button-small" onClick={() => { setSent(false); setForm({ company_name: '', contact_name: '', phone: '', email: '', city: '', monthly_capacity: '', message: '' }); setCapabilities([]); }}>ثبت درخواست دیگر</button>
      </div>
    );
  }

  return (
    <form className="print-contract-form" onSubmit={submit}>
      <div className="print-contract-form-head">
        <span className="print-order-icon"><Building2 size={19} /></span>
        <div><b>فرم درخواست قرارداد همکاری</b><small>اطلاعات چاپخانه را وارد کنید؛ همه‌ی فیلدهای ستاره‌دار الزامی است.</small></div>
      </div>

      <div className="print-contract-grid">
        <label className="field-label">نام چاپخانه/مجموعه *<input required value={form.company_name} onChange={(event) => setForm({ ...form, company_name: event.target.value })} maxLength={150} /></label>
        <label className="field-label">نام مسئول *<input required value={form.contact_name} onChange={(event) => setForm({ ...form, contact_name: event.target.value })} maxLength={120} /></label>
        <label className="field-label">شماره تماس *<input required value={form.phone} onChange={(event) => setForm({ ...form, phone: event.target.value })} maxLength={30} placeholder="۰۲۱…" /></label>
        <label className="field-label">ایمیل<input type="email" value={form.email} onChange={(event) => setForm({ ...form, email: event.target.value })} maxLength={255} /></label>
        <label className="field-label">شهر *<input required value={form.city} onChange={(event) => setForm({ ...form, city: event.target.value })} maxLength={80} /></label>
        <label className="field-label">ظرفیت ماهانه (نسخه)<input type="number" min="1" value={form.monthly_capacity} onChange={(event) => setForm({ ...form, monthly_capacity: event.target.value })} placeholder="مثلاً ۵۰۰" /></label>
      </div>

      <div className="print-contract-capabilities">
        <span>توانمندی‌ها</span>
        <div className="quick-chips">{capabilityOptions.map((capability) => <button type="button" key={capability} className={`chip ${capabilities.includes(capability) ? 'is-active' : ''}`} onClick={() => toggle(capability)}>{capability}</button>)}</div>
      </div>

      <label className="field-label">توضیح بیشتر<textarea rows={3} value={form.message} onChange={(event) => setForm({ ...form, message: event.target.value })} maxLength={2000} placeholder="درباره‌ی تجهیزات، سابقه‌ی همکاری با ناشران و شرایط تسویه بنویسید." /></label>

      {error && <div className="form-error">{error}</div>}

      <button className="button button-primary" disabled={sending}>
        {sending ? <><Loader2 size={16} className="search-spinner" /> در حال ارسال…</> : <><Send size={16} /> ارسال درخواست همکاری</>}
      </button>
      <p className="print-terms"><Sparkles size={14} /> پس از تأیید، پروفایل چاپخانه‌ی شما در همین صفحه برای خواننده‌ها نمایش داده می‌شود.</p>
    </form>
  );
}
