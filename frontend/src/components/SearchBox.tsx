import { useEffect, useMemo, useRef, useState, type FormEvent, type KeyboardEvent } from 'react';
import { useQuery } from '@tanstack/react-query';
import { ArrowLeft, BookOpen, CornerDownLeft, Loader2, Search, SlidersHorizontal, Sparkles, X } from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import { api, demoMode } from '../lib/api';
import { suggestDemoProducts } from '../lib/catalog';
import { formatPrice } from '../lib/format';
import type { Category, SearchSuggestions } from '../types';

type SearchBoxProps = {
  /** `header` navigates to the catalog on submit, `page` edits the catalog query live. */
  variant: 'header' | 'page';
  /** Controlled value; only used by the `page` variant. */
  value?: string;
  onChange?: (value: string) => void;
  onNavigate?: () => void;
  placeholder?: string;
};

type SuggestionRow =
  | { kind: 'product'; id: number; slug: string; title: string; category?: string | null; cover?: string | null; price: number }
  | { kind: 'category'; slug: string; title: string; count: number };

export default function SearchBox({ variant, value, onChange, onNavigate, placeholder }: SearchBoxProps) {
  const navigate = useNavigate();
  const containerRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLInputElement>(null);
  const [draft, setDraft] = useState(value ?? '');
  const [focused, setFocused] = useState(false);
  const [debounced, setDebounced] = useState('');
  const [activeIndex, setActiveIndex] = useState(-1);

  const term = variant === 'page' ? value ?? '' : draft;

  useEffect(() => {
    if (variant === 'page') setDraft(value ?? '');
  }, [value, variant]);

  useEffect(() => {
    const trimmed = term.trim();
    if (trimmed.length < 2) {
      setDebounced('');
      return;
    }
    const timer = window.setTimeout(() => setDebounced(trimmed), 220);
    return () => window.clearTimeout(timer);
  }, [term]);

  useEffect(() => {
    setActiveIndex(-1);
  }, [debounced, focused]);

  const { data: suggestions, isFetching } = useQuery<SearchSuggestions>({
    queryKey: ['search-suggest', debounced],
    enabled: debounced.length >= 2,
    staleTime: 60_000,
    queryFn: async () => {
      if (demoMode) return suggestDemoProducts(debounced, 5);
      const response = await api.get('/search/suggest', { params: { q: debounced, limit: 5 } });
      return response.data.data as SearchSuggestions;
    },
  });

  useEffect(() => {
    if (!focused) return;
    function handlePointerDown(event: MouseEvent) {
      if (!containerRef.current?.contains(event.target as Node)) setFocused(false);
    }
    document.addEventListener('mousedown', handlePointerDown);
    return () => document.removeEventListener('mousedown', handlePointerDown);
  }, [focused]);

  const rows = useMemo<SuggestionRow[]>(() => {
    if (!suggestions) return [];
    return [
      ...suggestions.products.map<SuggestionRow>((product) => ({
        kind: 'product',
        id: product.id,
        slug: product.slug,
        title: product.title,
        category: product.category?.name ?? null,
        cover: product.cover_url,
        price: product.price,
      })),
      ...suggestions.categories.map<SuggestionRow>((category: Category) => ({
        kind: 'category',
        slug: category.slug,
        title: category.name,
        count: category.products_count,
      })),
    ];
  }, [suggestions]);

  const showPanel = focused && (debounced.length >= 2 || (term.trim().length >= 2 && isFetching));
  const hasResults = rows.length > 0;

  function close() {
    setFocused(false);
    setActiveIndex(-1);
  }

  function commit(termToUse = term) {
    const trimmed = termToUse.trim();
    navigate(`/catalog${trimmed ? `?q=${encodeURIComponent(trimmed)}` : ''}`);
    close();
    onNavigate?.();
  }

  function openRow(row: SuggestionRow) {
    if (row.kind === 'product') navigate(`/book/${row.slug}`);
    else navigate(`/catalog?category=${row.slug}`);
    close();
    onNavigate?.();
  }

  function openAdvanced(termToUse = term) {
    const trimmed = termToUse.trim();
    const params = new URLSearchParams({ advanced: '1' });
    if (trimmed) params.set('q', trimmed);
    navigate(`/catalog?${params.toString()}`);
    close();
    onNavigate?.();
  }

  function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (activeIndex >= 0 && rows[activeIndex]) openRow(rows[activeIndex]);
    else commit();
  }

  function handleKeyDown(event: KeyboardEvent<HTMLInputElement>) {
    if (event.key === 'Escape') {
      close();
      inputRef.current?.blur();
      return;
    }
    if (!showPanel || !hasResults) return;
    if (event.key === 'ArrowDown') {
      event.preventDefault();
      setActiveIndex((index) => (index + 1) % rows.length);
    } else if (event.key === 'ArrowUp') {
      event.preventDefault();
      setActiveIndex((index) => (index <= 0 ? rows.length - 1 : index - 1));
    }
  }

  const activeRow = activeIndex >= 0 ? rows[activeIndex] : undefined;
  const activeOptionId = activeRow ? `search-option-${activeIndex}` : undefined;

  return (
    <div className={`search-box search-box-${variant}`} ref={containerRef}>
      <form className={variant === 'header' ? 'header-search' : 'catalog-search'} onSubmit={submit} role="search">
        <Search size={variant === 'header' ? 18 : 19} />
        <input
          ref={inputRef}
          value={term}
          onFocus={() => setFocused(true)}
          onChange={(event) => {
            setFocused(true);
            if (variant === 'page') {
              setDraft(event.target.value);
              onChange?.(event.target.value);
            } else {
              setDraft(event.target.value);
            }
          }}
          onKeyDown={handleKeyDown}
          placeholder={placeholder ?? 'دنبال چه چیزی می‌گردی؟'}
          aria-label="جست‌وجوی کتاب"
          aria-expanded={showPanel}
          aria-controls="search-suggestions"
          aria-autocomplete="list"
          aria-activedescendant={activeOptionId}
          autoComplete="off"
        />
        {isFetching && <Loader2 size={16} className="search-spinner" aria-hidden />}
        {!!term && !isFetching && (
          <button type="button" className="search-clear" aria-label="پاک کردن جست‌وجو" onClick={() => {
            if (variant === 'page') {
              onChange?.('');
              inputRef.current?.focus();
            } else {
              setDraft('');
              inputRef.current?.focus();
            }
          }}>
            <X size={16} />
          </button>
        )}
        {variant === 'header' && <kbd>↵</kbd>}
      </form>

      {showPanel && (
        <div className="search-suggest" id="search-suggestions" role="listbox" aria-label="پیشنهادهای جست‌وجو">
          {hasResults ? (
            <>
              <div className="search-suggest-head">
                <span><Sparkles size={14} /> پیشنهادهای سریع برای «{debounced}»</span>
                <span>{rows.length.toLocaleString('fa-IR')} مورد</span>
              </div>
              <div className="search-suggest-list">
                {rows.map((row, index) => row.kind === 'product' ? (
                  <button
                    type="button"
                    key={`product-${row.id}`}
                    id={`search-option-${index}`}
                    role="option"
                    aria-selected={index === activeIndex}
                    className={`search-suggest-item ${index === activeIndex ? 'is-active' : ''}`}
                    onMouseEnter={() => setActiveIndex(index)}
                    onClick={() => openRow(row)}
                  >
                    <span className="search-suggest-cover">
                      {row.cover ? <img src={row.cover} alt="" /> : <BookOpen size={17} />}
                    </span>
                    <span className="search-suggest-copy">
                      <b>{row.title}</b>
                      <small>{row.category ?? 'کتاب دیجیتال'}</small>
                    </span>
                    <span className="search-suggest-price">{formatPrice(row.price)}</span>
                  </button>
                ) : (
                  <button
                    type="button"
                    key={`category-${row.slug}`}
                    id={`search-option-${index}`}
                    role="option"
                    aria-selected={index === activeIndex}
                    className={`search-suggest-item is-category ${index === activeIndex ? 'is-active' : ''}`}
                    onMouseEnter={() => setActiveIndex(index)}
                    onClick={() => openRow(row)}
                  >
                    <span className="search-suggest-cover"><Sparkles size={17} /></span>
                    <span className="search-suggest-copy">
                      <b>{row.title}</b>
                      <small>دسته‌بندی · {row.count.toLocaleString('fa-IR')} عنوان</small>
                    </span>
                    <ArrowLeft size={16} />
                  </button>
                ))}
              </div>
            </>
          ) : (
            !isFetching && (
              <div className="search-empty">
                چیزی با «{debounced}» پیدا نشد؛ با فیلترهای پیشرفته دقیق‌تر بگرد.
              </div>
            )
          )}

          <div className="search-suggest-foot">
            <button type="button" onClick={() => openAdvanced()}>
              <SlidersHorizontal size={15} /> جست‌وجوی پیشرفته
            </button>
            <button type="button" onClick={() => commit()}>
              <CornerDownLeft size={15} /> دیدن همه‌ی نتیجه‌ها
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
