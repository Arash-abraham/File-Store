import { useMemo, type ReactNode } from 'react';
import { ArrowLeft, BookOpen, CircleDollarSign, CreditCard, Headset, MessageSquareText, Percent, TrendingDown, TrendingUp, Users, Wallet } from 'lucide-react';
import { formatDate, formatPrice } from '../../lib/format';

/* ------------------------------------------------------------------ types */

export type StatusCount = { status: string; count: number };
export type DayValue = { day: string; amount: number };
export type DayCount = { day: string; count: number };

export type AdminDashboard = {
  stats: { products: number; users: number; orders: number; revenue: number; pending_orders: number; open_tickets: number; pending_reviews: number };
  sales: {
    revenue_total: number; revenue_today: number; revenue_7d: number; revenue_30d: number; revenue_growth_30d: number | null;
    paid_orders: number; average_order_value: number; conversion_rate: number; refunded_orders: number; failed_orders: number; pending_orders: number;
    orders_by_status: StatusCount[]; daily: DayValue[]; daily_orders: DayCount[];
  };
  users: { total: number; admins: number; verified: number; new_7d: number; new_30d: number; buyers: number; daily_new: DayCount[] };
  catalog: {
    total: number; active: number; draft: number; inactive: number; unavailable: number; files: number; categories: number; tags: number;
    top_products: { id: number; title: string; revenue: number; sales: number }[];
    revenue_by_category: { name: string; revenue: number }[];
  };
  coupons: { total: number; active: number; orders_with_coupon: number; discount_given: number };
  community: {
    comments_approved: number; comments_pending: number; questions_approved: number; questions_pending: number; replies_total: number; replies_pending: number;
    rejected: number; likes_total: number; reports_total: number; average_rating: number; by_status: StatusCount[];
  };
  support: { open: number; in_progress: number; closed: number; created_7d: number };
  printing: { orders_total: number; revenue: number; orders_by_status: StatusCount[]; houses_total: number; houses_active: number; houses_pending: number; contracts_pending: number; contracts_approved: number };
  wallets: { total_balance: number; wallets: number; deposits_completed: number; purchases_completed: number; pending_transactions: number };
  recent_orders: { id: number; customer: string; amount: number; final_amount?: number; status: string; created_at?: string }[];
  attention: {
    reviews: { id: number; type: string; body: string; author: string; product?: string | null; created_at?: string }[];
    tickets: { id: number; subject: string; status: string; author: string; created_at?: string }[];
  };
};

export type AdminTabKey = 'overview' | 'products' | 'pages' | 'tickets' | 'reviews';

/* ------------------------------------------------------------- constants */

const ORDER_LABELS: Record<string, string> = { paid: 'پرداخت‌شده', pending: 'در انتظار', failed: 'ناموفق', cancelled: 'لغوشده', refunded: 'بازپرداخت' };
const PRINT_LABELS: Record<string, string> = { pending: 'در انتظار', confirmed: 'تأییدشده', printing: 'در حال چاپ', shipped: 'ارسال‌شده', delivered: 'تحویل‌شده', cancelled: 'لغوشده' };
const TICKET_LABELS: Record<string, string> = { open: 'باز', in_progress: 'در حال بررسی', closed: 'بسته' };
const PALETTE = ['#215446', '#e97b51', '#4a7fb5', '#d49b54', '#7a6bb5', '#3f9d8f', '#c66b60'];

const n = (value: number | null | undefined) => (value ?? 0).toLocaleString('fa-IR');
const pct = (value: number, total: number) => (total > 0 ? Math.round((value / total) * 100) : 0);
const shortDay = (day: string) => new Date(day).toLocaleDateString('fa-IR', { month: 'short', day: 'numeric' });
const sum = (values: number[]) => values.reduce((total, value) => total + value, 0);

/* ----------------------------------------------------------- main view */

type Props = {
  data: AdminDashboard | undefined;
  loading: boolean;
  userName: string;
  onNavigate: (tab: AdminTabKey) => void;
  onNewProduct: () => void;
};

export default function AdminOverview({ data, loading, userName, onNavigate, onNewProduct }: Props) {
  const today = useMemo(() => new Date().toLocaleDateString('fa-IR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }), []);

  if (!data) {
    return <div className="ov-loading">{loading ? 'در حال دریافت آمار…' : 'آماری برای نمایش نیست.'}</div>;
  }

  const { sales, users, catalog, coupons, community, support, printing, wallets } = data;
  const growth = sales.revenue_growth_30d;

  return (
    <div className="ov">
      <section className="ov-hero">
        <div>
          <span className="ov-hero-date">{today}</span>
          <h2>سلام {userName.split(' ')[0]}، امروز این‌طور است.</h2>
          <p>
            امروز {formatPrice(sales.revenue_today)} فروش ثبت شده و {n(sales.pending_orders)} سفارش هنوز منتظر پرداخت است.
          </p>
        </div>
        <div className="ov-hero-actions">
          <button className="ov-btn primary" onClick={onNewProduct}><BookOpen size={15} /> افزودن کتاب</button>
          <button className="ov-btn" onClick={() => onNavigate('reviews')}><MessageSquareText size={15} /> بررسی دیدگاه‌ها <span>{n(community.comments_pending + community.questions_pending + community.replies_pending)}</span></button>
          <button className="ov-btn" onClick={() => onNavigate('tickets')}><Headset size={15} /> پشتیبانی <span>{n(support.open + support.in_progress)}</span></button>
        </div>
      </section>

      <section className="ov-kpis">
        <KpiCard tone="green" icon={<CircleDollarSign size={20} />} label="درآمد کل" value={formatPrice(sales.revenue_total)}
          foot={<Growth value={growth} />} />
        <KpiCard tone="orange" icon={<CreditCard size={20} />} label="سفارش‌های پرداخت‌شده" value={n(sales.paid_orders)}
          foot={`میانگین سبد ${formatPrice(sales.average_order_value)} · نرخ موفقیت ${n(sales.conversion_rate)}٪`} />
        <KpiCard tone="blue" icon={<Users size={20} />} label="خواننده‌ها" value={n(users.total)}
          foot={`${n(users.new_30d)} عضو جدید در ۳۰ روز · ${n(users.buyers)} خریدار`} />
        <KpiCard tone="violet" icon={<BookOpen size={20} />} label="کتاب‌های فعال" value={n(catalog.active)}
          foot={`${n(catalog.total)} عنوان · ${n(catalog.files)} فایل · ${n(catalog.draft)} پیش‌نویس`} />
      </section>

      <section className="ov-mini">
        <MiniStat label="فروش امروز" value={formatPrice(sales.revenue_today)} />
        <MiniStat label="فروش ۷ روز" value={formatPrice(sales.revenue_7d)} />
        <MiniStat label="در انتظار پرداخت" value={n(sales.pending_orders)} tone="warn" onClick={() => onNavigate('overview')} />
        <MiniStat label="ناموفق / لغو" value={n(sales.failed_orders)} tone="danger" />
        <MiniStat label="بازپرداخت" value={n(sales.refunded_orders)} />
        <MiniStat label="تیکت باز" value={n(support.open + support.in_progress)} tone="warn" onClick={() => onNavigate('tickets')} />
      </section>

      <div className="ov-grid two">
        <Card title="درآمد ۳۰ روز اخیر" subtitle="مجموع فروش پرداخت‌شده در هر روز" wide action={<span className="ov-chip">{formatPrice(sales.revenue_30d)}</span>}>
          <AreaChart points={sales.daily.map((item) => ({ label: shortDay(item.day), value: item.amount }))} format={formatPrice} />
        </Card>
        <Card title="سفارش‌ها بر اساس وضعیت" subtitle={`${n(sum(sales.orders_by_status.map((item) => item.count)))} سفارش ثبت‌شده`}>
          <Donut
            segments={sales.orders_by_status.map((item, index) => ({ label: ORDER_LABELS[item.status] ?? item.status, value: item.count, color: PALETTE[index % PALETTE.length] }))}
            center={n(data.stats.orders)} centerLabel="کل سفارش"
          />
        </Card>
      </div>

      <div className="ov-grid two">
        <Card title="کتاب‌های پرفروش" subtitle="بر اساس درآمد از سفارش‌های پرداخت‌شده">
          {catalog.top_products.length ? <HBars rows={catalog.top_products.map((item) => ({ label: item.title, value: item.revenue, sub: `${n(item.sales)} فروش` }))} format={formatPrice} /> : <Empty text="هنوز فروشی ثبت نشده است." />}
        </Card>
        <Card title="درآمد بر اساس دسته‌بندی" subtitle="سهم هر دسته از کل فروش">
          {catalog.revenue_by_category.length ? <HBars rows={catalog.revenue_by_category.map((item) => ({ label: item.name, value: item.revenue }))} format={formatPrice} /> : <Empty text="داده‌ای برای دسته‌ها نیست." />}
        </Card>
      </div>

      <div className="ov-grid three">
        <Card title="خواننده‌های جدید" subtitle={`${n(users.new_7d)} نفر در ۷ روز اخیر`}>
          <BarSpark points={users.daily_new.map((item) => ({ label: shortDay(item.day), value: item.count }))} color="#4a7fb5" />
          <StatList items={[['کل خواننده‌ها', users.total], ['حساب‌های تأییدشده', users.verified], ['خریداران', users.buyers], ['مدیران', users.admins]]} />
        </Card>
        <Card title="دیدگاه و پرسش و پاسخ" subtitle="وضعیت تأیید محتوای کاربران">
          <StackBar segments={community.by_status.map((item) => ({ label: item.status === 'approved' ? 'منتشرشده' : item.status === 'pending' ? 'در انتظار' : 'ردشده', value: item.count, color: item.status === 'approved' ? '#4f9a6b' : item.status === 'pending' ? '#d49b54' : '#c66b60' }))} />
          <StatList items={[['دیدگاه منتشرشده', community.comments_approved], ['پرسش منتشرشده', community.questions_approved], ['پاسخ‌ها', community.replies_total], ['پسندها', community.likes_total], ['گزارش‌ها', community.reports_total]]} />
          <div className="ov-note">میانگین امتیاز: <b>{(community.average_rating || 0).toLocaleString('fa-IR')} از ۵</b></div>
        </Card>
        <Card title="پشتیبانی" subtitle={`${n(support.created_7d)} تیکت در ۷ روز اخیر`}>
          <StackBar segments={[
            { label: TICKET_LABELS.open, value: support.open, color: '#c66b60' },
            { label: TICKET_LABELS.in_progress, value: support.in_progress, color: '#d49b54' },
            { label: TICKET_LABELS.closed, value: support.closed, color: '#4f9a6b' },
          ]} />
          <StatList items={[['باز', support.open], ['در حال بررسی', support.in_progress], ['بسته‌شده', support.closed]]} />
        </Card>
      </div>

      <div className="ov-grid three">
        <Card title="چاپ کاغذی" subtitle={`${n(printing.orders_total)} سفارش چاپ`}>
          <HBars rows={printing.orders_by_status.map((item) => ({ label: PRINT_LABELS[item.status] ?? item.status, value: item.count }))} format={n} />
          <StatList items={[['درآمد چاپ', formatPrice(printing.revenue)], ['چاپخانه‌های فعال', printing.houses_active], ['درخواست همکاری', printing.contracts_pending]]} />
        </Card>
        <Card title="کیف پول" subtitle="جریان مالی کیف پول خواننده‌ها">
          <div className="ov-wallet-total"><Wallet size={18} /><div><small>مجموع موجودی</small><b>{formatPrice(wallets.total_balance)}</b></div></div>
          <StatList items={[['کیف پول‌های فعال', wallets.wallets], ['واریز موفق', formatPrice(wallets.deposits_completed)], ['خرید از کیف پول', formatPrice(wallets.purchases_completed)], ['تراکنش در انتظار', wallets.pending_transactions]]} />
        </Card>
        <Card title="کدهای تخفیف" subtitle={`${n(coupons.active)} کد فعال از ${n(coupons.total)}`}>
          <div className="ov-wallet-total"><Percent size={18} /><div><small>مجموع تخفیف داده‌شده</small><b>{formatPrice(coupons.discount_given)}</b></div></div>
          <StatList items={[['سفارش با کد تخفیف', coupons.orders_with_coupon], ['کدهای فعال', coupons.active], ['کدهای ثبت‌شده', coupons.total]]} />
        </Card>
      </div>

      <div className="ov-grid two">
        <Card title="در انتظار بررسی" subtitle="موارد نیازمند اقدام شما" action={<button className="ov-link" onClick={() => onNavigate('reviews')}>همه‌ی دیدگاه‌ها <ArrowLeft size={13} /></button>}>
          {data.attention.reviews.length ? (
            <ul className="ov-list">
              {data.attention.reviews.map((item) => (
                <li key={item.id}>
                  <span className={`ov-tag ${item.type}`}>{item.type === 'question' ? 'پرسش' : item.type === 'reply' ? 'پاسخ' : 'دیدگاه'}</span>
                  <div><b>{item.author}</b><small>{item.product ?? 'کتاب'} · {formatDate(item.created_at)}</small><p>{item.body}</p></div>
                </li>
              ))}
            </ul>
          ) : <Empty text="همه‌ی دیدگاه‌ها بررسی شده‌اند." />}
        </Card>
        <Card title="تیکت‌های باز" subtitle="درخواست‌های پشتیبانی که هنوز بسته نشده‌اند" action={<button className="ov-link" onClick={() => onNavigate('tickets')}>همه‌ی تیکت‌ها <ArrowLeft size={13} /></button>}>
          {data.attention.tickets.length ? (
            <ul className="ov-list">
              {data.attention.tickets.map((ticket) => (
                <li key={ticket.id}>
                  <span className={`ov-tag ${ticket.status}`}>{TICKET_LABELS[ticket.status] ?? ticket.status}</span>
                  <div><b>{ticket.subject}</b><small>{ticket.author} · {formatDate(ticket.created_at)}</small></div>
                </li>
              ))}
            </ul>
          ) : <Empty text="تیکت بازی وجود ندارد." />}
        </Card>
      </div>

      <Card title="آخرین سفارش‌ها" subtitle="تازه‌ترین تراکنش‌های فروشگاه">
        <div className="ov-table">
          <div className="ov-table-head"><span>سفارش</span><span>خریدار</span><span>مبلغ</span><span>وضعیت</span><span>زمان</span></div>
          {data.recent_orders.map((order) => (
            <div className="ov-table-row" key={order.id}>
              <b>#{n(order.id)}</b>
              <span>{order.customer}</span>
              <b>{formatPrice(order.final_amount ?? order.amount)}</b>
              <span className={`ov-status ${order.status}`}>{ORDER_LABELS[order.status] ?? order.status}</span>
              <small>{formatDate(order.created_at)}</small>
            </div>
          ))}
        </div>
      </Card>
    </div>
  );
}

/* ------------------------------------------------------------ building blocks */

function KpiCard({ tone, icon, label, value, foot }: { tone: string; icon: ReactNode; label: string; value: string; foot: ReactNode }) {
  return (
    <article className={`ov-kpi ${tone}`}>
      <span className="ov-kpi-icon">{icon}</span>
      <small>{label}</small>
      <b>{value}</b>
      <span className="ov-kpi-foot">{foot}</span>
    </article>
  );
}

function Growth({ value }: { value: number | null }) {
  if (value === null) return <span>۳۰ روز اخیر: بدون مقایسه</span>;
  const up = value >= 0;
  return <span className={`ov-growth ${up ? 'up' : 'down'}`}>{up ? <TrendingUp size={13} /> : <TrendingDown size={13} />} {Math.abs(value).toLocaleString('fa-IR')}٪ نسبت به ۳۰ روز قبل</span>;
}

function MiniStat({ label, value, tone, onClick }: { label: string; value: string; tone?: 'warn' | 'danger'; onClick?: () => void }) {
  const Tag = onClick ? 'button' : 'div';
  return (
    <Tag className={`ov-mini-item ${tone ?? ''}`} onClick={onClick}>
      <small>{label}</small>
      <b>{value}</b>
    </Tag>
  );
}

function Card({ title, subtitle, action, wide, children }: { title: string; subtitle?: string; action?: ReactNode; wide?: boolean; children: ReactNode }) {
  return (
    <section className={`ov-card ${wide ? 'wide' : ''}`}>
      <header>
        <div><h3>{title}</h3>{subtitle && <p>{subtitle}</p>}</div>
        {action}
      </header>
      {children}
    </section>
  );
}

function Empty({ text }: { text: string }) {
  return <p className="ov-empty">{text}</p>;
}

function StatList({ items }: { items: [string, number | string][] }) {
  return (
    <dl className="ov-statlist">
      {items.map(([label, value]) => <div key={label}><dt>{label}</dt><dd>{typeof value === 'number' ? n(value) : value}</dd></div>)}
    </dl>
  );
}

function AreaChart({ points, format }: { points: { label: string; value: number }[]; format: (value: number) => string }) {
  const width = 640;
  const height = 230;
  const padX = 12;
  const padTop = 16;
  const padBottom = 26;
  const max = Math.max(1, ...points.map((point) => point.value));
  const step = points.length > 1 ? (width - padX * 2) / (points.length - 1) : 0;
  const coords = points.map((point, index) => ({
    x: padX + index * step,
    y: padTop + (1 - point.value / max) * (height - padTop - padBottom),
  }));
  const line = coords.map((c, i) => `${i === 0 ? 'M' : 'L'}${c.x.toFixed(1)},${c.y.toFixed(1)}`).join(' ');
  const area = coords.length ? `${line} L${coords[coords.length - 1].x.toFixed(1)},${height - padBottom} L${coords[0].x.toFixed(1)},${height - padBottom} Z` : '';
  const peak = points.reduce((best, point) => (point.value > best.value ? point : best), { label: '', value: 0 });
  const gradientId = 'ov-area-grad';
  const total = sum(points.map((point) => point.value));

  return (
    <div className="ov-area">
      <div className="ov-area-meta">
        <span>میانگین روزانه: <b>{format(Math.round(total / Math.max(1, points.length)))}</b></span>
        <span>بیشترین فروش: <b>{peak.value > 0 ? `${format(peak.value)} · ${peak.label}` : '—'}</b></span>
      </div>
      <svg viewBox={`0 0 ${width} ${height}`} role="img" aria-label="نمودار درآمد روزانه">
        <defs>
          <linearGradient id={gradientId} x1="0" x2="0" y1="0" y2="1">
            <stop offset="0%" stopColor="#215446" stopOpacity="0.28" />
            <stop offset="100%" stopColor="#215446" stopOpacity="0" />
          </linearGradient>
        </defs>
        {[0.25, 0.5, 0.75].map((ratio) => <line key={ratio} x1={padX} x2={width - padX} y1={padTop + ratio * (height - padTop - padBottom)} y2={padTop + ratio * (height - padTop - padBottom)} className="ov-grid-line" />)}
        {area && <path d={area} fill={`url(#${gradientId})`} />}
        {line && <path d={line} className="ov-area-line" />}
        {coords.map((c, index) => <circle key={index} cx={c.x} cy={c.y} r={points[index].value > 0 ? 3 : 1.5} className="ov-area-dot"><title>{`${points[index].label}: ${format(points[index].value)}`}</title></circle>)}
        {points.map((point, index) => (index % 5 === 0 || index === points.length - 1) ? <text key={index} x={coords[index].x} y={height - 6} textAnchor="middle" className="ov-axis">{point.label}</text> : null)}
      </svg>
    </div>
  );
}

function BarSpark({ points, color }: { points: { label: string; value: number }[]; color: string }) {
  const max = Math.max(1, ...points.map((point) => point.value));
  return (
    <div className="ov-spark" aria-label="نمودار ستونی">
      {points.map((point, index) => (
        <span key={index} title={`${point.label}: ${n(point.value)}`}>
          <i style={{ height: `${Math.max(4, (point.value / max) * 100)}%`, background: color, opacity: point.value ? 1 : 0.25 }} />
        </span>
      ))}
    </div>
  );
}

function HBars({ rows, format }: { rows: { label: string; value: number; sub?: string }[]; format: (value: number) => string }) {
  const max = Math.max(1, ...rows.map((row) => row.value));
  return (
    <ul className="ov-hbars">
      {rows.map((row, index) => (
        <li key={row.label + index}>
          <div><span>{row.label}</span><b>{format(row.value)}</b></div>
          <div className="ov-bar-track"><i style={{ width: `${(row.value / max) * 100}%`, background: PALETTE[index % PALETTE.length] }} /></div>
          {row.sub && <small>{row.sub}</small>}
        </li>
      ))}
    </ul>
  );
}

function StackBar({ segments }: { segments: { label: string; value: number; color: string }[] }) {
  const total = sum(segments.map((segment) => segment.value));
  return (
    <div className="ov-stack-wrap">
      <div className="ov-stack">
        {total > 0 ? segments.filter((s) => s.value > 0).map((segment) => <i key={segment.label} style={{ width: `${pct(segment.value, total)}%`, background: segment.color }} title={`${segment.label}: ${n(segment.value)}`} />) : <i style={{ width: '100%', background: '#e7ebe4' }} />}
      </div>
      <div className="ov-legend">
        {segments.map((segment) => <span key={segment.label}><i style={{ background: segment.color }} />{segment.label} <b>{n(segment.value)}</b></span>)}
      </div>
    </div>
  );
}

function Donut({ segments, center, centerLabel }: { segments: { label: string; value: number; color: string }[]; center: string; centerLabel: string }) {
  const total = sum(segments.map((segment) => segment.value));
  let offset = 0;
  return (
    <div className="ov-donut">
      <div className="ov-donut-ring">
      <svg viewBox="0 0 42 42" aria-hidden="true">
        <circle cx="21" cy="21" r="15.9" className="ov-donut-track" />
        {total > 0 && segments.filter((s) => s.value > 0).map((segment) => {
          const share = (segment.value / total) * 100;
          const node = <circle key={segment.label} cx="21" cy="21" r="15.9" pathLength={100} stroke={segment.color} strokeDasharray={`${share} ${100 - share}`} strokeDashoffset={-offset} className="ov-donut-seg" />;
          offset += share;
          return node;
        })}
      </svg>
      <div className="ov-donut-center"><b>{center}</b><small>{centerLabel}</small></div>
      </div>
      <ul className="ov-legend vertical">
        {segments.map((segment) => <li key={segment.label}><i style={{ background: segment.color }} /><span>{segment.label}</span><b>{n(segment.value)}</b><em>{pct(segment.value, total)}٪</em></li>)}
      </ul>
    </div>
  );
}

/* ------------------------------------------------------------ demo data */

const demoDays = (count: number, base: (index: number) => number) => Array.from({ length: count }, (_, index) => {
  const date = new Date();
  date.setDate(date.getDate() - (count - 1 - index));
  return { day: date.toISOString().slice(0, 10), amount: base(index), count: Math.round(base(index) / 900000) };
});

export function demoDashboard(): AdminDashboard {
  const series = demoDays(30, (index) => Math.round(18000000 + Math.sin(index / 3) * 9000000 + ((index * 7919) % 11) * 1000000));
  return {
    stats: { products: 24, users: 2481, orders: 684, revenue: 438600000, pending_orders: 12, open_tickets: 7, pending_reviews: 9 },
    sales: {
      revenue_total: 438600000, revenue_today: 14200000, revenue_7d: 96400000, revenue_30d: 402100000, revenue_growth_30d: 12.4,
      paid_orders: 521, average_order_value: 842000, conversion_rate: 76.2, refunded_orders: 9, failed_orders: 41, pending_orders: 12,
      orders_by_status: [{ status: 'paid', count: 521 }, { status: 'pending', count: 12 }, { status: 'failed', count: 41 }, { status: 'cancelled', count: 96 }, { status: 'refunded', count: 9 }],
      daily: series.map(({ day, amount }) => ({ day, amount })),
      daily_orders: series.map(({ day, count }) => ({ day, count })),
    },
    users: { total: 2481, admins: 2, verified: 2210, new_7d: 64, new_30d: 311, buyers: 402, daily_new: series.map(({ day, count }) => ({ day, count: count % 9 })) },
    catalog: {
      total: 24, active: 19, draft: 3, inactive: 2, unavailable: 1, files: 41, categories: 6, tags: 14,
      top_products: [
        { id: 1, title: 'لینوکس برای هکرها', revenue: 96000000, sales: 118 },
        { id: 2, title: 'راهنمای کامل HTTP', revenue: 71000000, sales: 74 },
        { id: 3, title: 'ریاضیات کامل دبیرستان', revenue: 52000000, sales: 66 },
        { id: 4, title: 'نادرشاه؛ بازخوانی', revenue: 33000000, sales: 41 },
      ],
      revenue_by_category: [{ name: 'برنامه‌نویسی', revenue: 190000000 }, { name: 'تاریخ', revenue: 120000000 }, { name: 'ریاضی', revenue: 88000000 }, { name: 'تربیتی', revenue: 40000000 }],
    },
    coupons: { total: 12, active: 4, orders_with_coupon: 138, discount_given: 21400000 },
    community: {
      comments_approved: 214, comments_pending: 6, questions_approved: 58, questions_pending: 3, replies_total: 121, replies_pending: 2,
      rejected: 11, likes_total: 640, reports_total: 4, average_rating: 4.6,
      by_status: [{ status: 'approved', count: 272 }, { status: 'pending', count: 11 }, { status: 'rejected', count: 11 }],
    },
    support: { open: 5, in_progress: 2, closed: 88, created_7d: 9 },
    printing: {
      orders_total: 47, revenue: 61800000,
      orders_by_status: [{ status: 'pending', count: 6 }, { status: 'confirmed', count: 9 }, { status: 'printing', count: 11 }, { status: 'shipped', count: 8 }, { status: 'delivered', count: 12 }, { status: 'cancelled', count: 1 }],
      houses_total: 5, houses_active: 4, houses_pending: 1, contracts_pending: 2, contracts_approved: 3,
    },
    wallets: { total_balance: 18400000, wallets: 312, deposits_completed: 52000000, purchases_completed: 33600000, pending_transactions: 3 },
    recent_orders: [
      { id: 684, customer: 'آرمان رضایی', amount: 390000, status: 'paid', created_at: new Date().toISOString() },
      { id: 683, customer: 'مریم نادری', amount: 620000, status: 'pending', created_at: new Date(Date.now() - 3600000).toISOString() },
      { id: 682, customer: 'پارسا کاظمی', amount: 1200000, status: 'paid', created_at: new Date(Date.now() - 7200000).toISOString() },
    ],
    attention: {
      reviews: [{ id: 1, type: 'comment', body: 'برای شروع کار با لینوکس خیلی روان و مفید بود.', author: 'مینا سلیمانی', product: 'لینوکس برای هکرها', created_at: new Date().toISOString() }],
      tickets: [{ id: 2, subject: 'سؤال درباره‌ی فرمت فایل', status: 'in_progress', author: 'پویا احمدی', created_at: new Date().toISOString() }],
    },
  };
}
