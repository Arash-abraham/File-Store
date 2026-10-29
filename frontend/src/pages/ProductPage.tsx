import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { ArrowLeft, Bookmark, BookOpenCheck, Check, CircleCheck, Download, Heart, PackageCheck, Printer, ShieldCheck, ShoppingBag, Star, Truck, ZoomIn, X, BookOpen } from 'lucide-react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { api, demoMode } from '../lib/api';
import { demoProducts, demoReviews } from '../data/demo';
import { formatPrice } from '../lib/format';
import type { Product, Review } from '../types';
import ProductCard from '../components/ProductCard';
import PrintOrderForm from '../components/PrintOrderForm';
import ProductDiscussion from '../components/ProductDiscussion';
import { useStore } from '../StoreContext';
import { useFavorites } from '../lib/useFavorites';

type ProductDetail = { product: Product; files: { id: number; name: string; size?: string; type: string }[]; reviews: Review[]; related_products?: Product[] };

export default function ProductPage() {
  const { slug = '' } = useParams();
  const navigate = useNavigate();
  const { addToCart, setLoginOpen, user, ownsProduct } = useStore();
  const { isSaved, toggle, busy: favoriteBusy } = useFavorites();
  const [activeTab, setActiveTab] = useState<'overview' | 'features' | 'reviews' | 'questions'>('overview');
  const [previewOpen, setPreviewOpen] = useState(false);
  const [printOpen, setPrintOpen] = useState(false);
  const { data, isLoading, isError } = useQuery({
    queryKey: ['product', slug],
    queryFn: async (): Promise<ProductDetail> => {
      if (demoMode) {
        const product = demoProducts.find((item) => item.slug === slug);
        if (!product) throw new Error('این کتاب پیدا نشد.');
        return { product, files: [{ id: product.id, name: 'نسخه‌ی دیجیتال کتاب', size: 'PDF', type: 'PDF' }], reviews: demoReviews, related_products: demoProducts.filter((item) => item.id !== product.id && item.category_id === product.category_id).slice(0, 3) };
      }
      const response = await api.get(`/products/${slug}`);
      return response.data.data as ProductDetail;
    },
  });

  if (isLoading) return <div className="container product-loading"><div className="skeleton-cover" /><div><div className="skeleton-line" /><div className="skeleton-line short" /><div className="skeleton-line" /></div></div>;
  if (isError || !data) return <div className="container empty-state product-error"><h2>کتاب پیدا نشد</h2><p>شاید این عنوان از قفسه جابه‌جا شده است.</p><Link className="button button-primary" to="/catalog">برگشت به کتاب‌ها <ArrowLeft size={15} /></Link></div>;

  const { product, files } = data;
  const demoRelated = demoProducts.filter((item) => item.id !== product.id && item.category_id === product.category_id);
  const related = data.related_products?.length ? data.related_products : demoMode
    ? (demoRelated.length ? demoRelated : demoProducts.filter((item) => item.id !== product.id)).slice(0, 3)
    : [];
  const saved = isSaved(product.id);
  const owned = ownsProduct(product.id);

  async function buyNow() {
    if (!demoMode && !user) {
      setLoginOpen(true);
      return;
    }
    await addToCart(product);
    navigate('/cart');
  }

  return (
    <section className="container product-page section-pad">
      <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><Link to="/catalog">کتاب‌ها</Link><span>/</span><b>{product.title}</b></div>
      <div className="product-detail-grid">
        <div className="product-gallery">
          <div className="product-main-image">
            <span className="gallery-label"><Bookmark size={14} /> نسخه‌ی دیجیتال</span>
            {product.cover_url ? <img src={product.cover_url} alt={`جلد ${product.title}`} /> : <div className="cover-placeholder large"><BookOpenIcon /></div>}
            <button className={`gallery-save ${saved ? 'saved' : ''}`} disabled={favoriteBusy} onClick={() => toggle(product)} aria-label="علاقه‌مندی"><Heart size={18} fill={saved ? 'currentColor' : 'none'} /></button>
            <span className="gallery-zoom"><ZoomIn size={16} /> تصویر جلد</span>
          </div>
          <div className="gallery-note"><span className="gallery-note-icon"><ShieldCheck size={17} /></span><span><b>یک خرید، دسترسی همیشگی</b><small>فایل کتاب بعد از خرید در کتابخانه‌ی تو می‌ماند.</small></span><CircleCheck size={18} className="gallery-note-check" /></div>
        </div>
        <div className="product-detail-info">
          <div className="product-kicker"><Link to={`/catalog?category=${product.category?.slug ?? ''}`}>{product.category?.name ?? 'کتاب دیجیتال'}</Link><span>·</span><span>کد محصول {String(product.id).padStart(4, '0')}</span></div>
          <h1>{product.title}</h1>
          <p className="product-detail-desc">{product.description}</p>
          {(product.preview_url || demoMode) && <button className="sample-preview-trigger" onClick={() => setPreviewOpen(true)}><span className="sample-preview-icon"><BookOpen size={18} /></span><span><b>پیش‌نمایش رایگان کتاب</b><small>چند صفحه را ورق بزن، بعد تصمیم بگیر</small></span><ArrowLeft size={16} /></button>}
          <div className="product-rating-row"><div className="stars">{[0, 1, 2, 3, 4].map((star) => <Star key={star} size={16} fill={star < Math.round(product.rating) ? 'currentColor' : 'none'} />)}</div><b>{product.rating.toLocaleString('fa-IR')}</b><button onClick={() => setActiveTab('reviews')}>({product.reviews_count.toLocaleString('fa-IR')} دیدگاه)</button><span className="rating-separator" /> <span className="sales-note">پیشنهاد خواننده‌ها</span></div>
          <div className="detail-divider" />
          <div className="product-price-row"><div className="product-price-main"><b>{formatPrice(product.price)}</b>{product.original_price > product.price && <del>{formatPrice(product.original_price)}</del>}</div>{product.discount_percent > 0 && <span className="detail-discount">{product.discount_percent.toLocaleString('fa-IR')}٪ کمتر</span>}</div>
          {owned ? (
            <div className="owned-panel">
              <span className="owned-panel-icon"><BookOpenCheck size={19} /></span>
              <div>
                <b>این کتاب را قبلاً خریده‌ای</b>
                <small>یک‌بار خرید کافی است؛ فایل‌ها همیشه در کتابخانه‌ات می‌مانند.</small>
              </div>
              <Link className="button button-primary button-small" to="/account?tab=library">رفتن به کتابخانه‌ی من <ArrowLeft size={15} /></Link>
            </div>
          ) : (
            <div className="purchase-actions"><button className="button button-primary button-buy" onClick={() => void buyNow()}><ShoppingBag size={18} /> افزودن به سبد خرید <ArrowLeft size={17} /></button><button className={`button button-icon-only ${saved ? 'is-saved' : ''}`} disabled={favoriteBusy} onClick={() => toggle(product)} aria-label="ذخیره کتاب"><Heart size={18} /></button></div>
          )}
          <div className="product-assurances"><div><span><Download size={16} /></span><b>دریافت فوری</b><small>پس از پرداخت</small></div><div><span><PackageCheck size={16} /></span><b>فایل باکیفیت</b><small>نسخه‌ی اصلی PDF</small></div><div><span><Truck size={16} /></span><b>نسخه‌ی کاغذی هم داری</b><small>سفارش چاپ از چاپخانه‌های همکار</small></div></div>

          <div className="print-teaser">
            <div className="print-teaser-copy">
              <span className="print-teaser-icon"><Printer size={18} /></span>
              <div>
                <b>این کتاب را چاپ‌شده می‌خواهی؟</b>
                <small>{owned ? 'چون نسخه‌ی دیجیتال را داری، ۱۵٪ تخفیف چاپ می‌گیری.' : 'کاغذ، صحافی و تیراژ را انتخاب کن؛ چاپخانه‌ی همکار چاپش می‌کند.'}</small>
              </div>
            </div>
            <button className="button button-outline button-small" onClick={() => setPrintOpen(!printOpen)} aria-expanded={printOpen}>
              {printOpen ? 'بستن فرم' : 'سفارش نسخه‌ی چاپی'} <Printer size={15} />
            </button>
          </div>

          {printOpen && <PrintOrderForm product={product} compact />}
          <div className="detail-tabs"><button className={activeTab === 'overview' ? 'active' : ''} onClick={() => setActiveTab('overview')}>درباره‌ی کتاب</button><button className={activeTab === 'features' ? 'active' : ''} onClick={() => setActiveTab('features')}>مشخصات فایل</button><button className={activeTab === 'reviews' ? 'active' : ''} onClick={() => setActiveTab('reviews')}>دیدگاه‌ها</button><button className={activeTab === 'questions' ? 'active' : ''} onClick={() => setActiveTab('questions')}>پرسش و پاسخ</button></div>
          <div className="detail-tab-panel">
            {activeTab === 'overview' && <><h3>برای کنجکاوها، نه فقط متخصص‌ها</h3><p>{product.description} این نسخه برای مطالعه‌ی راحت روی موبایل، تبلت و رایانه آماده شده است.</p><div className="feature-check-list">{product.key_features.map((feature) => <span key={feature}><Check size={15} /> {feature}</span>)}</div></>}
            {activeTab === 'features' && <div className="file-specs">{files.map((file) => <div key={file.id}><span className="file-spec-icon"><Download size={17} /></span><span><b>{file.name}</b><small>فرمت {file.type} · {file.size ?? 'دریافت فوری'}</small></span><CircleCheck size={17} /></div>)}<p>فایل‌ها پس از پرداخت در کتابخانه‌ی شخصی شما قابل دریافت خواهند بود.</p></div>}
            {activeTab === 'reviews' && <ProductDiscussion key={`comments-${product.id}`} productId={product.id} slug={product.slug} type="comment" />}
            {activeTab === 'questions' && <ProductDiscussion key={`questions-${product.id}`} productId={product.id} slug={product.slug} type="question" />}
          </div>
        </div>
      </div>
      {previewOpen && <div className="sample-preview-backdrop" onMouseDown={(event) => event.target === event.currentTarget && setPreviewOpen(false)}><section className="sample-preview-dialog" role="dialog" aria-modal="true" aria-labelledby="sample-preview-title"><header><div><span className="eyebrow">چند صفحه برای آشنایی</span><h2 id="sample-preview-title">پیش‌نمایش «{product.title}»</h2></div><button className="icon-button" onClick={() => setPreviewOpen(false)} aria-label="بستن پیش‌نمایش"><X size={19} /></button></header>{product.preview_url ? <iframe title={`پیش‌نمایش ${product.title}`} src={product.preview_url} /> : <div className="sample-preview-demo"><span className="eyebrow">نمونه‌ی نمایشی</span><h3>{product.title}</h3><p>{product.description}</p><div className="feature-check-list">{product.key_features.slice(0, 3).map((feature) => <span key={feature}><Check size={15} /> {feature}</span>)}</div><small>در نسخه‌ی اصلی، مدیر فروشگاه نمونه‌ی PDF کتاب را اینجا بارگذاری می‌کند.</small></div>}</section></div>}
      <div className="related-books section-pad"><div className="section-heading section-heading-inline"><div><span className="eyebrow">شاید این‌ها هم به دلت بنشینند</span><h2>کتاب‌های هم‌مسیر</h2></div><Link className="text-link" to="/catalog">دیدن همه <ArrowLeft size={15} /></Link></div><div className="product-grid">{related.map((item, index) => <ProductCard key={item.id} product={item} index={index} />)}</div></div>
    </section>
  );
}

function BookOpenIcon() {
  return <span className="cover-placeholder-label">فایل‌استور<br />کتاب دیجیتال</span>;
}
