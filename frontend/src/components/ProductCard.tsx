import { ArrowLeft, BookOpen, BookOpenCheck, Heart, ShoppingBag, Star } from 'lucide-react';
import { Link } from 'react-router-dom';
import type { Product } from '../types';
import { formatPrice } from '../lib/format';
import { useStore } from '../StoreContext';
import { useFavorites } from '../lib/useFavorites';

export default function ProductCard({ product, index = 0 }: { product: Product; index?: number }) {
  const { addToCart, ownsProduct } = useStore();
  const { isSaved, toggle, busy } = useFavorites();
  const saved = isSaved(product.id);
  const owned = ownsProduct(product.id);
  return (
    <article className="product-card" style={{ animationDelay: `${Math.min(index * 55, 300)}ms` }}>
      <div className="product-cover-wrap">
        <div className="product-cover-backdrop" />
        {owned
          ? <span className="owned-badge"><BookOpenCheck size={14} /> در کتابخانه‌ی تو</span>
          : product.discount_percent > 0 && <span className="discount-badge">{product.discount_percent.toLocaleString('fa-IR')}٪ تخفیف</span>}
        <Link to={`/book/${product.slug}`} className="product-cover-link" aria-label={`دیدن ${product.title}`}>
          {product.cover_url ? (
            <img src={product.cover_url} alt={`جلد ${product.title}`} className="product-cover" loading="lazy" />
          ) : (
            <div className="cover-placeholder"><BookOpen size={35} /><span>فایل‌استور</span></div>
          )}
        </Link>
        <button className={`cover-heart ${saved ? 'saved' : ''}`} disabled={busy} aria-label={saved ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها'} onClick={() => toggle(product)}><Heart size={17} fill={saved ? 'currentColor' : 'none'} /></button>
        <Link className="cover-quick-view" to={`/book/${product.slug}`}>مشاهده‌ی کتاب <ArrowLeft size={13} /></Link>
      </div>
      <div className="product-card-info">
        <div className="product-card-meta"><span>{product.category?.name ?? 'کتاب دیجیتال'}</span><span className="rating"><Star size={13} fill="currentColor" /> {product.rating.toLocaleString('fa-IR')}</span></div>
        <Link to={`/book/${product.slug}`} className="product-title">{product.title}</Link>
        <p className="product-card-description">{product.description}</p>
        <div className="product-card-bottom">
          {owned
            ? <div className="owned-note"><BookOpenCheck size={15} /> خریداری‌شده — نیازی به خرید دوباره نیست</div>
            : <div className="price-stack"><b>{formatPrice(product.price)}</b>{product.original_price > product.price && <del>{formatPrice(product.original_price)}</del>}</div>}
          {owned
            ? <Link className="add-cart-button is-owned" to="/account?tab=library" aria-label={`${product.title} در کتابخانه‌ی من`} title="رفتن به کتابخانه"><BookOpenCheck size={17} /></Link>
            : <button className="add-cart-button" onClick={() => void addToCart(product)} aria-label={`افزودن ${product.title} به سبد`}><ShoppingBag size={17} /></button>}
        </div>
      </div>
    </article>
  );
}
