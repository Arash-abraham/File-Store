import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { BookOpen, ChevronDown, Compass, Percent, Star } from 'lucide-react';
import { Link, NavLink } from 'react-router-dom';
import { api, demoMode } from '../lib/api';
import { demoCategories } from '../data/demo';
import type { Category } from '../types';

function unwrap<T>(value: unknown): T[] {
  if (Array.isArray(value)) return value as T[];
  if (value && typeof value === 'object' && 'data' in value) {
    const nested = (value as { data: unknown }).data;
    return Array.isArray(nested) ? nested as T[] : [];
  }
  return [];
}

const categoryIcons = [BookOpen, Compass, Star, Percent];

export default function MainNav({ menuOpen, onNavigate }: { menuOpen: boolean; onNavigate: () => void }) {
  const [panelOpen, setPanelOpen] = useState(false);
  const { data: categories = [] } = useQuery<Category[]>({
    queryKey: ['categories'],
    staleTime: 5 * 60 * 1000,
    queryFn: async () => demoMode ? demoCategories : unwrap<Category>((await api.get('/categories')).data),
  });

  const items = categories.slice(0, 6);

  return (
    <nav className={`main-nav ${menuOpen ? 'is-open' : ''}`} onMouseLeave={() => setPanelOpen(false)} aria-label="ناوبری اصلی" style={{
      borderRadius : '17px'
    }}>
      <div className="nav-links">
        <NavLink to="/" end className="nav-link" onClick={onNavigate}>خانه</NavLink>
        <NavLink to="/catalog" className="nav-link" onClick={onNavigate}>کتاب‌ها</NavLink>

        <div
          className={`nav-drop ${panelOpen ? 'open' : ''}`}
          onMouseEnter={() => setPanelOpen(true)}
        >
          <button
            type="button"
            className="nav-link nav-drop-trigger"
            aria-expanded={panelOpen}
            aria-haspopup="true"
            onClick={() => setPanelOpen((open) => !open)}
          >
            دسته‌بندی‌ها <ChevronDown size={15} />
          </button>
          <div className="nav-panel">
            <div className="nav-panel-head">
              <b>دسته‌بندی‌های فروشگاه</b>
              <Link to="/catalog" onClick={() => { setPanelOpen(false); onNavigate(); }}>همه‌ی کتاب‌ها</Link>
            </div>
            <div className="nav-panel-grid">
              {items.length === 0 && <span className="nav-panel-empty">دسته‌بندی‌ها به‌زودی منتشر می‌شوند.</span>}
              {items.map((category, index) => {
                const Icon = categoryIcons[index % categoryIcons.length];
                return (
                  <Link
                    className="nav-panel-item"
                    key={category.slug}
                    to={`/catalog?category=${category.slug}`}
                    onClick={() => { setPanelOpen(false); onNavigate(); }}
                  >
                    <span className={`nav-panel-icon tone-${category.color ?? 'green'}`}><Icon size={17} /></span>
                    <span className="nav-panel-item-copy">
                      <b>{category.name}</b>
                      <small>{category.products_count.toLocaleString('fa-IR')} عنوان</small>
                    </span>
                  </Link>
                );
              })}
            </div>
            <div className="nav-panel-foot">
              <Link className="nav-panel-chip" to="/catalog?on_sale=1" onClick={() => { setPanelOpen(false); onNavigate(); }}>
                <Percent size={14} /> تخفیف‌دارها
              </Link>
              <Link className="nav-panel-chip" to="/catalog?min_rating=4.5&sort=rating" onClick={() => { setPanelOpen(false); onNavigate(); }}>
                <Star size={14} /> امتیاز ۴٫۵ به بالا
              </Link>
            </div>
          </div>
        </div>

        <NavLink to="/print" className="nav-link" onClick={onNavigate}>چاپ کتاب</NavLink>
        <NavLink to="/about" className="nav-link" onClick={onNavigate}>داستان ما</NavLink>
        <NavLink to="/faq" className="nav-link" onClick={onNavigate}>راهنما</NavLink>
      </div>
    </nav>
  );
}
