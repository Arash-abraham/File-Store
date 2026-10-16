import { useEffect, useMemo, useState, type FormEvent } from 'react';
import { useQuery } from '@tanstack/react-query';
import { ArrowLeft, BadgeCheck, BookOpen, Building2, Check, ClipboardList, Factory, Handshake, Loader2, MapPin, PackageCheck, Phone, Search, Send, Sparkles, Star, Truck, Wallet, X } from 'lucide-react';
import { Link } from 'react-router-dom';
import { api, apiErrorMessage, demoMode } from '../lib/api';
import { demoProducts } from '../data/demo';
import { fetchPrintDirectory, submitPrintContract } from '../lib/print';
import { formatPrice, toman } from '../lib/format';
import type { PrintHouse, Product } from '../types';
import { useStore } from '../StoreContext';
import PrintOrderForm from '../components/PrintOrderForm';

type SortKey = 'rating' | 'price_asc' | 'price_desc' | 'fastest';

const capabilityOptions = ['کاغذ تحریر', 'گلاسه', 'کاغذ کرافت', 'صحافی سخت', 'صحافی فنر', 'چاپ رنگی', 'تیراژ بالا', 'تحویل سریع'];

function unwrapProducts(value: unknown): Product[] {
  const payload = (value as { data?: unknown })?.data;
  return Array.isArray(payload) ? (payload as Product[]) : [];
}

export default function PrintPage() {
  const { setNotice } = useStore();
  const [city, setCity] = useState('');
  const [capability, setCapability] = useState('');
  const [search, setSearch] = useState('');
  const [sort, setSort] = useState<SortKey>('rating');
  const [contractOpen, setContractOpen] = useState(false);

  // The quick order flow: a partner, a book, and the order form itself.
  const [orderHouse, setOrderHouse] = useState<number | null>(null);
  const [orderBook, setOrderBook] = useState<Product | null>(null);
  const [bookQuery, setBookQuery] = useState('');
  const [bookTerm, setBookTerm] = useState('');

  // The search box talks to the API, so the term is debounced first.
  useEffect(() => {
    const timer = setTimeout(() => setBookTerm(bookQuery.trim()), 350);
    return () => clearTimeout(timer);
  }, [bookQuery]);

  const { data, isLoading, isError, refetch } = useQuery({
    queryKey: ['print-directory'],
    staleTime: 5 * 60 * 1000,
    queryFn: fetchPrintDirectory,
  });

  const houses = useMemo(() => data?.houses ?? [], [data]);

  const books = useQuery<Product[]>({
    queryKey: ['print-books', bookTerm],
    staleTime: 60 * 1000,
    queryFn: async () => {
      if (demoMode) {
        return bookTerm
          ? demoProducts.filter((item) => item.title.includes(bookTerm)).slice(0, 8)
          : demoProducts.slice(0, 8);
      }
      const response = await api.get('/products', { params: { q: bookTerm || undefined, per_page: 12 } });
      return unwrapProducts(response.data);
    },
  });

  const visibleHouses = useMemo(() => {
    const term = search.trim();
    const list = houses.filter((house) => {
      if (city && house.city !== city) return false;
      if (capability && !house.capabilities.includes(capability)) return false;
      if (term && !`${house.name} ${house.city} ${house.description ?? ''}`.includes(term)) return false;
      return true;
    });

    const sorted = [...list];
    switch (sort) {
      case 'price_asc': sorted.sort((a, b) => a.unit_price - b.unit_price); break;
      case 'price_desc': sorted.sort((a, b) => b.unit_price - a.unit_price); break;
      case 'fastest': sorted.sort((a, b) => a.delivery_days - b.delivery_days); break;
      default: sorted.sort((a, b) => Number(b.is_featured) - Number(a.is_featured) || b.rating - a.rating);
    }
    return sorted;
  }, [houses, city, capability, search, sort]);

  const stats = data?.stats;
  const fastest = stats?.fastest_delivery_days ?? 0;
  const fromPrice = stats?.from_price ?? 0;

  // The first partner is preselected, so the order flow is always one step ahead.
  useEffect(() => {
    if (!orderHouse && houses.length) setOrderHouse(houses[0].id);
  }, [houses, orderHouse]);

  function startOrder(house: PrintHouse) {
    setOrderHouse(house.id);
    document.getElementById('print-order')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  function selectBook(book: Product) {
    setOrderBook(book);
    // On small screens the form sits under the picker, so bring it into view.
    if (window.innerWidth < 1050) {
      window.setTimeout(() => {
        document.getElementById('print-order-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 140);
    }
  }

  return (
    <section className="container print-page section-pad">
      <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>چاپ کتاب</b></div>

      <header className="print-hero">
        <span className="eyebrow">از قفسه تا قفسه‌ی خانه</span>
        <h1>کتاب‌هایت را <em>چاپ‌شده</em> داشته باش.</h1>
        <p>بعضی کتاب‌ها باید ورق بخورند. نسخه‌ی کاغذی همان کتابی را که خریده‌ای، از میان چاپخانه‌های همکار فایل‌استور سفارش بده — با انتخاب کاغذ، صحافی و تیراژ.</p>
        <div className="print-hero-actions">
          <a className="button button-primary" href="#print-order">سفارش نسخه‌ی چاپی <ArrowLeft size={16} /></a>
          <button className="button button-outline" onClick={() => setContractOpen(true)}><Handshake size={16} /> من چاپخانه دارم</button>
        </div>
      </header>

      <div className="print-stats">
        <article><span className="print-stat-icon"><Factory size={20} /></span><b>{(stats?.print_houses ?? 0).toLocaleString('fa-IR')} چاپخانه</b><small>چاپخانه‌ی همکار فعال</small></article>
        <article><span className="print-stat-icon"><MapPin size={20} /></span><b>{(stats?.cities ?? 0).toLocaleString('fa-IR')} شهر</b><small>پوشش سراسری ارسال</small></article>
        <article><span className="print-stat-icon"><Truck size={20} /></span><b>از {fastest ? toman(fastest) : '—'} روز</b><small>سریع‌ترین تحویل</small></article>
        <article><span className="print-stat-icon"><Wallet size={20} /></span><b>از {fromPrice ? formatPrice(fromPrice) : '—'}</b><small>هزینه‌ی هر نسخه</small></article>
      </div>

      <section className="print-steps">
        <h2>چطور کتاب چاپی‌ات را بگیری؟</h2>
        <ol>
          <li><span><ClipboardList size={19} /></span><b>کتاب را انتخاب کن</b><p>در همین صفحه کتابت را جست‌وجو کن یا از صفحه‌ی هر کتاب دکمه‌ی «سفارش نسخه‌ی چاپی» را بزن.</p></li>
          <li><span><Factory size={19} /></span><b>چاپخانه و کاغذ</b><p>چاپخانه‌ی شهرت را با قیمت و زمان تحویلش انتخاب کن.</p></li>
          <li><span><PackageCheck size={19} /></span><b>ثبت و تأیید</b><p>سفارش ثبت می‌شود؛ چاپخانه تأیید می‌کند و چاپ شروع می‌شود.</p></li>
          <li><span><Truck size={19} /></span><b>تحویل درِ خانه</b><p>نسخه‌ی صحافی‌شده با پست برایت ارسال می‌شود.</p></li>
        </ol>
      </section>

      <section className="print-order-panel" id="print-order">
        <div className="print-panel-heading">
          <div>
            <span className="eyebrow">سفارش سریع</span>
            <h2>همین حالا نسخه‌ی چاپی را سفارش بده</h2>
            <p>چاپخانه را انتخاب کن، کتابت را پیدا کن و فرم سفارش را پر کن؛ قیمت همان لحظه از سرور گرفته می‌شود.</p>
          </div>
          <ol className="print-panel-steps">
            <li className={orderHouse ? 'is-done' : 'is-active'}><span>۱</span> چاپخانه</li>
            <li className={orderBook ? 'is-done' : orderHouse ? 'is-active' : ''}><span>۲</span> کتاب</li>
            <li className={orderBook ? 'is-active' : ''}><span>۳</span> ثبت سفارش</li>
          </ol>
        </div>

        <div className="print-order-panel-body">
          <div className="print-order-picker">
            <div className="print-picker-block">
              <span className="print-picker-title"><Factory size={15} /> ۱. چاپخانه‌ی همکار</span>
              {isLoading ? (
                <div className="print-house-chips">{[0, 1, 2].map((item) => <span className="print-chip-skeleton" key={item} />)}</div>
              ) : isError ? (
                <div className="print-picker-error">
                  <span>فهرست چاپخانه‌ها دریافت نشد.</span>
                  <button className="button button-outline button-small" onClick={() => refetch()}>تلاش دوباره</button>
                </div>
              ) : (
                <div className="print-house-chips">
                  {houses.map((house) => (
                    <button
                      type="button"
                      key={house.id}
                      className={`print-house-chip ${orderHouse === house.id ? 'is-active' : ''}`}
                      onClick={() => setOrderHouse(house.id)}
                    >
                      <b>{house.name}</b>
                      <small>{house.city} · {formatPrice(house.unit_price)} · {house.delivery_days.toLocaleString('fa-IR')} روزه</small>
                    </button>
                  ))}
                </div>
              )}
            </div>

            <div className="print-picker-block">
              <span className="print-picker-title"><BookOpen size={15} /> ۲. کتابی که می‌خواهی چاپ شود</span>
              <label className="print-book-search">
                <Search size={16} />
                <input value={bookQuery} onChange={(event) => setBookQuery(event.target.value)} placeholder="جست‌وجوی نام کتاب…" />
                {bookQuery && <button type="button" onClick={() => setBookQuery('')} aria-label="پاک کردن"><X size={15} /></button>}
              </label>
              {books.isLoading ? (
                <div className="print-book-grid">{[0, 1, 2, 3].map((item) => <div className="print-book-skeleton" key={item} />)}</div>
              ) : books.data?.length ? (
                <div className="print-book-grid">
                  {books.data.map((book) => (
                    <button
                      type="button"
                      key={book.id}
                      className={`print-book-card ${orderBook?.id === book.id ? 'is-active' : ''}`}
                      onClick={() => selectBook(book)}
                    >
                      <span className="print-book-cover">
                        {book.cover_url ? <img src={book.cover_url} alt="" /> : <BookOpen size={18} />}
                      </span>
                      <b>{book.title}</b>
                      {orderBook?.id === book.id && <span className="print-book-check"><Check size={13} /></span>}
                    </button>
                  ))}
                </div>
              ) : (
                <p className="print-picker-empty">کتابی با این نام پیدا نشد؛ عبارت دیگری را امتحان کن.</p>
              )}
            </div>
          </div>

          <div className="print-order-panel-form" id="print-order-form">
            {orderBook ? (
              <PrintOrderForm product={orderBook} initialHouseId={orderHouse} />
            ) : (
              <div className="print-order-placeholder">
                <span><BookOpen size={22} /></span>
                <b>کتابت را انتخاب کن</b>
                <p>از فهرست بالا یک کتاب بردار تا فرم سفارش نسخه‌ی چاپی اینجا باز شود.</p>
              </div>
            )}
          </div>
        </div>
      </section>

      <section className="print-directory">
        <div className="print-directory-heading">
          <div>
            <span className="eyebrow">فهرست چاپخانه‌ها</span>
            <h2>چاپخانه‌های همکار</h2>
            <p>قیمت‌ها بابت چاپ هر نسخه است و هزینه‌ی ارسال جدا محاسبه می‌شود.</p>
          </div>
          <div className="print-filters">
            <label className="print-search-inline">
              <Search size={15} />
              <input value={search} onChange={(event) => setSearch(event.target.value)} placeholder="نام چاپخانه یا شهر…" />
            </label>
            <label className="print-select-inline">
              <select value={city} onChange={(event) => setCity(event.target.value)}>
                <option value="">همه‌ی شهرها</option>
                {(data?.cities ?? []).map((item) => <option key={item} value={item}>{item}</option>)}
              </select>
            </label>
            <label className="print-select-inline">
              <select value={capability} onChange={(event) => setCapability(event.target.value)}>
                <option value="">همه‌ی توانمندی‌ها</option>
                {(data?.capabilities ?? []).map((item) => <option key={item} value={item}>{item}</option>)}
              </select>
            </label>
            <label className="print-select-inline">
              <select value={sort} onChange={(event) => setSort(event.target.value as SortKey)}>
                <option value="rating">بیشترین امتیاز</option>
                <option value="price_asc">ارزان‌ترین</option>
                <option value="price_desc">گران‌ترین</option>
                <option value="fastest">سریع‌ترین تحویل</option>
              </select>
            </label>
          </div>
        </div>

        {visibleHouses.length === 0 && !isLoading ? (
          <div className="empty-state"><span className="empty-state-icon"><Factory size={22} /></span><h3>چاپخانه‌ای با این فیلتر پیدا نشد</h3><p>شهر دیگری را امتحان کن یا فیلترها را پاک کن.</p><button className="button button-outline" onClick={() => { setCity(''); setCapability(''); setSearch(''); }}>پاک کردن فیلترها</button></div>
        ) : (
          <div className="print-house-grid">
            {isLoading
              ? [0, 1, 2].map((item) => <div className="print-house-skeleton" key={item} />)
              : visibleHouses.map((house) => <PrintHouseCard key={house.id} house={house} onOrder={() => startOrder(house)} />)}
          </div>
        )}
      </section>

      <section className={`print-contract ${contractOpen ? 'is-open' : ''}`} id="contract">
        <div className="print-contract-intro">
          <span className="eyebrow">همکاری با فایل‌استور</span>
          <h2>چاپخانه‌ات را به فروشگاه وصل کن</h2>
          <p>اگر چاپخانه، صحافی یا مجموعه‌ی چاپ دیجیتال دارید، فرم همکاری را پر کنید. پس از بررسی و تأیید، پروفایل چاپخانه‌ی شما با قیمت و زمان تحویل در همین صفحه نمایش داده می‌شود و سفارش‌های کتاب‌های فروشگاه به شما می‌رسد.</p>
          <ul className="print-contract-points">
            <li><BadgeCheck size={16} /> سفارش‌های آماده‌ی کتاب‌های دیجیتال فروشگاه</li>
            <li><BadgeCheck size={16} /> تسویه‌ی منظم و قرارداد شفاف</li>
            <li><BadgeCheck size={16} /> پروفایل اختصاصی با قیمت و زمان تحویل شما</li>
          </ul>
          {!contractOpen && <button className="button button-primary" onClick={() => setContractOpen(true)}><Handshake size={16} /> پر کردن فرم قرارداد</button>}
        </div>
        {contractOpen && <ContractForm onClose={() => setContractOpen(false)} onDone={() => setNotice('درخواست همکاری ثبت شد؛ به‌زودی با شما تماس می‌گیریم.')} />}
      </section>
    </section>
  );
}

function PrintHouseCard({ house, onOrder }: { house: PrintHouse; onOrder: () => void }) {
  return (
    <article className={`print-house-card ${house.is_featured ? 'is-featured' : ''}`}>
      <div className="print-house-top">
        <span className="print-house-mark" aria-hidden="true">{house.name.trim().slice(0, 1)}</span>
        <div className="print-house-title">
          <b>{house.name}</b>
          <small><MapPin size={13} /> {house.city}{house.province && house.province !== house.city ? ` · ${house.province}` : ''}</small>
        </div>
        <span className="print-house-rating"><Star size={13} fill="currentColor" /> {house.rating.toLocaleString('fa-IR')}</span>
      </div>

      {house.is_featured && <span className="print-house-badge"><Sparkles size={13} /> چاپخانه‌ی ویژه</span>}

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
        <button className="text-link" onClick={onOrder}>سفارش با این چاپخانه <ArrowLeft size={14} /></button>
      </div>
    </article>
  );
}

function ContractForm({ onDone, onClose }: { onDone: () => void; onClose: () => void }) {
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
      await submitPrintContract({
        ...form,
        monthly_capacity: form.monthly_capacity ? Number(form.monthly_capacity) : null,
        capabilities,
        email: form.email || null,
      });
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
        <p>کارشناس قراردادهای فایل‌استور برای بررسی ظرفیت و امضای قرارداد با شما تماس می‌گیرد. پس از تأیید، پروفایل چاپخانه در همین صفحه نمایش داده می‌شود.</p>
        <div className="print-contract-success-actions">
          <button className="button button-outline button-small" onClick={() => { setSent(false); setForm({ company_name: '', contact_name: '', phone: '', email: '', city: '', monthly_capacity: '', message: '' }); setCapabilities([]); }}>ثبت درخواست دیگر</button>
          <button className="button button-primary button-small" onClick={onClose}>بستن</button>
        </div>
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
