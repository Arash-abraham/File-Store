import { useEffect, useMemo, useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { ArrowDownUp, BookOpen, Check, Download, Filter, Percent, RotateCcw, SlidersHorizontal, Star, X } from 'lucide-react';
import { Link, useSearchParams } from 'react-router-dom';
import { api, demoMode } from '../lib/api';
import { demoCategories, demoProducts } from '../data/demo';
import {
  activeFilterCount,
  applyDemoFilters,
  filtersFromParams,
  filtersToApiParams,
  filtersToParams,
  pricePresets,
  ratingPresets,
  sortOptions,
  type CatalogFilters,
} from '../lib/catalog';
import { toman } from '../lib/format';
import type { Category, Product } from '../types';
import ProductCard from '../components/ProductCard';
import SearchBox from '../components/SearchBox';

function unwrap<T>(value: unknown): T[] {
  if (Array.isArray(value)) return value as T[];
  if (value && typeof value === 'object' && 'data' in value) {
    const nested = (value as { data: unknown }).data;
    return Array.isArray(nested) ? nested as T[] : [];
  }
  return [];
}

const ratingLabels: Record<number, string> = {
  4.5: '۴٫۵ به بالا',
  4: '۴ به بالا',
  3: '۳ به بالا',
};

export default function CatalogPage() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [filtersOpen, setFiltersOpen] = useState(() => searchParams.get('advanced') === '1');
  const filters = useMemo(() => filtersFromParams(searchParams), [searchParams]);
  const activeCount = activeFilterCount(filters);
  const { category, q, sort } = filters;

  const { data: categories = demoMode ? demoCategories : [] } = useQuery({
    queryKey: ['categories'],
    staleTime: 5 * 60 * 1000,
    queryFn: async () => demoMode ? demoCategories : unwrap<Category>((await api.get('/categories')).data),
  });
  const { data: fetched = [], isLoading, isError } = useQuery({
    queryKey: ['products', filters],
    queryFn: async () => {
      if (demoMode) return demoProducts;
      const response = await api.get('/products', { params: { ...filtersToApiParams(filters), per_page: 48 } });
      return unwrap<Product>(response.data);
    },
  });

  const products = useMemo(() => demoMode ? applyDemoFilters(fetched, filters) : fetched, [fetched, filters]);
  const priceFiltered = filters.priceMin !== null || filters.priceMax !== null;

  // `?advanced=1` (from the header shortcut) opens the filter drawer on small screens.
  useEffect(() => {
    if (searchParams.get('advanced') === '1') setFiltersOpen(true);
  }, [searchParams]);

  function updateFilters(patch: Partial<CatalogFilters>) {
    const next = filtersToParams({ ...filters, ...patch });
    if (searchParams.get('advanced') === '1') next.set('advanced', '1');
    setSearchParams(next, { replace: true });
  }

  function toggleFilters() {
    const next = !filtersOpen;
    setFiltersOpen(next);
    if (!next && searchParams.get('advanced') === '1') {
      const params = new URLSearchParams(searchParams);
      params.delete('advanced');
      setSearchParams(params, { replace: true });
    }
  }

  function resetFilters() {
    setSearchParams({}, { replace: true });
    setFiltersOpen(false);
  }

  function clearPrice() {
    updateFilters({ priceMin: null, priceMax: null });
  }

  return (
    <section className="catalog-page container section-pad">
      <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>کتاب‌ها</b></div>
      <div className="catalog-hero"><div><span className="eyebrow">قفسه‌ی باز</span><h1>برای فکرهای <em>کنجکاو.</em></h1><p>کتاب‌هایی برای یاد گرفتن، ساختن و کمی متفاوت دیدن.</p></div><div className="catalog-hero-count"><BookOpen size={23} /><span><b>{products.length.toLocaleString('fa-IR')}</b><small>عنوان برای کشف</small></span></div></div>

      <div className="catalog-search-row">
        <SearchBox variant="page" value={q} onChange={(value) => updateFilters({ q: value })} placeholder="جست‌وجوی عنوان یا موضوع…" />
        <button className={`filter-toggle ${filtersOpen ? 'active' : ''}`} onClick={toggleFilters} aria-expanded={filtersOpen}>
          <SlidersHorizontal size={17} /> فیلترها
          {activeCount > 0 && <span className="filter-count">{activeCount.toLocaleString('fa-IR')}</span>}
          <Filter size={15} />
        </button>
        <label className="sort-select"><ArrowDownUp size={16} /><select value={sort} onChange={(event) => updateFilters({ sort: event.target.value })}>{sortOptions.map(([value, label]) => <option key={value} value={value}>{label}</option>)}</select></label>
      </div>

      {activeCount > 0 && (
        <div className="active-filters">
          <span>فیلترهای فعال:</span>
          {q && <button className="active-chip" onClick={() => updateFilters({ q: '' })}>جست‌وجو: {q} <X size={14} /></button>}
          {category && <button className="active-chip" onClick={() => updateFilters({ category: '' })}>{(categories as Category[]).find((item) => item.slug === category)?.name ?? category} <X size={14} /></button>}
          {priceFiltered && <button className="active-chip" onClick={clearPrice}>{priceLabel(filters)} <X size={14} /></button>}
          {filters.minRating !== null && <button className="active-chip" onClick={() => updateFilters({ minRating: null })}>امتیاز {ratingLabels[filters.minRating] ?? filters.minRating} <X size={14} /></button>}
          {filters.onSale && <button className="active-chip" onClick={() => updateFilters({ onSale: false })}>تخفیف‌دار <X size={14} /></button>}
          {filters.hasFiles && <button className="active-chip" onClick={() => updateFilters({ hasFiles: false })}>فایل آماده <X size={14} /></button>}
          <button className="active-filters-reset" onClick={resetFilters}><RotateCcw size={14} /> پاک‌کردن همه</button>
        </div>
      )}

      <div className="catalog-layout">
        <aside className={`catalog-sidebar ${filtersOpen ? 'open' : ''}`}>
          <div className="advanced-filters">
            <div className="filter-block">
              <div className="filter-block-head"><b>دسته‌بندی</b>{category && <button onClick={() => updateFilters({ category: '' })}>پاک‌کردن</button>}</div>
              <button className={`category-filter ${!category ? 'selected' : ''}`} onClick={() => updateFilters({ category: '' })}><span className="filter-dot all-dot" /> همه‌ی کتاب‌ها <span>{products.length.toLocaleString('fa-IR')}</span></button>
              {(categories as Category[]).map((item) => <button key={item.slug} className={`category-filter ${category === item.slug ? 'selected' : ''}`} onClick={() => updateFilters({ category: item.slug })}><span className={`filter-dot dot-${item.color ?? 'gray'}`} /> {item.name}<span>{item.products_count.toLocaleString('fa-IR')}</span></button>)}
            </div>

            <div className="filter-block">
              <div className="filter-block-head"><b>محدوده‌ی قیمت</b>{priceFiltered && <button onClick={clearPrice}>پاک‌کردن</button>}</div>
              <div className="price-range">
                <label>از (تومان)
                  <input
                    type="number" min="0" step="10000" inputMode="numeric"
                    value={filters.priceMin ?? ''}
                    placeholder="۰"
                    onChange={(event) => updateFilters({ priceMin: event.target.value ? Number(event.target.value) : null })}
                  />
                </label>
                <label>تا (تومان)
                  <input
                    type="number" min="0" step="10000" inputMode="numeric"
                    value={filters.priceMax ?? ''}
                    placeholder="بی‌نهایت"
                    onChange={(event) => updateFilters({ priceMax: event.target.value ? Number(event.target.value) : null })}
                  />
                </label>
              </div>
              <div className="quick-chips">
                {pricePresets.map((preset) => {
                  const active = filters.priceMin === preset.min && filters.priceMax === preset.max;
                  return <button key={preset.id} className={`chip ${active ? 'is-active' : ''}`} onClick={() => updateFilters(active ? { priceMin: null, priceMax: null } : { priceMin: preset.min, priceMax: preset.max })}>{preset.label}</button>;
                })}
              </div>
            </div>

            <div className="filter-block">
              <div className="filter-block-head"><b>امتیاز خواننده‌ها</b>{filters.minRating !== null && <button onClick={() => updateFilters({ minRating: null })}>پاک‌کردن</button>}</div>
              <div className="quick-chips">
                {ratingPresets.map((rating) => <button key={rating} className={`chip ${filters.minRating === rating ? 'is-active' : ''}`} onClick={() => updateFilters({ minRating: filters.minRating === rating ? null : rating })}><Star size={13} fill="currentColor" /> {ratingLabels[rating]}</button>)}
              </div>
            </div>

            <div className="filter-block">
              <div className="filter-block-head"><b>ویژگی‌های کتاب</b></div>
              <button className="toggle-row" onClick={() => updateFilters({ onSale: !filters.onSale })} aria-pressed={filters.onSale}>
                <span><b><Percent size={14} /> تخفیف‌دار</b><small>فقط کتاب‌هایی که قیمت‌شان کاهش یافته</small></span>
                <span className={`switch ${filters.onSale ? 'is-on' : ''}`}><i /></span>
              </button>
              <button className="toggle-row" onClick={() => updateFilters({ hasFiles: !filters.hasFiles })} aria-pressed={filters.hasFiles}>
                <span><b><Download size={14} /> فایل آماده‌ی دانلود</b><small>فایل کتاب پیش از خرید بارگذاری شده است</small></span>
                <span className={`switch ${filters.hasFiles ? 'is-on' : ''}`}><i /></span>
              </button>
            </div>

            <div className="sidebar-promo"><span>یک پیشنهاد کوچک</span><b>با کد WELCOME10<br />اولین کتاب را ارزان‌تر بخر.</b><button onClick={resetFilters}>شروع گشت‌وگذار <ArrowDownUp size={14} /></button></div>
          </div>
        </aside>

        <div className="catalog-content">
          <div className="catalog-result-line">
            <span>{q ? <>نتیجه برای <b>«{q}»</b></> : category ? (categories as Category[]).find((item) => item.slug === category)?.name : 'همه‌ی کتاب‌ها'}</span>
            <span>{products.length.toLocaleString('fa-IR')} عنوان</span>
          </div>
          {isLoading ? <div className="product-grid">{[0, 1, 2, 3].map((item) => <div className="skeleton-card" key={item} />)}</div> : isError ? <div className="empty-state"><BookOpen size={35} /><h3>کتاب‌ها بارگذاری نشدند</h3><p>ارتباط با API را بررسی کن و دوباره تلاش کن.</p></div> : products.length > 0 ? <div className="product-grid">{products.map((product, index) => <ProductCard key={product.id} product={product} index={index} />)}</div> : <div className="empty-state"><span className="empty-state-icon"><Filter size={27} /></span><h3>با این فیلترها چیزی پیدا نشد</h3><p>محدوده‌ی قیمت را بازتر کن یا فیلترها را پاک کن.</p><button className="button button-primary" onClick={resetFilters}><RotateCcw size={16} /> نمایش همه‌ی کتاب‌ها</button></div>}
          {!isLoading && products.length > 0 && <div className="catalog-bottom-note"><span className="catalog-note-icon"><Check size={17} /></span> کتاب خوب عجله‌ای نیست؛ وقت بگذار و عنوان بعدی‌ات را پیدا کن.</div>}
        </div>
      </div>
    </section>
  );
}

function priceLabel(filters: CatalogFilters) {
  const { priceMin, priceMax } = filters;
  if (priceMin !== null && priceMax !== null) return `${toman(priceMin)} تا ${toman(priceMax)} تومان`;
  if (priceMin !== null) return `از ${toman(priceMin)} تومان`;
  if (priceMax !== null) return `تا ${toman(priceMax)} تومان`;
  return 'محدوده‌ی قیمت';
}
