import { useMemo, useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { ArrowDownUp, BookOpen, Filter, Search, SlidersHorizontal, X } from 'lucide-react';
import { Link, useSearchParams } from 'react-router-dom';
import { api, demoMode } from '../lib/api';
import { demoCategories, demoProducts } from '../data/demo';
import type { Category, Product } from '../types';
import ProductCard from '../components/ProductCard';

function unwrap<T>(value: unknown): T[] {
  if (Array.isArray(value)) return value as T[];
  if (value && typeof value === 'object' && 'data' in value) {
    const nested = (value as { data: unknown }).data;
    return Array.isArray(nested) ? nested as T[] : [];
  }
  return [];
}

export default function CatalogPage() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [filtersOpen, setFiltersOpen] = useState(false);
  const query = searchParams.get('q') ?? '';
  const category = searchParams.get('category') ?? '';
  const sort = searchParams.get('sort') ?? 'popular';
  const { data: categories = demoMode ? demoCategories : [] } = useQuery({
    queryKey: ['categories'],
    queryFn: async () => demoMode ? demoCategories : unwrap<Category>((await api.get('/categories')).data),
  });
  const { data: products = demoMode ? demoProducts : [], isLoading, isError } = useQuery({
    queryKey: ['products', query, category, sort],
    queryFn: async () => {
      if (demoMode) return demoProducts;
      const response = await api.get('/products', { params: { q: query, category, sort, per_page: 48 } });
      return unwrap<Product>(response.data);
    },
  });
  const filtered = useMemo(() => {
    const normalized = query.trim().toLocaleLowerCase('fa-IR');
    return products.filter((product) => {
      const matchesQuery = !normalized || `${product.title} ${product.description}`.toLocaleLowerCase('fa-IR').includes(normalized);
      const matchesCategory = !category || product.category?.slug === category;
      return matchesQuery && matchesCategory;
    }).sort((a, b) => sort === 'price_asc' ? a.price - b.price : sort === 'price_desc' ? b.price - a.price : 0);
  }, [products, query, category, sort]);

  function updateParam(key: string, value: string) {
    const next = new URLSearchParams(searchParams);
    if (value) next.set(key, value); else next.delete(key);
    setSearchParams(next);
  }

  return (
    <section className="catalog-page container section-pad">
      <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>کتاب‌ها</b></div>
      <div className="catalog-hero"><div><span className="eyebrow">قفسه‌ی باز</span><h1>برای فکرهای <em>کنجکاو.</em></h1><p>کتاب‌هایی برای یاد گرفتن، ساختن و کمی متفاوت دیدن.</p></div><div className="catalog-hero-count"><BookOpen size={23} /><span><b>{products.length.toLocaleString('fa-IR')}</b><small>عنوان برای کشف</small></span></div></div>
      <div className="catalog-search-row"><label className="catalog-search"><Search size={18} /><input value={query} onChange={(event) => updateParam('q', event.target.value)} placeholder="جست‌وجوی عنوان یا موضوع…" /><button onClick={() => updateParam('q', '')} aria-label="پاک کردن جست‌وجو"><X size={16} /></button></label><button className={`filter-toggle ${filtersOpen ? 'active' : ''}`} onClick={() => setFiltersOpen(!filtersOpen)}><SlidersHorizontal size={17} /> فیلترها <Filter size={15} /></button><label className="sort-select"><ArrowDownUp size={16} /><select value={sort} onChange={(event) => updateParam('sort', event.target.value)}><option value="popular">پیشنهاد فایل‌استور</option><option value="newest">تازه‌ترین</option><option value="price_asc">ارزان‌ترین</option><option value="price_desc">گران‌ترین</option></select></label></div>
      <div className="catalog-layout">
        <aside className={`catalog-sidebar ${filtersOpen ? 'open' : ''}`}>
          <div className="sidebar-title"><b>دسته‌بندی‌ها</b><button onClick={() => updateParam('category', '')}>پاک‌کردن</button></div>
          <button className={`category-filter ${!category ? 'selected' : ''}`} onClick={() => updateParam('category', '')}><span className="filter-dot all-dot" /> همه‌ی کتاب‌ها <span>{products.length.toLocaleString('fa-IR')}</span></button>
          {categories.map((item: Category) => <button key={item.slug} className={`category-filter ${category === item.slug ? 'selected' : ''}`} onClick={() => updateParam('category', item.slug)}><span className={`filter-dot dot-${item.color ?? 'gray'}`} /> {item.name}<span>{item.products_count.toLocaleString('fa-IR')}</span></button>)}
          <div className="sidebar-promo"><span>یک پیشنهاد کوچک</span><b>با کد WELCOME10<br />اولین کتاب را ارزان‌تر بخر.</b><button onClick={() => updateParam('q', '')}>شروع گشت‌وگذار <ArrowDownUp size={14} /></button></div>
        </aside>
        <div className="catalog-content">
          <div className="catalog-result-line"><span>{query ? <>نتیجه برای <b>«{query}»</b></> : category ? categories.find((item) => item.slug === category)?.name : 'همه‌ی کتاب‌ها'}</span><span>{filtered.length.toLocaleString('fa-IR')} عنوان</span></div>
          {isLoading ? <div className="product-grid">{[0, 1, 2, 3].map((item) => <div className="skeleton-card" key={item} />)}</div> : isError ? <div className="empty-state"><BookOpen size={35} /><h3>کتاب‌ها بارگذاری نشدند</h3><p>ارتباط با API را بررسی کن و دوباره تلاش کن.</p></div> : filtered.length > 0 ? <div className="product-grid">{filtered.map((product, index) => <ProductCard key={product.id} product={product} index={index} />)}</div> : <div className="empty-state"><span className="empty-state-icon"><Search size={27} /></span><h3>این قفسه فعلاً خالی است</h3><p>با یک موضوع دیگر جست‌وجو کن یا فیلترها را پاک کن.</p><button className="button button-primary" onClick={() => { setSearchParams({}); setFiltersOpen(false); }}>نمایش همه‌ی کتاب‌ها</button></div>}
          {!isLoading && filtered.length > 0 && <div className="catalog-bottom-note"><span className="catalog-note-icon"><BookOpen size={17} /></span> کتاب خوب عجله‌ای نیست؛ وقت بگذار و عنوان بعدی‌ات را پیدا کن.</div>}
        </div>
      </div>
    </section>
  );
}
