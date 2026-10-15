import { useQuery } from '@tanstack/react-query';
import { ArrowLeft, ArrowUpLeft, BadgeCheck, BookMarked, BookOpen, ChevronLeft, Clock3, Download, Headphones, Search, ShieldCheck, Sparkles, Star } from 'lucide-react';
import { Link, useNavigate } from 'react-router-dom';
import { api, demoMode } from '../lib/api';
import { demoCategories, demoProducts } from '../data/demo';
import type { Category, Product } from '../types';
import ProductCard from '../components/ProductCard';
import { useStore } from '../StoreContext';

const categoryIllustrations = [BookOpen, Sparkles, BookMarked, Star, Headphones];

function unwrapArray<T>(value: unknown): T[] {
  if (Array.isArray(value)) return value as T[];
  if (value && typeof value === 'object' && 'data' in value) {
    const nested = (value as { data: unknown }).data;
    if (Array.isArray(nested)) return nested as T[];
  }
  return [];
}

export default function HomePage() {
  const navigate = useNavigate();
  const { setNotice } = useStore();
  const { data, isLoading } = useQuery({
    queryKey: ['storefront-home'],
    queryFn: async () => {
      if (demoMode) return { categories: demoCategories, products: demoProducts };
      const response = await api.get('/home');
      const payload = response.data.data ?? {};
      return {
        categories: unwrapArray<Category>(payload.categories),
        products: unwrapArray<Product>(payload.featured_products),
      };
    },
  });
  const categories = data?.categories ?? (demoMode ? demoCategories : []);
  const products = data?.products ?? (demoMode ? demoProducts : []);

  function submitSearch(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const form = new FormData(event.currentTarget);
    const q = String(form.get('q') ?? '').trim();
    navigate(`/catalog${q ? `?q=${encodeURIComponent(q)}` : ''}`);
  }

  return (
    <>
      <section className="hero-section">
        <div className="container hero-layout">
          <div className="hero-copy">
            <div className="hero-label"><span className="hero-label-icon"><Sparkles size={14} /></span> برای روزهایی که می‌خواهی بیشتر بدانی</div>
            <h1>کتاب بعدی‌ات<br /><span>همین‌جاست.</span></h1>
            <p>یک کتاب خوب می‌تواند مسیر یک روز را عوض کند.<br className="desktop-break" /> کتاب‌های دیجیتالِ انتخاب‌شده، برای فکرهای کنجکاو.</p>
            <form className="hero-search" onSubmit={submitSearch}>
              <Search size={19} />
              <input name="q" placeholder="موضوع، عنوان یا نویسنده را جست‌وجو کن" aria-label="جست‌وجوی کتاب" />
              <button type="submit">جست‌وجو <ArrowLeft size={16} /></button>
            </form>
            <div className="hero-proof"><div className="avatar-stack"><span>م</span><span>آ</span><span>س</span><span>+</span></div><span><b>بیش از ۲٬۴۰۰ نفر</b> همین هفته کتاب خوانده‌اند</span><span className="proof-rating"><Star size={14} fill="currentColor" /> ۴٫۹ از ۵</span></div>
          </div>
          <div className="hero-art" aria-label="چند جلد کتاب دیجیتال">
            <div className="hero-art-halo" />
            <div className="hero-quote-card"><span className="quote-mark">“</span><p>هر کتاب، دری است<br />که از آن می‌شود<br /><b>جهانی تازه</b> دید.</p><small>— کتاب‌خوانِ ناشناس</small></div>
            <div className="hero-book hero-book-back"><img src="/images/books/nginx-cookbook.png" alt="جلد کتاب آشپزخانه‌ی NGINX" /></div>
            <div className="hero-book hero-book-front"><img src="/images/books/linux-basics.svg" alt="جلد کتاب لینوکس برای هکرها" /></div>
            <div className="hero-sticker"><span>چاپ تازه</span><b>PDF</b><small>همیشه همراهت</small></div>
            <div className="hero-art-sparkle sparkle-a">✳</div><div className="hero-art-sparkle sparkle-b">✦</div>
            <div className="hero-art-caption"><span className="caption-line" /> انتخاب این هفته <b>شماره ۰۷</b></div>
          </div>
        </div>
        <div className="container hero-bottom-line"><span>خواندن، سرمایه‌گذاری روی خودت است.</span><span className="hero-scroll-label">پایین‌تر را ببین <span>↓</span></span></div>
      </section>

      <section className="category-section container section-pad">
        <div className="section-heading section-heading-inline">
          <div><span className="eyebrow">از کجا شروع کنیم؟</span><h2>دنیای مورد علاقه‌ات را انتخاب کن</h2></div>
          <Link to="/catalog" className="text-link">همه‌ی دسته‌ها <ArrowLeft size={15} /></Link>
        </div>
        <div className="category-grid">
          {categories.map((category: Category, index: number) => {
            const Icon = categoryIllustrations[index % categoryIllustrations.length];
            return <Link to={`/catalog?category=${category.slug}`} className={`category-tile category-${index % 5}`} key={category.slug}>
              <span className="category-icon"><Icon size={21} strokeWidth={1.8} /></span>
              <span className="category-tile-copy"><b>{category.name}</b><small>{category.products_count.toLocaleString('fa-IR')} عنوان برای کشف</small></span>
              <ArrowUpLeft size={17} className="category-arrow" />
            </Link>;
          })}
        </div>
      </section>

      <section className="featured-section section-pad">
        <div className="container">
          <div className="section-heading section-heading-inline">
            <div><span className="eyebrow">انتخابِ خواننده‌ها</span><h2>کتاب‌هایی که دوستشان دارند</h2><p>آثاری که از قفسه‌ها جدا نمی‌شوند.</p></div>
            <Link to="/catalog" className="button button-outline">دیدن همه‌ی کتاب‌ها <ArrowLeft size={16} /></Link>
          </div>
          {isLoading ? <div className="product-grid">{[0, 1, 2, 3].map((item) => <div className="skeleton-card" key={item} />)}</div> : (
            <div className="product-grid">{products.slice(0, 4).map((product: Product, index: number) => <ProductCard key={product.id} product={product} index={index} />)}</div>
          )}
        </div>
      </section>

      <section className="manifesto-section container">
        <div className="manifesto-card">
          <div className="manifesto-overline"><span>فایل‌استور، با فکر ساخته شده</span><span className="manifesto-number">01 / 03</span></div>
          <div className="manifesto-content"><div><h2>کمتر بگرد.<br /><em>بیشتر کشف کن.</em></h2><p>ما به‌جای یک قفسه‌ی بی‌انتها، کتاب‌هایی را کنار هم می‌گذاریم که واقعاً ارزش وقت و توجه تو را دارند.</p><Link to="/about" className="button button-light">داستان ما را بخوان <ArrowLeft size={16} /></Link></div>
            <div className="manifesto-visual"><div className="manifesto-circle circle-one" /><div className="manifesto-circle circle-two" /><div className="manifesto-book"><BookOpen size={45} /><span>THE<br />NEXT<br />CHAPTER</span></div><div className="manifesto-note note-one"><BadgeCheck size={16} /> انتخاب‌شده با دقت</div><div className="manifesto-note note-two">یک ایده‌ی خوب<br /><b>همه‌چیز را عوض می‌کند.</b></div></div>
          </div>
          <div className="manifesto-footer"><span>نه شلوغیِ بی‌دلیل؛ فقط کتاب‌های درست.</span><span className="manifesto-dots"><i className="active" /><i /><i /></span></div>
        </div>
      </section>

      <section className="why-section container section-pad">
        <div className="section-heading"><span className="eyebrow">تجربه‌ای برای خواندن</span><h2>کتابخانه‌ای که با تو می‌ماند</h2></div>
        <div className="benefit-grid">
          <article className="benefit-card"><span className="benefit-icon benefit-blue"><Download size={22} /></span><h3>یک‌بار بخر، همیشه بخوان</h3><p>کتاب‌های خریده‌شده در کتابخانه‌ی شخصی تو می‌مانند؛ هر وقت و هرجا که خواستی.</p><span className="benefit-index">۰۱</span></article>
          <article className="benefit-card"><span className="benefit-icon benefit-yellow"><ShieldCheck size={22} /></span><h3>پرداخت امن و روشن</h3><p>قیمت نهایی از قبل مشخص است و پرداخت فقط از مسیر امن درگاه انجام می‌شود.</p><span className="benefit-index">۰۲</span></article>
          <article className="benefit-card"><span className="benefit-icon benefit-green"><Headphones size={22} /></span><h3>پشتیبانی، نه ربات</h3><p>اگر در دانلود یا سفارش به مشکلی خوردی، آدم‌های واقعی پیگیرش می‌شوند.</p><span className="benefit-index">۰۳</span></article>
        </div>
      </section>

      <section className="newsletter-strip container">
        <div className="newsletter-icon"><BookMarked size={27} /></div>
        <div><span className="eyebrow">کمی کتاب، کمی الهام</span><h2>یک ایمیل، یک پیشنهادِ خوب</h2><p>ما هر هفته فقط یک کتاب و یک دلیل برای خواندنش می‌فرستیم.</p></div>
        <form onSubmit={(event) => { event.preventDefault(); setNotice('عضویت در خبرنامه به‌زودی فعال می‌شود.'); }}><input type="email" placeholder="ایمیل خودت را بنویس" aria-label="ایمیل خبرنامه" required /><button type="submit">عضویت <ArrowLeft size={16} /></button></form>
      </section>

      <div className="container home-last-line"><span><Clock3 size={14} /> هر کتاب، شروع یک گفت‌وگوی تازه</span><Link to="/catalog">برو سراغ قفسه‌ها <ChevronLeft size={16} /></Link></div>
    </>
  );
}
