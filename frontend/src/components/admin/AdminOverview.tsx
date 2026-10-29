import { ArrowLeft, Receipt, Ticket, MessageSquareText, Users, Eye } from 'lucide-react';
import { formatPrice } from '../../lib/format';
import { AreaChart, BarsChart, CHART_COLORS } from './Charts';

export type AdminTabKey = 'overview' | 'products' | 'users' | 'tickets' | 'pages' | 'reviews' | 'tasks';

export type AdminDashboard = {
  stats: {
    products: number;
    users: number;
    new_users_30d: number;
    orders: number;
    paid_orders: number;
    pending_orders: number;
    revenue: number;
    revenue_30d: number;
    open_tickets: number;
    pending_reviews: number;
    visits_today: number;
    visits_30d: number;
    visitors_30d: number;
  };
  sales: { daily: { day: string; amount: number }[] };
  users: { daily_new: { day: string; count: number }[] };
  visits: { daily: { day: string; views: number; visitors: number }[] };
  recent_orders: { id: number; customer: string; amount: number; paid_at?: string | null }[];
};

type Props = {
  data?: AdminDashboard;
  loading?: boolean;
  userName: string;
  onNavigate: (tab: AdminTabKey) => void;
};

const toFa = (value: number) => value.toLocaleString('fa-IR');

function dayLabel(day: string) {
  return new Date(`${day}T00:00:00`).toLocaleDateString('fa-IR', { month: 'short', day: 'numeric' });
}

export default function AdminOverview({ data, loading, userName, onNavigate }: Props) {
  if (!data) {
    return <div className="ov-wrap"><p className="ov-loading">{loading ? 'در حال بارگذاری آمار…' : 'آماری برای نمایش نیست.'}</p></div>;
  }

  const { stats } = data;
  const labels = data.sales.daily.map((point) => dayLabel(point.day));
  const cards = [
    { label: 'فروش ۳۰ روز', value: formatPrice(stats.revenue_30d), hint: `کل فروش ${formatPrice(stats.revenue)}`, icon: Receipt, tone: 'green' },
    { label: 'کاربران جدید', value: toFa(stats.new_users_30d), hint: `کل کاربران ${toFa(stats.users)}`, icon: Users, tone: 'blue' },
    { label: 'بازدید ۳۰ روز', value: toFa(stats.visits_30d), hint: `${toFa(stats.visitors_30d)} بازدیدکننده‌ی یکتا`, icon: Eye, tone: 'violet' },
    { label: 'درخواست‌های باز', value: toFa(stats.open_tickets), hint: 'نیازمند پاسخ پشتیبانی', icon: Ticket, tone: 'orange', tab: 'tickets' as const },
    { label: 'دیدگاه در انتظار', value: toFa(stats.pending_reviews), hint: 'منتظر تأیید شما', icon: MessageSquareText, tone: 'amber', tab: 'reviews' as const },
  ];

  return (
    <div className="ov-wrap">
      <div className="ov-hero">
        <div className="ov-hero-copy">
          <span className="ov-hero-date">{new Date().toLocaleDateString('fa-IR', { weekday: 'long', day: 'numeric', month: 'long' })}</span>
          <h2>{userName} عزیز، امروز {toFa(stats.visits_today)} بازدید ثبت شده است.</h2>
          <p>{toFa(stats.paid_orders)} سفارش پرداخت‌شده تا امروز · {toFa(stats.pending_orders)} سفارش در انتظار پرداخت</p>
        </div>
        <div className="ov-hero-actions">
          <button className="ov-btn primary" onClick={() => onNavigate('tickets')}>پاسخ به پشتیبانی <ArrowLeft size={14} /></button>
          <button className="ov-btn" onClick={() => onNavigate('users')}>کاربران</button>
        </div>
      </div>

      <div className="ov-kpis">
        {cards.map((card) => {
          const Icon = card.icon;
          const inner = (
            <>
              <span className={`ov-kpi-icon ${card.tone}`}><Icon size={18} /></span>
              <div><small>{card.label}</small><b>{card.value}</b><em>{card.hint}</em></div>
            </>
          );
          return card.tab
            ? <button key={card.label} className="ov-kpi ov-kpi-link" onClick={() => onNavigate(card.tab)}>{inner}</button>
            : <article key={card.label} className="ov-kpi">{inner}</article>;
        })}
      </div>

      <div className="ov-charts">
        <section className="ov-card ov-card-wide">
          <header><div><h3>فروش روزانه</h3><p>مجموع سفارش‌های پرداخت‌شده در ۳۰ روز گذشته</p></div></header>
          <AreaChart labels={labels} values={data.sales.daily.map((point) => point.amount)} label="فروش" format={(value) => formatPrice(value)} height={250} />
        </section>
        <section className="ov-card">
          <header><div><h3>کاربران جدید</h3><p>ثبت‌نام روزانه</p></div></header>
          <BarsChart labels={labels} datasets={[{ label: 'کاربر جدید', data: data.users.daily_new.map((point) => point.count), color: CHART_COLORS[2] }]} height={230} />
        </section>
        <section className="ov-card">
          <header><div><h3>بازدید</h3><p>تعداد بازدید و بازدیدکننده‌ی یکتا در روز</p></div></header>
          <BarsChart
            labels={labels}
            datasets={[
              { label: 'بازدید', data: data.visits.daily.map((point) => point.views), color: CHART_COLORS[0] },
              { label: 'بازدیدکننده', data: data.visits.daily.map((point) => point.visitors), color: CHART_COLORS[1] },
            ]}
            height={230}
          />
        </section>
      </div>

      <section className="ov-card ov-recent">
        <header><div><h3>آخرین فروش‌ها</h3><p>سفارش‌های تازه‌ی پرداخت‌شده</p></div></header>
        {data.recent_orders.length ? (
          <ul>
            {data.recent_orders.map((order) => (
              <li key={order.id}><span className="review-avatar">{order.customer.slice(0, 1)}</span><span><b>{order.customer}</b><small>سفارش #{toFa(order.id)}</small></span><strong>{formatPrice(order.amount)}</strong></li>
            ))}
          </ul>
        ) : <p className="ov-empty">هنوز فروشی ثبت نشده است.</p>}
      </section>
    </div>
  );
}

const DEMO_DAYS = 30;
const demoLabelsDates = Array.from({ length: DEMO_DAYS }, (_, index) => {
  const date = new Date();
  date.setDate(date.getDate() - (DEMO_DAYS - 1 - index));
  return date.toISOString().slice(0, 10);
});

/** Illustrative numbers for demo mode only. */
export function demoDashboard(): AdminDashboard {
  return {
    stats: {
      products: 24, users: 318, new_users_30d: 42, orders: 610, paid_orders: 584, pending_orders: 12,
      revenue: 182400000, revenue_30d: 23150000, open_tickets: 2, pending_reviews: 2,
      visits_today: 137, visits_30d: 4210, visitors_30d: 1376,
    },
    sales: { daily: demoLabelsDates.map((day, i) => ({ day, amount: 400000 + ((i * 370000) % 1400000) })) },
    users: { daily_new: demoLabelsDates.map((day, i) => ({ day, count: (i * 7) % 5 })) },
    visits: { daily: demoLabelsDates.map((day, i) => ({ day, views: 90 + ((i * 53) % 120), visitors: 40 + ((i * 29) % 60) })) },
    recent_orders: [
      { id: 1042, customer: 'سارا بهرامی', amount: 285000, paid_at: new Date().toISOString() },
      { id: 1041, customer: 'پویا احمدی', amount: 190000, paid_at: new Date().toISOString() },
    ],
  };
}
