import { useMemo, useState, type ReactNode } from 'react';
import { ArrowLeft, BookOpen, Check, CircleAlert, CircleDollarSign, CreditCard, Headset, Layers, MessageSquareText, Percent, Printer, ShoppingBag, TrendingDown, TrendingUp, Users, Wallet, X } from 'lucide-react';
import { formatDate, formatPrice } from '../../lib/format';
import { AreaChart, BarsChart, DoughnutChart, Sparkline } from './Charts';

/* ------------------------------------------------------------------ types */

export type StatusCount = { status: string; count: number };
export type DayValue = { day: string; amount: number };
export type DayCount = { day: string; count: number };
export type TypeStatusRow = { type: string; label: string; approved: number; pending: number; rejected: number };

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
    rejected: number; likes_total: number; reports_total: number; average_rating: number; by_status: StatusCount[]; by_type_status: TypeStatusRow[];
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
type Section = 'overview' | 'sales' | 'users' | 'content' | 'operations';

/* ------------------------------------------------------------- constants */

const ORDER_LABELS: Record<string, string> = { paid: 'پرداخت‌شده', pending: 'در انتظار', failed: 'ناموفق', cancelled: 'لغوشده', refunded: 'بازپرداخت' };
const PRINT_LABELS: Record<string, string> = { pending: 'در انتظار', confirmed: 'تأییدشده', printing: 'در حال چاپ', shipped: 'ارسال‌شده', delivered: 'تحویل‌شده', cancelled: 'لغوشده' };
const SECTIONS: { key: Section; label: string }[] = [
  { key: 'overview', label: 'نمای کلی' },
  { key: 'sales', label: 'فروش' },
  { key: 'users', label: 'خواننده‌ها' },
  { key: 'content', label: 'دیدگاه و پرسش' },
  { key: 'operations', label: 'پشتیبانی و چاپ' },
];
const C = { green: '#215446', orange: '#e97b51', blue: '#4a7fb5', gold: '#d49b54', violet: '#7a6bb5', teal: '#3f9d8f', red: '#c66b60', ok: '#4f9a6b', muted: '#c9d3cb' };

const n = (value: number | null | undefined) => (value ?? 0).toLocaleString('fa-IR');
const pctText = (value: number, total: number) => `${(total > 0 ? Math.round((value / total) * 100) : 0).toLocaleString('fa-IR')}٪`;
const shortDay = (day: string) => new Date(day).toLocaleDateString('fa-IR', { month: 'short', day: 'numeric' });
const sum = (values: number[]) => values.reduce((total, value) => total + value, 0);

/* ----------------------------------------------------------- main view */

type Props = {
  data: AdminDashboard | undefined;
  loading: boolean;
  userName: string;
  onNavigate: (tab: AdminTabKey) => void;
  onNewProduct: () => void;
  onModerate: (id: number, status: 'approved' | 'rejected') => void;
};

export default function AdminOverview({ data, loading, userName, onNavigate, onNewProduct, onModerate }: Props) {
  const [section, setSection] = useState<Section>('overview');
  const today = useMemo(() => new Date().toLocaleDateString('fa-IR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }), []);

  if (!data) return <div className="ov-loading">{loading ? 'در حال دریافت آمار…' : 'آماری برای نمایش نیست.'}</div>;

  const pendingContent = data.community.comments_pending + data.community.questions_pending + data.community.replies_pending;
  const openTickets = data.support.open + data.support.in_progress;

  type Alert = { label: string; count: number; tab?: AdminTabKey; section?: Section; tone: 'warn' | 'danger' | 'info' };
  const alertList: Alert[] = [
    { label: 'سفارش در انتظار پرداخت', count: data.sales.pending_orders, section: 'sales', tone: 'warn' },
    { label: 'دیدگاه و پرسش در انتظار تأیید', count: pendingContent, section: 'content', tone: 'warn' },
    { label: 'تیکت پشتیبانی باز', count: openTickets, tab: 'tickets', tone: 'danger' },
    { label: 'درخواست همکاری چاپخانه', count: data.printing.contracts_pending, section: 'operations', tone: 'info' },
    { label: 'کتاب پیش‌نویس', count: data.catalog.draft, tab: 'products', tone: 'info' },
    { label: 'کتاب ناموجود', count: data.catalog.unavailable, tab: 'products', tone: 'info' },
  ];
  const alerts = alertList.filter((alert) => alert.count > 0);

  const goAlert = (alert: Alert) => (alert.tab ? onNavigate(alert.tab) : alert.section && setSection(alert.section));

  return (
    <div className="ov">
      <section className="ov-hero">
        <div className="ov-hero-copy">
          <span className="ov-hero-date">{today}</span>
          <h2>سلام {userName.split(' ')[0]} 👋</h2>
          <p>امروز <b>{formatPrice(data.sales.revenue_today)}</b> فروش داشته‌ایم و <b>{n(data.sales.paid_orders)}</b> سفارش موفق در کل ثبت شده است.</p>
        </div>
        <div className="ov-hero-actions">
          <button className="ov-btn primary" onClick={onNewProduct}><BookOpen size={15} /> کتاب جدید</button>
          <button className="ov-btn" onClick={() => onNavigate('reviews')}><MessageSquareText size={15} /> بررسی محتوا</button>
          <button className="ov-btn" onClick={() => onNavigate('tickets')}><Headset size={15} /> پشتیبانی</button>
        </div>
      </section>

      <nav className="ov-sections" aria-label="بخش‌های آمار">
        {SECTIONS.map((item) => (
          <button key={item.key} className={section === item.key ? 'active' : ''} onClick={() => setSection(item.key)}>{item.label}</button>
        ))}
      </nav>

      {alerts.length > 0 && section === 'overview' && (
        <section className="ov-alerts" aria-label="هشدارها">
          {alerts.map((alert) => (
            <button key={alert.label} className={`ov-alert ${alert.tone}`} onClick={() => goAlert(alert)}>
              <CircleAlert size={15} />
              <span>{alert.label}</span>
              <b>{n(alert.count)}</b>
              <ArrowLeft size={13} />
            </button>
          ))}
        </section>
      )}

      {section === 'overview' && <OverviewSection data={data} onNavigate={onNavigate} onModerate={onModerate} onSection={setSection} />}
      {section === 'sales' && <SalesSection data={data} />}
      {section === 'users' && <UsersSection data={data} />}
      {section === 'content' && <ContentSection data={data} onNavigate={onNavigate} onModerate={onModerate} />}
      {section === 'operations' && <OperationsSection data={data} onNavigate={onNavigate} />}
    </div>
  );
}

/* ------------------------------------------------------------- sections */

function OverviewSection({ data, onNavigate, onModerate, onSection }: { data: AdminDashboard; onNavigate: (tab: AdminTabKey) => void; onModerate: Props['onModerate']; onSection: (section: Section) => void }) {
  const [range, setRange] = useState<7 | 30>(30);
  const daily = data.sales.daily.slice(-range);
  const { sales, users, catalog } = data;
  const revenueSeries = sales.daily.map((item) => item.amount);
  const ordersSeries = sales.daily_orders.map((item) => item.count);
  const usersSeries = users.daily_new.map((item) => item.count);

  return (
    <>
      <div className="ov-kpis">
        <Kpi tone={C.green} icon={<CircleDollarSign size={19} />} label="درآمد کل" value={formatPrice(sales.revenue_total)} foot={<Growth value={sales.revenue_growth_30d} />} spark={revenueSeries} />
        <Kpi tone={C.orange} icon={<CreditCard size={19} />} label="سفارش موفق" value={n(sales.paid_orders)} foot={`میانگین سبد ${formatPrice(sales.average_order_value)}`} spark={ordersSeries} />
        <Kpi tone={C.blue} icon={<Users size={19} />} label="خواننده‌ها" value={n(users.total)} foot={`${n(users.new_30d)} عضو جدید در ۳۰ روز`} spark={usersSeries} />
        <Kpi tone={C.violet} icon={<BookOpen size={19} />} label="کتاب‌های فعال" value={n(catalog.active)} foot={`از ${n(catalog.total)} عنوان · ${n(catalog.files)} فایل`} />
      </div>

      <div className="ov-grid main">
        <Card className="span-2" title="روند درآمد" subtitle="فروش پرداخت‌شده در هر روز" action={<Segment value={range} options={[[7, '۷ روز'], [30, '۳۰ روز']]} onChange={(value) => setRange(value as 7 | 30)} />}>
          <div className="ov-stats-row">
            <Stat label="مجموع دوره" value={formatPrice(sum(daily.map((item) => item.amount)))} />
            <Stat label="بیشترین روز" value={formatPrice(Math.max(0, ...daily.map((item) => item.amount)))} />
            <Stat label="۳۰ روز اخیر" value={formatPrice(sales.revenue_30d)} />
          </div>
          <AreaChart labels={daily.map((item) => shortDay(item.day))} values={daily.map((item) => item.amount)} color={C.green} format={formatPrice} label="درآمد" height={250} />
        </Card>
        <Card title="وضعیت سفارش‌ها" subtitle={`${n(sum(sales.orders_by_status.map((item) => item.count)))} سفارش`}>
          <DoughnutChart
            labels={sales.orders_by_status.map((item) => ORDER_LABELS[item.status] ?? item.status)}
            values={sales.orders_by_status.map((item) => item.count)}
            colors={[C.ok, C.gold, C.red, C.muted, C.blue]}
            height={200}
          />
          <Legend items={sales.orders_by_status.map((item, index) => ({ label: ORDER_LABELS[item.status] ?? item.status, value: item.count, color: [C.ok, C.gold, C.red, C.muted, C.blue][index] }))} />
        </Card>
      </div>

      <div className="ov-grid two">
        <Card title="نیازمند تأیید" subtitle="دیدگاه‌ها و پرسش‌های تازه؛ همین‌جا تأیید یا رد کن" action={<LinkButton onClick={() => onNavigate('reviews')}>همه‌ی موارد</LinkButton>}>
          {data.attention.reviews.length ? (
            <ul className="ov-queue">
              {data.attention.reviews.map((item) => (
                <li key={item.id}>
                  <span className={`ov-tag ${item.type}`}>{item.type === 'question' ? 'پرسش' : item.type === 'reply' ? 'پاسخ' : 'دیدگاه'}</span>
                  <div className="ov-queue-body">
                    <b>{item.author} <small>· {item.product ?? 'کتاب'}</small></b>
                    <p>{item.body}</p>
                    <small>{formatDate(item.created_at)}</small>
                  </div>
                  <div className="ov-queue-actions">
                    <button className="ok" title="تأیید" onClick={() => onModerate(item.id, 'approved')}><Check size={15} /></button>
                    <button className="no" title="رد" onClick={() => onModerate(item.id, 'rejected')}><X size={15} /></button>
                  </div>
                </li>
              ))}
            </ul>
          ) : <Empty text="همه‌ی موارد بررسی شده‌اند. ✨" />}
        </Card>
        <Card title="پرفروش‌ترین کتاب‌ها" subtitle="بر اساس درآمد" action={<LinkButton onClick={() => onSection('sales')}>جزئیات فروش</LinkButton>}>
          {catalog.top_products.length ? (
            <BarsChart horizontal height={260} labels={catalog.top_products.map((item) => truncate(item.title, 26))} datasets={[{ label: 'درآمد', data: catalog.top_products.map((item) => item.revenue), color: C.green }]} format={formatPrice} />
          ) : <Empty text="هنوز فروشی ثبت نشده است." />}
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
    </>
  );
}

function SalesSection({ data }: { data: AdminDashboard }) {
  const { sales, catalog, coupons } = data;
  const categories = catalog.revenue_by_category;
  return (
    <>
      <div className="ov-kpis">
        <Kpi tone={C.green} icon={<CircleDollarSign size={19} />} label="امروز" value={formatPrice(sales.revenue_today)} foot={`۷ روز: ${formatPrice(sales.revenue_7d)}`} />
        <Kpi tone={C.orange} icon={<ShoppingBag size={19} />} label="میانگین سبد خرید" value={formatPrice(sales.average_order_value)} foot={`${n(sales.paid_orders)} سفارش موفق`} />
        <Kpi tone={C.blue} icon={<Percent size={19} />} label="نرخ موفقیت پرداخت" value={`${n(sales.conversion_rate)}٪`} foot={`${n(sales.pending_orders)} در انتظار · ${n(sales.failed_orders)} ناموفق`} />
        <Kpi tone={C.red} icon={<TrendingDown size={19} />} label="بازپرداخت" value={n(sales.refunded_orders)} foot={`کد تخفیف: ${formatPrice(coupons.discount_given)} داده شده`} />
      </div>

      <div className="ov-grid main">
        <Card className="span-2" title="درآمد ۳۰ روزه" subtitle="هر نقطه یک روز فروش است" action={<span className="ov-chip">{formatPrice(sales.revenue_30d)}</span>}>
          <AreaChart labels={sales.daily.map((item) => shortDay(item.day))} values={sales.daily.map((item) => item.amount)} color={C.green} format={formatPrice} label="درآمد" height={280} />
        </Card>
        <Card title="تعداد سفارش روزانه" subtitle="سفارش‌های ثبت‌شده در ۳۰ روز">
          <BarsChart labels={sales.daily_orders.map((item) => shortDay(item.day))} datasets={[{ label: 'سفارش', data: sales.daily_orders.map((item) => item.count), color: C.orange }]} height={280} format={n} />
        </Card>
      </div>

      <div className="ov-grid two">
        <Card title="درآمد بر اساس دسته‌بندی" subtitle="سهم هر دسته از فروش کل">
          {categories.length ? (
            <div className="ov-split">
              <DoughnutChart labels={categories.map((item) => item.name)} values={categories.map((item) => item.revenue)} height={210} format={formatPrice} />
              <Legend vertical items={categories.map((item, index) => ({ label: item.name, value: item.revenue, color: CHART_PALETTE[index % CHART_PALETTE.length], format: formatPrice, share: pctText(item.revenue, sum(categories.map((c) => c.revenue))) }))} />
            </div>
          ) : <Empty text="داده‌ای برای دسته‌ها نیست." />}
        </Card>
        <Card title="پرفروش‌ترین کتاب‌ها" subtitle="درآمد و تعداد فروش">
          {catalog.top_products.length ? (
            <BarsChart horizontal height={280} labels={catalog.top_products.map((item) => truncate(item.title, 26))} datasets={[{ label: 'درآمد', data: catalog.top_products.map((item) => item.revenue), color: C.green }]} format={formatPrice} />
          ) : <Empty text="هنوز فروشی ثبت نشده است." />}
          <ul className="ov-ranking">
            {catalog.top_products.slice(0, 5).map((item, index) => <li key={item.id}><span>{n(index + 1)}</span><b>{item.title}</b><small>{n(item.sales)} فروش</small></li>)}
          </ul>
        </Card>
      </div>

      <div className="ov-grid three">
        <Card title="کدهای تخفیف" subtitle={`${n(coupons.active)} فعال از ${n(coupons.total)}`}>
          <Stat label="مجموع تخفیف داده‌شده" value={formatPrice(coupons.discount_given)} />
          <StatList items={[['سفارش با کد تخفیف', coupons.orders_with_coupon], ['کدهای فعال', coupons.active], ['کل کدها', coupons.total]]} />
        </Card>
        <Card title="سفارش‌ها به تفکیک وضعیت" subtitle="وضعیت فعلی سفارش‌ها">
          <BarsChart labels={sales.orders_by_status.map((item) => ORDER_LABELS[item.status] ?? item.status)} datasets={[{ label: 'تعداد', data: sales.orders_by_status.map((item) => item.count), color: C.green }]} horizontal height={190} format={n} />
        </Card>
        <Card title="کتاب‌ها" subtitle="وضعیت فهرست فروشگاه">
          <StatList items={[['منتشرشده', catalog.active], ['پیش‌نویس', catalog.draft], ['غیرفعال', catalog.inactive], ['ناموجود', catalog.unavailable], ['فایل‌های دانلود', catalog.files], ['دسته‌ها', catalog.categories]]} />
        </Card>
      </div>
    </>
  );
}

function UsersSection({ data }: { data: AdminDashboard }) {
  const { users } = data;
  const nonBuyers = Math.max(0, users.total - users.buyers);
  const unverified = Math.max(0, users.total - users.verified);
  return (
    <>
      <div className="ov-kpis">
        <Kpi tone={C.blue} icon={<Users size={19} />} label="کل خواننده‌ها" value={n(users.total)} foot={`${n(users.admins)} مدیر`} spark={users.daily_new.map((item) => item.count)} />
        <Kpi tone={C.ok} icon={<ShoppingBag size={19} />} label="خریداران" value={n(users.buyers)} foot={`${pctText(users.buyers, users.total)} از کل کاربران`} />
        <Kpi tone={C.violet} icon={<Check size={19} />} label="ایمیل تأییدشده" value={n(users.verified)} foot={`${pctText(users.verified, users.total)} از کل کاربران`} />
        <Kpi tone={C.orange} icon={<Users size={19} />} label="عضو جدید (۷ روز)" value={n(users.new_7d)} foot={`${n(users.new_30d)} عضو در ۳۰ روز`} />
      </div>
      <div className="ov-grid main">
        <Card className="span-2" title="ثبت‌نام روزانه" subtitle="تعداد خواننده‌های جدید در ۳۰ روز">
          <BarsChart labels={users.daily_new.map((item) => shortDay(item.day))} datasets={[{ label: 'عضو جدید', data: users.daily_new.map((item) => item.count), color: C.blue }]} height={280} format={n} />
        </Card>
        <Card title="خریدار یا غیرخریدار" subtitle="سهم خریداران از کل خواننده‌ها">
          <DoughnutChart labels={['خریدار', 'غیرخریدار']} values={[users.buyers, nonBuyers]} colors={[C.green, C.muted]} height={200} />
          <Legend items={[{ label: 'خریدار', value: users.buyers, color: C.green }, { label: 'غیرخریدار', value: nonBuyers, color: C.muted }]} />
        </Card>
      </div>
      <div className="ov-grid two">
        <Card title="تأیید ایمیل" subtitle="حساب‌های تأییدشده در برابر تأیید نشده">
          <DoughnutChart labels={['تأییدشده', 'تأیید نشده']} values={[users.verified, unverified]} colors={[C.violet, C.muted]} height={200} />
          <Legend items={[{ label: 'تأییدشده', value: users.verified, color: C.violet }, { label: 'تأیید نشده', value: unverified, color: C.muted }]} />
        </Card>
        <Card title="خلاصه‌ی رشد" subtitle="آمار ثبت‌نام">
          <StatList items={[['کل خواننده‌ها', users.total], ['عضو جدید ۷ روز', users.new_7d], ['عضو جدید ۳۰ روز', users.new_30d], ['خریداران', users.buyers], ['مدیران', users.admins]]} />
        </Card>
      </div>
    </>
  );
}

function ContentSection({ data, onNavigate, onModerate }: { data: AdminDashboard; onNavigate: (tab: AdminTabKey) => void; onModerate: Props['onModerate'] }) {
  const { community } = data;
  const matrix = community.by_type_status;
  const pending = community.comments_pending + community.questions_pending + community.replies_pending;
  return (
    <>
      <div className="ov-kpis">
        <Kpi tone={C.ok} icon={<MessageSquareText size={19} />} label="دیدگاه منتشرشده" value={n(community.comments_approved)} foot={`میانگین امتیاز ${(community.average_rating || 0).toLocaleString('fa-IR')} از ۵`} />
        <Kpi tone={C.violet} icon={<MessageSquareText size={19} />} label="پرسش منتشرشده" value={n(community.questions_approved)} foot={`${n(community.replies_total)} پاسخ ثبت‌شده`} />
        <Kpi tone={C.gold} icon={<Layers size={19} />} label="در انتظار تأیید" value={n(pending)} foot="نیازمند بررسی شما" />
        <Kpi tone={C.red} icon={<CircleAlert size={19} />} label="گزارش‌ها" value={n(community.reports_total)} foot={`${n(community.likes_total)} پسند · ${n(community.rejected)} ردشده`} />
      </div>
      <div className="ov-grid main">
        <Card className="span-2" title="وضعیت محتوا به تفکیک نوع" subtitle="دیدگاه، پرسش و پاسخ به تفکیک وضعیت تأیید">
          <BarsChart
            labels={matrix.map((row) => row.label)}
            stacked
            height={280}
            datasets={[
              { label: 'منتشرشده', data: matrix.map((row) => row.approved), color: C.ok },
              { label: 'در انتظار', data: matrix.map((row) => row.pending), color: C.gold },
              { label: 'ردشده', data: matrix.map((row) => row.rejected), color: C.red },
            ]}
            format={n}
          />
        </Card>
        <Card title="همه‌ی محتوا" subtitle="توزیع وضعیت">
          <DoughnutChart
            labels={community.by_status.map((item) => (item.status === 'approved' ? 'منتشرشده' : item.status === 'pending' ? 'در انتظار' : 'ردشده'))}
            values={community.by_status.map((item) => item.count)}
            colors={[C.ok, C.gold, C.red]}
            height={190}
          />
          <Legend items={community.by_status.map((item) => ({ label: item.status === 'approved' ? 'منتشرشده' : item.status === 'pending' ? 'در انتظار' : 'ردشده', value: item.count, color: item.status === 'approved' ? C.ok : item.status === 'pending' ? C.gold : C.red }))} />
        </Card>
      </div>
      <Card title="صف تأیید" subtitle="جدیدترین مواردی که منتظر تأیید هستند" action={<LinkButton onClick={() => onNavigate('reviews')}>باز کردن صف کامل</LinkButton>}>
        {data.attention.reviews.length ? (
          <ul className="ov-queue">
            {data.attention.reviews.map((item) => (
              <li key={item.id}>
                <span className={`ov-tag ${item.type}`}>{item.type === 'question' ? 'پرسش' : item.type === 'reply' ? 'پاسخ' : 'دیدگاه'}</span>
                <div className="ov-queue-body"><b>{item.author} <small>· {item.product ?? 'کتاب'}</small></b><p>{item.body}</p><small>{formatDate(item.created_at)}</small></div>
                <div className="ov-queue-actions">
                  <button className="ok" title="تأیید" onClick={() => onModerate(item.id, 'approved')}><Check size={15} /></button>
                  <button className="no" title="رد" onClick={() => onModerate(item.id, 'rejected')}><X size={15} /></button>
                </div>
              </li>
            ))}
          </ul>
        ) : <Empty text="صف تأیید خالی است. ✨" />}
      </Card>
    </>
  );
}

function OperationsSection({ data, onNavigate }: { data: AdminDashboard; onNavigate: (tab: AdminTabKey) => void }) {
  const { support, printing, wallets } = data;
  return (
    <>
      <div className="ov-kpis">
        <Kpi tone={C.red} icon={<Headset size={19} />} label="تیکت باز" value={n(support.open + support.in_progress)} foot={`${n(support.created_7d)} تیکت جدید در ۷ روز`} />
        <Kpi tone={C.orange} icon={<Printer size={19} />} label="سفارش چاپ" value={n(printing.orders_total)} foot={`درآمد چاپ ${formatPrice(printing.revenue)}`} />
        <Kpi tone={C.teal} icon={<Printer size={19} />} label="چاپخانه‌های فعال" value={n(printing.houses_active)} foot={`${n(printing.houses_pending)} در انتظار تأیید`} />
        <Kpi tone={C.green} icon={<Wallet size={19} />} label="موجودی کیف پول‌ها" value={formatPrice(wallets.total_balance)} foot={`${n(wallets.wallets)} کیف پول فعال`} />
      </div>
      <div className="ov-grid three">
        <Card title="تیکت‌های پشتیبانی" subtitle="وضعیت فعلی درخواست‌ها" action={<LinkButton onClick={() => onNavigate('tickets')}>پاسخ‌دهی</LinkButton>}>
          <DoughnutChart labels={['باز', 'در حال بررسی', 'بسته']} values={[support.open, support.in_progress, support.closed]} colors={[C.red, C.gold, C.ok]} height={190} />
          <Legend items={[{ label: 'باز', value: support.open, color: C.red }, { label: 'در حال بررسی', value: support.in_progress, color: C.gold }, { label: 'بسته', value: support.closed, color: C.ok }]} />
        </Card>
        <Card className="span-2" title="سفارش‌های چاپ" subtitle="پیشرفت سفارش‌های کاغذی">
          <BarsChart labels={printing.orders_by_status.map((item) => PRINT_LABELS[item.status] ?? item.status)} datasets={[{ label: 'سفارش', data: printing.orders_by_status.map((item) => item.count), color: C.orange }]} height={220} format={n} />
        </Card>
      </div>
      <div className="ov-grid three">
        <Card title="چاپخانه‌ها و همکاری" subtitle="شبکه‌ی چاپ فروشگاه">
          <StatList items={[['کل چاپخانه‌ها', printing.houses_total], ['فعال', printing.houses_active], ['در انتظار تأیید', printing.houses_pending], ['درخواست همکاری باز', printing.contracts_pending], ['همکاری تأییدشده', printing.contracts_approved]]} />
        </Card>
        <Card title="کیف پول" subtitle="جریان مالی کیف پول خواننده‌ها">
          <Stat label="مجموع موجودی" value={formatPrice(wallets.total_balance)} />
          <StatList items={[['واریز موفق', formatPrice(wallets.deposits_completed)], ['خرید از کیف پول', formatPrice(wallets.purchases_completed)], ['تراکنش در انتظار', wallets.pending_transactions]]} />
        </Card>
        <Card title="پشتیبانی" subtitle="جمع‌بندی">
          <StatList items={[['تیکت ۷ روز اخیر', support.created_7d], ['تیکت بسته‌شده', support.closed], ['در حال بررسی', support.in_progress]]} />
        </Card>
      </div>
    </>
  );
}

/* ------------------------------------------------------------ building blocks */

const CHART_PALETTE = ['#215446', '#e97b51', '#4a7fb5', '#d49b54', '#7a6bb5', '#3f9d8f', '#c66b60', '#9bb7a5'];

function truncate(text: string, length: number) {
  return text.length > length ? `${text.slice(0, length)}…` : text;
}

function Kpi({ tone, icon, label, value, foot, spark }: { tone: string; icon: ReactNode; label: string; value: string; foot: ReactNode; spark?: number[] }) {
  return (
    <article className="ov-kpi" style={{ ['--kpi' as string]: tone }}>
      <div className="ov-kpi-top">
        <span className="ov-kpi-icon">{icon}</span>
        <small>{label}</small>
      </div>
      <b>{value}</b>
      <span className="ov-kpi-foot">{foot}</span>
      {spark && spark.length > 1 && <Sparkline values={spark} color={tone} />}
    </article>
  );
}

function Growth({ value }: { value: number | null }) {
  if (value === null) return <span>بدون مقایسه‌ی قبلی</span>;
  const up = value >= 0;
  return <span className={`ov-growth ${up ? 'up' : 'down'}`}>{up ? <TrendingUp size={12} /> : <TrendingDown size={12} />} {Math.abs(value).toLocaleString('fa-IR')}٪ نسبت به ۳۰ روز قبل</span>;
}

function Card({ title, subtitle, action, className, children }: { title: string; subtitle?: string; action?: ReactNode; className?: string; children: ReactNode }) {
  return (
    <section className={`ov-card ${className ?? ''}`}>
      <header>
        <div><h3>{title}</h3>{subtitle && <p>{subtitle}</p>}</div>
        {action}
      </header>
      {children}
    </section>
  );
}

function Segment<T extends string | number>({ value, options, onChange }: { value: T; options: [T, string][]; onChange: (value: T) => void }) {
  return (
    <div className="ov-segment">
      {options.map(([key, label]) => <button key={String(key)} className={value === key ? 'active' : ''} onClick={() => onChange(key)}>{label}</button>)}
    </div>
  );
}

function LinkButton({ onClick, children }: { onClick: () => void; children: ReactNode }) {
  return <button className="ov-link" onClick={onClick}>{children} <ArrowLeft size={13} /></button>;
}

function Stat({ label, value }: { label: string; value: string }) {
  return <div className="ov-stat"><small>{label}</small><b>{value}</b></div>;
}

function Legend({ items, vertical }: { items: { label: string; value: number; color: string; format?: (value: number) => string; share?: string }[]; vertical?: boolean }) {
  return (
    <ul className={`ov-legend ${vertical ? 'vertical' : ''}`}>
      {items.map((item) => (
        <li key={item.label}>
          <i style={{ background: item.color }} />
          <span>{item.label}</span>
          <b>{item.format ? item.format(item.value) : n(item.value)}</b>
          {item.share && <em>{item.share}</em>}
        </li>
      ))}
    </ul>
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

/* ------------------------------------------------------------ demo data */

const demoDays = (count: number, base: (index: number) => number) => Array.from({ length: count }, (_, index) => {
  const date = new Date();
  date.setDate(date.getDate() - (count - 1 - index));
  return { day: date.toISOString().slice(0, 10), amount: base(index), count: Math.max(0, Math.round(base(index) / 900000)) };
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
      by_type_status: [
        { type: 'comment', label: 'دیدگاه', approved: 214, pending: 6, rejected: 7 },
        { type: 'question', label: 'پرسش', approved: 58, pending: 3, rejected: 2 },
        { type: 'reply', label: 'پاسخ', approved: 119, pending: 2, rejected: 2 },
      ],
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
