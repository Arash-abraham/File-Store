import { useEffect, useMemo, useState, type FormEvent } from 'react';
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { ArrowLeft, ArrowUpLeft, BookOpen, Check, CircleHelp, Download, LayoutDashboard, LibraryBig, LogOut, Package, Printer, Settings2, Ticket, UserRound, Wallet, X } from 'lucide-react';
import { Link, useSearchParams } from 'react-router-dom';
import { api, apiErrorMessage, demoMode } from '../lib/api';
import { demoProducts } from '../data/demo';
import { formatDate, formatPrice } from '../lib/format';
import SupportTickets from '../components/support/SupportTickets';
import { cancelPrintOrder, fetchPrintOrders, printStatusLabels } from '../lib/print';
import { demoPrintOrders } from '../data/demo';
import type { Order, PrintOrder } from '../types';
import { useStore } from '../StoreContext';

const accountTabs = [
  { id: 'overview', label: 'نمای کلی', icon: LayoutDashboard },
  { id: 'library', label: 'کتابخانه‌ی من', icon: LibraryBig },
  { id: 'orders', label: 'سفارش‌ها', icon: Package },
  { id: 'prints', label: 'سفارش‌های چاپی', icon: Printer },
  { id: 'wallet', label: 'کیف پول', icon: Wallet },
  { id: 'support', label: 'پشتیبانی', icon: Ticket },
  { id: 'profile', label: 'اطلاعات حساب', icon: Settings2 },
];

function demoOverview() {
  const linux = demoProducts[0];
  return {
    stats: { orders_count: 2, paid_orders_count: 1, total_spent: linux.price, downloads_count: 1 },
    wallet: { balance: 1250000, transactions: [{ id: 1, type: 'deposit', amount: 1250000, description: 'شارژ اولیه‌ی کیف پول', status: 'completed', created_at: new Date().toISOString() }] },
    recent_orders: [
      { id: 2401, status: 'paid', total_amount: linux.price, discount_amount: 0, final_amount: linux.price, paid_at: new Date().toISOString(), created_at: new Date().toISOString(), items: [{ id: 1, product_id: linux.id, title: linux.title, unit_price: linux.price, quantity: 1, subtotal: linux.price }] },
      { id: 2398, status: 'pending', total_amount: 590000, discount_amount: 0, final_amount: 590000, created_at: new Date(Date.now() - 86400000 * 2).toISOString(), items: [{ id: 2, product_id: 2, title: demoProducts[1].title, unit_price: demoProducts[1].price, quantity: 1, subtotal: demoProducts[1].price }] },
    ] as Order[],
    open_tickets: 1,
  };
}

export default function AccountPage() {
  const { user, setLoginOpen, logout, setNotice, updateUser } = useStore();
  const queryClient = useQueryClient();
  const [params, setParams] = useSearchParams();
  const activeTab = params.get('tab') ?? 'overview';
  const [profileName, setProfileName] = useState(user?.name ?? '');
  const [profilePhone, setProfilePhone] = useState(user?.phone ?? '');
  const [depositAmount, setDepositAmount] = useState('500000');
  const [formError, setFormError] = useState('');
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    setProfileName(user?.name ?? '');
    setProfilePhone(user?.phone ?? '');
  }, [user?.id, user?.name, user?.phone]);

  useEffect(() => {
    const status = params.get('status');
    if (status === 'success') setNotice('کیف پول با موفقیت شارژ شد.');
    if (status === 'failed') setNotice('پرداخت شارژ کیف پول تأیید نشد.');
    if (status === 'cancelled') setNotice('پرداخت شارژ لغو شد.');
  }, [params, setNotice]);

  const overviewQuery = useQuery({
    queryKey: ['account-overview', user?.id],
    enabled: !!user,
    queryFn: async () => demoMode ? demoOverview() : (await api.get('/account/overview')).data.data,
  });
  const ordersQuery = useQuery({
    queryKey: ['account-orders', user?.id],
    enabled: !!user && activeTab === 'orders',
    queryFn: async () => demoMode ? demoOverview().recent_orders : (await api.get('/account/orders')).data.data as Order[],
  });
  const downloadsQuery = useQuery({
    queryKey: ['account-downloads', user?.id],
    enabled: !!user && activeTab === 'library',
    queryFn: async () => demoMode ? [{ id: 1, name: 'نسخه‌ی دیجیتال کتاب', type: 'PDF', size: 'PDF', product: { title: demoProducts[0].title, slug: demoProducts[0].slug, cover_url: demoProducts[0].cover_url } }] : (await api.get('/account/downloads')).data.data,
  });
  const printOrdersQuery = useQuery<PrintOrder[]>({
    queryKey: ['print-orders', user?.id],
    enabled: !!user && activeTab === 'prints',
    queryFn: async () => demoMode
      ? demoPrintOrders as unknown as PrintOrder[]
      : fetchPrintOrders(),
  });
  const cancelPrint = useMutation({
    mutationFn: (id: number) => cancelPrintOrder(id),
    onSuccess: async (order) => {
      setNotice(`سفارش چاپی #${order.id} لغو شد.`);
      await queryClient.invalidateQueries({ queryKey: ['print-orders'] });
    },
    onError: (reason) => setNotice(apiErrorMessage(reason, 'لغو سفارش چاپی انجام نشد.')),
  });
  const walletQuery = useQuery({
    queryKey: ['wallet', user?.id],
    enabled: !!user && activeTab === 'wallet',
    queryFn: async () => {
      if (demoMode) return demoOverview().wallet;
      const wallet = (await api.get('/wallet')).data.data;
      const transactions = Array.isArray(wallet.transactions) ? wallet.transactions : wallet.transactions?.data ?? [];
      return { ...wallet, transactions };
    },
  });

  const recentOrders = useMemo(() => overviewQuery.data?.recent_orders ?? [], [overviewQuery.data]);

  function selectTab(tab: string) {
    setParams(tab === 'overview' ? {} : { tab });
    setFormError('');
  }


  async function saveProfile(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setFormError('');
    setSubmitting(true);
    try {
      if (demoMode) {
        updateUser({ ...user!, name: profileName.trim(), phone: profilePhone || null });
        setNotice('اطلاعات آزمایشی ذخیره شد.');
      } else {
        const response = await api.put('/account/profile', { name: profileName, phone: profilePhone || null });
        updateUser(response.data.data.user);
        setNotice('اطلاعات حساب ذخیره شد.');
      }
    } catch (error) {
      setFormError(apiErrorMessage(error, 'ذخیره‌ی اطلاعات انجام نشد.'));
    } finally {
      setSubmitting(false);
    }
  }

  async function deposit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setFormError('');
    setSubmitting(true);
    try {
      if (demoMode) {
        setNotice('در محیط آزمایشی، شارژ واقعی کیف پول انجام نمی‌شود.');
      } else {
        const response = await api.post('/wallet/deposits', { amount: Number(depositAmount) });
        window.location.assign(response.data.data.payment_url);
      }
    } catch (error) {
      setFormError(apiErrorMessage(error, 'ایجاد درخواست شارژ انجام نشد.'));
    } finally {
      setSubmitting(false);
    }
  }

  if (!user) return <section className="container account-locked section-pad"><span className="account-lock-icon"><UserRound size={29} /></span><span className="eyebrow">همه‌چیز در یک کتابخانه</span><h1>کتاب‌هایت اینجا<br /><em>منتظر تو هستند.</em></h1><p>برای دیدن سفارش‌ها، فایل‌های خریداری‌شده و کیف پول وارد حساب کاربری‌ات شو.</p><button className="button button-primary" onClick={() => setLoginOpen(true)}>ورود به حساب <ArrowLeft size={16} /></button></section>;

  const overview = overviewQuery.data ?? (demoMode ? demoOverview() : null);
  const tabLabel = accountTabs.find((tab) => tab.id === activeTab)?.label ?? 'حساب من';

  return (
    <section className="container account-page section-pad">
      <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>حساب کاربری</b></div>
      <div className="account-welcome"><div><span className="eyebrow">کتابخانه‌ی شخصی تو</span><h1>سلام، {user.name.split(' ')[0]} <span>✳</span></h1><p>اینجا جایی است که مسیر خواندنت ادامه پیدا می‌کند.</p></div><button className="account-logout" onClick={() => void logout()}><LogOut size={16} /> خروج از حساب</button></div>
      <div className="account-layout">
        <aside className="account-sidebar"><div className="account-user-card"><span className="account-large-avatar">{user.name.slice(0, 1)}</span><span><b>{user.name}</b><small>{user.email}</small></span></div><nav>{accountTabs.map(({ id, label, icon: Icon }) => <button key={id} className={activeTab === id ? 'active' : ''} onClick={() => selectTab(id)}><Icon size={17} /> {label}{activeTab === id && <ArrowUpLeft size={15} />}</button>)}</nav><div className="account-sidebar-help"><CircleHelp size={17} /><span>نیاز به راهنمایی داری؟</span><Link to="/faq">مرکز راهنما <ArrowLeft size={13} /></Link></div>{user.role === 'admin' && <Link to="/admin" className="admin-shortcut">ورود به پنل مدیریت <ArrowLeft size={14} /></Link>}</aside>
        <div className="account-content">
          <div className="account-content-heading"><div><span className="eyebrow">حساب من / {tabLabel}</span><h2>{tabLabel}</h2></div>{activeTab === 'overview' && <span className="account-status"><Check size={14} /> حساب فعال</span>}</div>
          {formError && <div className="form-error account-form-error">{formError}<button onClick={() => setFormError('')}><X size={14} /></button></div>}

          {activeTab === 'overview' && overview && <>
            <div className="account-stat-grid"><article><span className="stat-icon violet"><Package size={18} /></span><small>کل سفارش‌ها</small><b>{overview.stats.orders_count.toLocaleString('fa-IR')}</b><span>سفارش ثبت‌شده</span></article><article><span className="stat-icon green"><LibraryBig size={18} /></span><small>کتاب‌های من</small><b>{overview.stats.downloads_count.toLocaleString('fa-IR')}</b><span>فایل آماده‌ی دریافت</span></article><article><span className="stat-icon orange"><Wallet size={18} /></span><small>موجودی کیف پول</small><b>{formatPrice(overview.wallet.balance)}</b><span>برای خرید بعدی</span></article></div>
            <div className="account-panel recent-orders-panel"><div className="account-panel-heading"><div><h3>آخرین سفارش‌ها</h3><p>خریدهای اخیر تو</p></div><button className="text-link" onClick={() => selectTab('orders')}>همه‌ی سفارش‌ها <ArrowLeft size={14} /></button></div><OrderList orders={recentOrders as Order[]} /></div>
            <div className="account-quick-grid"><button onClick={() => selectTab('library')}><LibraryBig size={19} /><b>رفتن به کتابخانه</b><span>کتاب‌هایی که خریده‌ای</span><ArrowLeft size={15} /></button><button onClick={() => selectTab('wallet')}><Wallet size={19} /><b>جزئیات کیف پول</b><span>موجودی و تراکنش‌ها</span><ArrowLeft size={15} /></button></div>
          </>}

          {activeTab === 'library' && <div className="account-panel"><div className="account-panel-heading"><div><h3>کتابخانه‌ی من</h3><p>هر خرید، برای همیشه در دسترس تو</p></div></div>{downloadsQuery.isLoading ? <div className="loading-inline">در حال بارگذاری کتابخانه…</div> : downloadsQuery.data?.length ? <div className="library-list">{downloadsQuery.data.map((file: any) => <article key={file.id} className="library-item"><div className="library-cover">{file.product?.cover_url ? <img src={file.product.cover_url} alt="" /> : <BookOpen size={22} />}</div><div className="library-info"><b>{file.product?.title ?? file.name}</b><small>{file.name} · {file.type ?? 'PDF'} · {file.size ?? 'نسخه‌ی دیجیتال'}</small></div>{demoMode ? <button className="button button-small button-outline" onClick={() => setNotice('دانلود در حالت نمایشی شبیه‌سازی شده است.')}><Download size={15} /> دریافت</button> : <a className="button button-small button-outline" href={file.download_url} target="_blank" rel="noreferrer"><Download size={15} /> دریافت</a>}</article>)}</div> : <div className="empty-inline"><LibraryBig size={26} /><b>هنوز کتابی اینجا نیست.</b><Link to="/catalog">رفتن به قفسه‌ی کتاب‌ها <ArrowLeft size={14} /></Link></div>}</div>}

          {activeTab === 'prints' && <div className="account-panel"><div className="account-panel-heading"><div><h3>سفارش‌های چاپی</h3><p>نسخه‌های کاغذی که سفارش داده‌ای</p></div><Link className="text-link" to="/print">فهرست چاپخانه‌ها <ArrowLeft size={14} /></Link></div>{printOrdersQuery.isLoading ? <div className="loading-inline">در حال بارگذاری…</div> : printOrdersQuery.data?.length ? <div className="print-order-list">{printOrdersQuery.data.map((order) => <article className="print-order-row" key={order.id}><div className="print-order-cover">{order.product.cover_url ? <img src={order.product.cover_url} alt="" /> : <BookOpen size={20} />}</div><div className="print-order-main"><b>{order.product.title}</b><small>{order.print_house.name} · {order.print_house.city} · {order.quantity.toLocaleString('fa-IR')} نسخه</small><span className="print-order-specs">{order.paper_label} · صحافی {order.binding_label}{order.color_print ? ' · رنگی' : ''}{order.digital_owner ? ' · تخفیف دارنده‌ی نسخه‌ی دیجیتال' : ''}</span>{order.tracking_code && <span className="print-order-tracking">کد رهگیری: {order.tracking_code}</span>}</div><div className="print-order-side"><span className={`print-status ${order.status}`}>{printStatusLabels[order.status] ?? order.status}</span><b>{formatPrice(order.total_price)}</b><small>تحویل حدود {order.delivery_days.toLocaleString('fa-IR')} روز · {formatDate(order.created_at)}</small>{order.can_cancel && <button className="print-order-cancel" disabled={cancelPrint.isPending} onClick={() => cancelPrint.mutate(order.id)}>لغو سفارش</button>}</div></article>)}</div> : <div className="empty-inline"><Printer size={26} /><b>هنوز نسخه‌ی کاغذی سفارش نداده‌ای.</b><p>از صفحه‌ی هر کتاب می‌توانی نسخه‌ی چاپی سفارش بدهی.</p><Link to="/print">دیدن چاپخانه‌ها <ArrowLeft size={14} /></Link></div>}</div>}

          {activeTab === 'orders' && <div className="account-panel"><div className="account-panel-heading"><div><h3>تاریخچه‌ی سفارش‌ها</h3><p>همه‌ی خریدها و وضعیت پرداخت</p></div></div>{ordersQuery.isLoading ? <div className="loading-inline">در حال بارگذاری…</div> : <OrderList orders={ordersQuery.data ?? []} />}</div>}

          {activeTab === 'wallet' && <div className="wallet-page-grid"><div className="wallet-balance-card"><span className="wallet-balance-label"><Wallet size={17} /> موجودی فعلی</span><strong>{formatPrice(walletQuery.data?.balance ?? overview?.wallet.balance ?? 0)}</strong><p>برای خرید کتاب بعدی، همیشه آماده‌ای.</p><form className="wallet-deposit-form" onSubmit={(event) => void deposit(event)}><label>مبلغ شارژ به تومان<input type="number" min="10000" step="10000" value={depositAmount} onChange={(event) => setDepositAmount(event.target.value)} /></label><button className="button button-light" disabled={submitting}>شارژ از درگاه <ArrowLeft size={15} /></button></form></div><div className="account-panel wallet-history"><div className="account-panel-heading"><div><h3>تراکنش‌ها</h3><p>حرکت موجودی کیف پول</p></div></div><div className="transaction-list">{(walletQuery.data?.transactions ?? []).map((transaction: any) => <div key={transaction.id}><span className="transaction-mark"><Wallet size={15} /></span><span><b>{transaction.description ?? 'تراکنش کیف پول'}</b><small>{formatDate(transaction.created_at)}</small></span><strong className={transaction.type === 'deposit' ? 'positive' : ''}>{transaction.type === 'deposit' ? '+' : '−'}{formatPrice(transaction.amount)}</strong></div>)}</div></div></div>}

          {activeTab === 'support' && <SupportTickets />}

          {activeTab === 'profile' && <div className="account-panel profile-panel"><div className="account-panel-heading"><div><h3>اطلاعات حساب</h3><p>اطلاعاتت را به‌روز نگه دار.</p></div></div><form className="profile-form" onSubmit={(event) => void saveProfile(event)}><label className="field-label">نام نمایشی<input value={profileName} onChange={(event) => setProfileName(event.target.value)} required /></label><label className="field-label">ایمیل<input type="email" value={user.email} disabled /><small>برای تغییر ایمیل با پشتیبانی تماس بگیر.</small></label><label className="field-label">شماره‌ی تماس<input value={profilePhone} onChange={(event) => setProfilePhone(event.target.value)} placeholder="۰۹۱۲۱۲۳۴۵۶۷" /></label><button className="button button-primary" disabled={submitting}>ذخیره‌ی تغییرات <Check size={15} /></button></form></div>}
        </div>
      </div>
    </section>
  );
}

function OrderList({ orders }: { orders: Order[] }) {
  if (!orders.length) return <div className="empty-inline"><Package size={24} /><span>هنوز سفارشی ثبت نشده است.</span></div>;
  return <div className="order-list"><div className="order-list-head"><span>شماره</span><span>تاریخ</span><span>مبلغ</span><span>وضعیت</span></div>{orders.map((order) => <div className="order-row" key={order.id}><b>#{order.id.toLocaleString('fa-IR')}</b><span>{formatDate(order.created_at)}</span><strong>{formatPrice(order.final_amount)}</strong><span className={`order-status ${order.status}`}>{order.status === 'paid' ? 'پرداخت‌شده' : order.status === 'pending' ? 'در انتظار' : 'ناموفق'}</span></div>)}</div>;
}
