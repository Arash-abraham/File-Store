import { useMemo, useState } from 'react';
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { ArrowLeft, Check, ClipboardList, MessageSquareText, Printer, RefreshCw, Ticket, X } from 'lucide-react';
import { useStore } from '../../StoreContext';
import { api, apiErrorMessage, demoMode } from '../../lib/api';
import { formatDate, formatPrice } from '../../lib/format';
import type { SupportTicket } from '../../types';

type TaskReview = { id: number; body: string; status: string; type?: string; created_at?: string; user?: { name?: string }; product?: { title?: string } };
type PrintOrder = { id: number; status: string; product_title: string; customer: string; quantity: number; total_price: number; created_at?: string };
type PrintContract = { id: number; status: string; company_name: string; contact_name: string; city: string; created_at?: string };
type Task = { id: string; kind: 'ticket' | 'review' | 'print-order' | 'contract'; title: string; detail: string; date?: string; target: 'tickets' | 'reviews' | 'printing'; sourceId: number };
type Props = { onNavigate: (target: 'tickets' | 'reviews') => void };

const demoQueue: Task[] = [
  { id: 'ticket-1', kind: 'ticket', title: 'لینک دانلود کتاب باز نمی‌شود', detail: 'سارا بهرامی · درخواست پشتیبانی', date: new Date().toISOString(), target: 'tickets', sourceId: 1 },
  { id: 'review-1', kind: 'review', title: 'برای شروع کار با لینوکس خیلی روان و مفید بود.', detail: 'مینا سلیمانی · مبانی لینوکس', date: new Date(Date.now() - 3600000).toISOString(), target: 'reviews', sourceId: 1 },
  { id: 'print-order-1', kind: 'print-order', title: 'سفارش چاپ «طراحی با داده»', detail: 'امیرحسین کاظمی · ۲ نسخه', date: new Date(Date.now() - 7200000).toISOString(), target: 'printing', sourceId: 1 },
  { id: 'contract-1', kind: 'contract', title: 'درخواست همکاری چاپخانه آفتاب', detail: 'علی رضایی · تهران', date: new Date(Date.now() - 86400000).toISOString(), target: 'printing', sourceId: 1 },
];

const kindLabel: Record<Task['kind'], string> = { ticket: 'پشتیبانی', review: 'محتوا', 'print-order': 'سفارش چاپ', contract: 'همکاری چاپخانه' };
const kindIcon = { ticket: Ticket, review: MessageSquareText, 'print-order': Printer, contract: ClipboardList };

export default function AdminTaskCenter({ onNavigate }: Props) {
  const { setNotice } = useStore();
  const queryClient = useQueryClient();
  const [filter, setFilter] = useState<'all' | Task['kind']>('all');
  const [search, setSearch] = useState('');
  const [demoTasks, setDemoTasks] = useState(demoQueue);

  const ticketsQuery = useQuery<SupportTicket[]>({
    queryKey: ['admin-task-tickets'], enabled: !demoMode,
    queryFn: async () => {
      const [open, progress] = await Promise.all([
        api.get('/admin/tickets', { params: { status: 'open' } }),
        api.get('/admin/tickets', { params: { status: 'in_progress' } }),
      ]);
      return [...(open.data.data as SupportTicket[]), ...(progress.data.data as SupportTicket[])];
    },
  });
  const reviewsQuery = useQuery<TaskReview[]>({
    queryKey: ['admin-task-reviews'], enabled: !demoMode,
    queryFn: async () => (await api.get('/admin/reviews', { params: { status: 'pending' } })).data.data as TaskReview[],
  });
  const printOrdersQuery = useQuery<PrintOrder[]>({
    queryKey: ['admin-task-print-orders'], enabled: !demoMode,
    queryFn: async () => (await api.get('/admin/print-orders', { params: { status: 'pending' } })).data.data as PrintOrder[],
  });
  const contractsQuery = useQuery<PrintContract[]>({
    queryKey: ['admin-task-contracts'], enabled: !demoMode,
    queryFn: async () => (await api.get('/admin/print-contracts')).data.data as PrintContract[],
  });

  const updateTask = useMutation({
    mutationFn: async ({ task, status }: { task: Task; status: string }) => {
      if (task.kind === 'review') return api.put(`/admin/reviews/${task.sourceId}`, { status });
      if (task.kind === 'print-order') return api.put(`/admin/print-orders/${task.sourceId}`, { status });
      if (task.kind === 'contract') return api.put(`/admin/print-contracts/${task.sourceId}`, { status });
      return null;
    },
    onSuccess: async (_response, { task }) => {
      await Promise.all([
        queryClient.invalidateQueries({ queryKey: ['admin-task-reviews'] }),
        queryClient.invalidateQueries({ queryKey: ['admin-task-print-orders'] }),
        queryClient.invalidateQueries({ queryKey: ['admin-task-contracts'] }),
        queryClient.invalidateQueries({ queryKey: ['admin-reviews'] }),
        queryClient.invalidateQueries({ queryKey: ['admin-dashboard'] }),
      ]);
      setNotice(task.kind === 'review' ? 'وضعیت محتوا به‌روز شد.' : 'وضعیت مورد به‌روز شد.');
    },
    onError: (error) => setNotice(apiErrorMessage(error, 'به‌روزرسانی مورد انجام نشد.')),
  });

  const tasks = useMemo(() => {
    if (demoMode) return demoTasks;
    const ticketTasks: Task[] = (ticketsQuery.data ?? []).map((ticket) => ({
      id: `ticket-${ticket.id}`, kind: 'ticket', title: ticket.subject,
      detail: `${ticket.user?.name ?? 'کاربر'} · ${ticket.status === 'open' ? 'در انتظار پاسخ' : 'در حال پیگیری'}`,
      date: ticket.updated_at ?? ticket.created_at ?? undefined, target: 'tickets', sourceId: ticket.id,
    }));
    const reviewTasks: Task[] = (reviewsQuery.data ?? []).map((review) => ({
      id: `review-${review.id}`, kind: 'review', title: review.body,
      detail: `${review.user?.name ?? 'کاربر'} · ${review.product?.title ?? (review.type === 'question' ? 'پرسش' : 'دیدگاه')}`,
      date: review.created_at, target: 'reviews', sourceId: review.id,
    }));
    const orderTasks: Task[] = (printOrdersQuery.data ?? []).map((order) => ({
      id: `print-order-${order.id}`, kind: 'print-order', title: `سفارش چاپ «${order.product_title}»`,
      detail: `${order.customer} · ${order.quantity.toLocaleString('fa-IR')} نسخه · ${formatPrice(order.total_price)}`,
      date: order.created_at, target: 'printing', sourceId: order.id,
    }));
    const contractTasks: Task[] = (contractsQuery.data ?? []).filter((contract) => ['pending', 'reviewing'].includes(contract.status)).map((contract) => ({
      id: `contract-${contract.id}`, kind: 'contract', title: `درخواست همکاری ${contract.company_name}`,
      detail: `${contract.contact_name} · ${contract.city}`, date: contract.created_at,
      target: 'printing', sourceId: contract.id,
    }));
    return [...ticketTasks, ...reviewTasks, ...orderTasks, ...contractTasks];
  }, [ticketsQuery.data, reviewsQuery.data, printOrdersQuery.data, contractsQuery.data, demoTasks]);

  const visibleTasks = tasks.filter((task) => (filter === 'all' || task.kind === filter)
    && (!search.trim() || `${task.title} ${task.detail}`.toLocaleLowerCase().includes(search.trim().toLocaleLowerCase())))
    .sort((a, b) => (a.date ? new Date(a.date).getTime() : 0) - (b.date ? new Date(b.date).getTime() : 0));
  const isLoading = !demoMode && [ticketsQuery, reviewsQuery, printOrdersQuery, contractsQuery].some((query) => query.isLoading);
  const isError = !demoMode && [ticketsQuery, reviewsQuery, printOrdersQuery, contractsQuery].some((query) => query.isError);
  const refreshTasks = () => Promise.all([
    queryClient.invalidateQueries({ queryKey: ['admin-task-tickets'] }),
    queryClient.invalidateQueries({ queryKey: ['admin-task-reviews'] }),
    queryClient.invalidateQueries({ queryKey: ['admin-task-print-orders'] }),
    queryClient.invalidateQueries({ queryKey: ['admin-task-contracts'] }),
  ]);
  const applyTaskAction = (task: Task, status: string) => {
    if (demoMode) {
      setDemoTasks((current) => current.filter((item) => item.id !== task.id));
      setNotice('مورد نمایشی از فهرست کارها کنار رفت.');
      return;
    }
    updateTask.mutate({ task, status });
  };

  return <section className="admin-panel adm-task-center">
    <header className="admin-panel-heading">
      <div><h2>مرکز کارهای مدیریتی</h2><p>موارد نیازمند پیگیری را یک‌جا ببینید و کارهای سریع را همین‌جا انجام دهید.</p></div>
      <button className="button button-outline button-small" onClick={() => void refreshTasks()}><RefreshCw size={15} /> تازه‌سازی</button>
    </header>
    <div className="adm-task-summary">
      <span><b>{tasks.length.toLocaleString('fa-IR')}</b> مورد باز</span>
      <span>{tasks.filter((task) => task.kind === 'ticket').length.toLocaleString('fa-IR')} پشتیبانی</span>
      <span>{tasks.filter((task) => task.kind === 'review').length.toLocaleString('fa-IR')} محتوا</span>
      <span>{tasks.filter((task) => task.target === 'printing').length.toLocaleString('fa-IR')} چاپ و همکاری</span>
    </div>
    <div className="admin-list-toolbar adm-task-toolbar">
      <label><MessageSquareText size={16} /><input value={search} onChange={(event) => setSearch(event.target.value)} placeholder="جست‌وجو در کارهای نیازمند پیگیری…" /></label>
      <div className="admin-segment">{([['all', 'همه'], ['ticket', 'پشتیبانی'], ['review', 'محتوا'], ['print-order', 'چاپ'], ['contract', 'همکاری']] as [typeof filter, string][]).map(([value, label]) => <button key={value} className={filter === value ? 'active' : ''} onClick={() => setFilter(value)}>{label}</button>)}</div>
    </div>
    {isError && <div className="adm-task-error">دریافت بخشی از کارها با خطا روبه‌رو شد. اتصال را بررسی و دوباره تازه‌سازی کنید.</div>}
    {isLoading ? <p className="ov-loading">در حال جمع‌آوری کارهای مدیریتی…</p> : visibleTasks.length ? <div className="adm-task-list">{visibleTasks.map((task) => {
      const Icon = kindIcon[task.kind];
      return <article className="adm-task-item" key={task.id}>
        <span className={`adm-task-icon ${task.kind}`}><Icon size={18} /></span>
        <div className="adm-task-copy"><div className="adm-task-meta"><span>{kindLabel[task.kind]}</span>{task.date && <time>{formatDate(task.date)}</time>}</div><b>{task.title}</b><small>{task.detail}</small></div>
        <div className="adm-task-actions">
          {task.kind === 'review' && <><button className="review-action approve" title="تأیید و انتشار" disabled={updateTask.isPending} onClick={() => applyTaskAction(task, 'approved')}><Check size={15} /></button><button className="review-action reject" title="رد محتوا" disabled={updateTask.isPending} onClick={() => applyTaskAction(task, 'rejected')}><X size={15} /></button></>}
          {task.kind === 'print-order' && <button className="button button-outline button-small" disabled={updateTask.isPending} onClick={() => applyTaskAction(task, 'confirmed')}>تأیید سفارش</button>}
          {task.kind === 'contract' && <><button className="button button-outline button-small" disabled={updateTask.isPending} onClick={() => applyTaskAction(task, 'reviewing')}>در حال بررسی</button><button className="button button-primary button-small" disabled={updateTask.isPending} onClick={() => applyTaskAction(task, 'approved')}>تأیید</button></>}
          {task.kind === 'ticket' && <button className="button button-outline button-small" onClick={() => onNavigate('tickets')}>مشاهده <ArrowLeft size={14} /></button>}
        </div>
      </article>;
    })}</div> : <div className="adm-task-empty"><ClipboardList size={28} /><b>همه‌چیز مرتب است</b><span>{search || filter !== 'all' ? 'موردی با این جست‌وجو یا فیلتر پیدا نشد.' : 'در حال حاضر کار بازی برای پیگیری ندارید.'}</span></div>}
    {demoMode && <small className="adm-task-demo-note">نمونه‌داده‌ها در حالت نمایشی هستند؛ برای تغییرات واقعی، حالت دمو را خاموش کنید.</small>}
  </section>;
}
