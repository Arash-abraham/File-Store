import { demoCategories, demoProducts } from '../data/demo';
import type { Category, Product, SearchSuggestions } from '../types';

export type CatalogFilters = {
  q: string;
  category: string;
  sort: string;
  priceMin: number | null;
  priceMax: number | null;
  minRating: number | null;
  onSale: boolean;
  hasFiles: boolean;
};

export const defaultFilters: CatalogFilters = {
  q: '',
  category: '',
  sort: 'popular',
  priceMin: null,
  priceMax: null,
  minRating: null,
  onSale: false,
  hasFiles: false,
};

export const sortOptions: [string, string][] = [
  ['popular', 'پیشنهاد فایل‌استور'],
  ['newest', 'تازه‌ترین'],
  ['rating', 'بیشترین امتیاز'],
  ['discount', 'بیشترین تخفیف'],
  ['price_asc', 'ارزان‌ترین'],
  ['price_desc', 'گران‌ترین'],
];

export const pricePresets: { id: string; label: string; min: number | null; max: number | null }[] = [
  { id: 'under-400', label: 'زیر ۴۰۰ هزار', min: null, max: 400000 },
  { id: '400-800', label: '۴۰۰ تا ۸۰۰ هزار', min: 400000, max: 800000 },
  { id: 'over-800', label: 'بالای ۸۰۰ هزار', min: 800000, max: null },
];

export const ratingPresets = [4.5, 4, 3];

const toNumber = (value: string | null): number | null => {
  if (!value) return null;
  const parsed = Number(value.replace(/[^\d.]/g, ''));
  return Number.isFinite(parsed) ? parsed : null;
};

export function filtersFromParams(params: URLSearchParams): CatalogFilters {
  return {
    q: params.get('q') ?? '',
    category: params.get('category') ?? '',
    sort: params.get('sort') ?? 'popular',
    priceMin: toNumber(params.get('price_min')),
    priceMax: toNumber(params.get('price_max')),
    minRating: toNumber(params.get('min_rating')),
    onSale: params.get('on_sale') === '1',
    hasFiles: params.get('has_files') === '1',
  };
}

export function filtersToParams(filters: CatalogFilters): URLSearchParams {
  const params = new URLSearchParams();
  if (filters.q) params.set('q', filters.q);
  if (filters.category) params.set('category', filters.category);
  if (filters.sort && filters.sort !== 'popular') params.set('sort', filters.sort);
  if (filters.priceMin !== null) params.set('price_min', String(filters.priceMin));
  if (filters.priceMax !== null) params.set('price_max', String(filters.priceMax));
  if (filters.minRating !== null) params.set('min_rating', String(filters.minRating));
  if (filters.onSale) params.set('on_sale', '1');
  if (filters.hasFiles) params.set('has_files', '1');
  return params;
}

/** Query-string payload for `GET /products`. */
export function filtersToApiParams(filters: CatalogFilters): Record<string, string | number> {
  const params: Record<string, string | number> = { sort: filters.sort };
  if (filters.q) params.q = filters.q;
  if (filters.category) params.category = filters.category;
  if (filters.priceMin !== null) params.price_min = filters.priceMin;
  if (filters.priceMax !== null) params.price_max = filters.priceMax;
  if (filters.minRating !== null) params.min_rating = filters.minRating;
  if (filters.onSale) params.on_sale = 1;
  if (filters.hasFiles) params.has_files = 1;
  return params;
}

export function activeFilterCount(filters: CatalogFilters): number {
  return [
    filters.q,
    filters.category,
    filters.priceMin !== null || filters.priceMax !== null,
    filters.minRating !== null,
    filters.onSale,
    filters.hasFiles,
  ].filter(Boolean).length;
}

/**
 * Demo mode has no API, so the same filters run in the browser.
 */
export function applyDemoFilters(products: Product[], filters: CatalogFilters): Product[] {
  const term = filters.q.trim().toLocaleLowerCase('fa-IR');
  const filtered = products.filter((product) => {
    const haystack = `${product.title} ${product.description} ${product.category?.name ?? ''}`.toLocaleLowerCase('fa-IR');
    if (term && !haystack.includes(term)) return false;
    if (filters.category && product.category?.slug !== filters.category) return false;
    if (filters.priceMin !== null && product.price < filters.priceMin) return false;
    if (filters.priceMax !== null && product.price > filters.priceMax) return false;
    if (filters.minRating !== null && product.rating < filters.minRating) return false;
    if (filters.onSale && !(product.discount_percent > 0)) return false;
    if (filters.hasFiles && !(product.files_count ?? 0)) return false;
    return true;
  });

  const sorted = [...filtered];
  switch (filters.sort) {
    case 'price_asc': sorted.sort((a, b) => a.price - b.price); break;
    case 'price_desc': sorted.sort((a, b) => b.price - a.price); break;
    case 'rating': sorted.sort((a, b) => b.rating - a.rating); break;
    case 'discount': sorted.sort((a, b) => b.discount_percent - a.discount_percent); break;
    case 'newest': sorted.sort((a, b) => b.id - a.id); break;
    default: sorted.sort((a, b) => b.reviews_count - a.reviews_count);
  }
  return sorted;
}

export function suggestDemoProducts(term: string, limit = 5): SearchSuggestions {
  const normalized = term.trim().toLocaleLowerCase('fa-IR');
  if (normalized.length < 2) return { query: term, products: [], categories: [] };
  const match = (value: string) => value.toLocaleLowerCase('fa-IR').includes(normalized);
  return {
    query: term,
    products: demoProducts
      .filter((product) => match(product.title) || match(product.description))
      .slice(0, limit),
    categories: (demoCategories as Category[]).filter((category) => match(category.name)).slice(0, 4),
  };
}
