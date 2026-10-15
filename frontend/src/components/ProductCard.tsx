import { useState } from 'react';
import { ArrowLeft, BookOpen, Heart, ShoppingBag, Star } from 'lucide-react';
import { Link } from 'react-router-dom';
import type { Product } from '../types';
import { formatPrice } from '../lib/format';
import { useStore } from '../StoreContext';

export default function ProductCard({ product, index = 0 }: { product: Product; index?: number }) {
  const { addToCart, setNotice } = useStore();
  const [saved, setSaved] = useState(false);
  return (
    <article className="product-card" style={{ animationDelay: `${Math.min(index * 55, 300)}ms` }}>
      <div className="product-cover-wrap">
        <div className="product-cover-backdrop" />
        {product.discount_percent > 0 && <span className="discount-badge">{product.discount_percent.toLocaleString('fa-IR')}٪ تخفیف</span>}
        <Link to={`/book/${product.slug}`} className="product-cover-link" aria-label={`دیدن ${product.title}`}>
          {product.cover_url ? (
            <img src={product.cover_url} alt={`جلد ${product.title}`} className="product-cover" loading="lazy" />
          ) : (
            <div className="cover-placeholder"><BookOpen size={35} /><span>فایل‌استور</span></div>
          )}
        </Link>
        <button className={`cover-heart ${saved ? 'saved' : ''}`} aria-label={saved ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها'} onClick={() => { setSaved(!saved); setNotice(saved ? 'از علاقه‌مندی‌ها حذف شد.' : 'به علاقه‌مندی‌ها اضافه شد.'); }}><Heart size={17} fill={saved ? 'currentColor' : 'none'} /></button>
        <Link className="cover-quick-view" to={`/book/${product.slug}`}>مشاهده‌ی کتاب <ArrowLeft size={13} /></Link>
      </div>
      <div className="product-card-info">
        <div className="product-card-meta"><span>{product.category?.name ?? 'کتاب دیجیتال'}</span><span className="rating"><Star size={13} fill="currentColor" /> {product.rating.toLocaleString('fa-IR')}</span></div>
        <Link to={`/book/${product.slug}`} className="product-title">{product.title}</Link>
        <p className="product-card-description">{product.description}</p>
        <div className="product-card-bottom">
          <div className="price-stack"><b>{formatPrice(product.price)}</b>{product.original_price > product.price && <del>{formatPrice(product.original_price)}</del>}</div>
          <button className="add-cart-button" onClick={() => void addToCart(product)} aria-label={`افزودن ${product.title} به سبد`}><ShoppingBag size={17} /></button>
        </div>
      </div>
    </article>
  );
}
