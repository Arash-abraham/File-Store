import { useState } from 'react';
import { useQuery, useQueryClient } from '@tanstack/react-query';
import { Search, Send, Ticket } from 'lucide-react';
import { useStore } from '../../StoreContext';
import { api, apiErrorMessage, demoMode } from '../../lib/api';
import { formatDate } from '../../lib/format';
import type { SupportTicket, SupportTicketStatus } from '../../types';
import TicketMessages from '../support/TicketMessages';
import { TICKET_STATUS_LABEL } from '../support/statusLabels';

type StatusFilter = 'all' | SupportTicketStatus;

const STATUS_FILTERS: [StatusFilter, string][] = [
  ['all', 'همه'],
  ['open', 'در انتظار'],
  ['in_progress', 'در حال پیگیری'],
  ['closed', 'بسته'],
];

const toFa = (value: number) => value.toLocaleString('fa-IR');

/** Support inbox: a list of tickets on one side, the full two-way thread on the other. */
export default function AdminTickets({ initialSearch = '' }: { initialSearch?: string }) {
  const { setNotice } = useStore();
  const queryClient = useQueryClient();
  const [statusFilter, setStatusFilter] = useState<StatusFilter>('all');
  const [search, setSearch] = useState(initialSearch);
  const [selectedId, setSelectedId] = useState<number | null>(null);
  const [reply, setReply] = useState('');
  const [sending, setSending] = useState(false);
  const [demoList, setDemoList] = useState<SupportTicket[]>(demoTickets);

  const listQuery = useQuery<SupportTicket[]>({
    queryKey: ['admin-tickets', statusFilter, search],
    enabled: !demoMode,
    queryFn: async () => {
      const params: Record<string, string> = {};
      if (statusFilter !== 'all') params.status = statusFilter;
      if (search.trim()) params.search = search.trim();
      return (await api.get('/admin/tickets', { params })).data.data as SupportTicket[];
    },
  });

  const detailQuery = useQuery<SupportTicket>({
    queryKey: ['admin-ticket', selectedId],
    enabled: !demoMode && selectedId !== null,
    queryFn: async () => (await api.get(`/admin/tickets/${selectedId}`)).data.data as SupportTicket,
  });

  const tickets: SupportTicket[] = demoMode
    ? demoList.filter((ticket) => (statusFilter === 'all' || ticket.status === statusFilter)
      && (!search.trim() || `${ticket.subject} ${ticket.user?.email ?? ''} ${ticket.user?.name ?? ''}`.includes(search.trim())))
    : listQuery.data ?? [];
  const selected = demoMode ? demoList.find((ticket) => ticket.id === selectedId) : detailQuery.data;
  const activeId = selected?.id ?? null;

  async function sendReply(status?: SupportTicketStatus) {
    if (activeId === null || reply.trim().length < 2) return;
    const body = reply.trim();
    setSending(true);
    try {
      if (demoMode) {
        const nextStatus = status ?? (demoList.find((t) => t.id === activeId)?.status === 'open' ? 'in_progress' : undefined);
        setDemoList((list) => list.map((ticket) => ticket.id !== activeId ? ticket : {
          ...ticket,
          status: nextStatus ?? ticket.status,
          messages: [...(ticket.messages ?? []), { id: Date.now(), body, is_staff: true, author: 'پشتیبانی فایل‌استور', created_at: new Date().toISOString() }],
        }));
      } else {
        await api.post(`/admin/tickets/${activeId}/messages`, status ? { body, status } : { body });
        await queryClient.invalidateQueries({ queryKey: ['admin-tickets'] });
        await queryClient.invalidateQueries({ queryKey: ['admin-ticket', activeId] });
      }
      setReply('');
      setNotice('پاسخ شما برای کاربر ثبت شد.');
    } catch (error) {
      setNotice(apiErrorMessage(error, 'ارسال پاسخ انجام نشد.'));
    } finally {
      setSending(false);
    }
  }

  async function changeStatus(status: SupportTicketStatus) {
    if (activeId === null) return;
    if (demoMode) {
      setDemoList((list) => list.map((ticket) => ticket.id === activeId ? { ...ticket, status } : ticket));
      return;
    }
    try {
      await api.put(`/admin/tickets/${activeId}`, { status });
      await queryClient.invalidateQueries({ queryKey: ['admin-tickets'] });
      await queryClient.invalidateQueries({ queryKey: ['admin-ticket', activeId] });
    } catch (error) {
      setNotice(apiErrorMessage(error, 'تغییر وضعیت انجام نشد.'));
    }
  }

  const loading = !demoMode && listQuery.isLoading;

  return (
    <div className="admin-panel adm-tk">
      <div className="admin-panel-heading">
        <div><h2>پشتیبانی</h2><p>هر درخواست یک گفت‌وگوی دوطرفه است؛ پاسخ شما در حساب کاربر نیز دیده می‌شود.</p></div>
        <span className="admin-table-count">{toFa(tickets.length)} مورد</span>
      </div>

      <div className="admin-list-toolbar">
        <label><Search size={16} /><input value={search} onChange={(event) => setSearch(event.target.value)} placeholder="جست‌وجوی موضوع یا ایمیل کاربر…" /></label>
        <div className="admin-segment">
          {STATUS_FILTERS.map(([value, label]) => (
            <button key={value} className={statusFilter === value ? 'active' : ''} onClick={() => setStatusFilter(value)}>{label}</button>
          ))}
        </div>
      </div>

      <div className="adm-tk-grid">
        <div className="adm-tk-list">
          {loading && <p className="ov-loading">در حال بارگذاری…</p>}
          {!loading && tickets.length === 0 && <div className="empty-inline"><Ticket size={24} /><span>درخواستی با این فیلتر پیدا نشد.</span></div>}
          {tickets.map((ticket) => (
            <button key={ticket.id} className={`adm-tk-item ${ticket.status}${ticket.id === activeId ? ' is-active' : ''}`} onClick={() => setSelectedId(ticket.id)}>
              <span className={`ticket-status ${ticket.status}`}>{TICKET_STATUS_LABEL[ticket.status]}</span>
              <b>{ticket.subject}</b>
              <small>{ticket.user?.name ?? 'کاربر فایل‌استور'} · {formatDate(ticket.updated_at ?? ticket.created_at ?? undefined)}</small>
            </button>
          ))}
        </div>

        <div className="adm-tk-thread">
          {!selected && <div className="empty-inline"><Ticket size={24} /><span>یک درخواست را از فهرست انتخاب کنید.</span></div>}
          {selected && (
            <>
              <header className="adm-tk-head">
                <div>
                  <b>{selected.subject}</b>
                  <small>{selected.user?.name ?? 'کاربر'} · {selected.user?.email ?? ''} · {formatDate(selected.created_at ?? undefined)}</small>
                </div>
                <div className="admin-segment">
                  {(['open', 'in_progress', 'closed'] as SupportTicketStatus[]).map((status) => (
                    <button key={status} className={selected.status === status ? 'active' : ''} onClick={() => void changeStatus(status)}>{TICKET_STATUS_LABEL[status]}</button>
                  ))}
                </div>
              </header>

              <TicketMessages messages={selected.messages ?? []} viewerIsStaff />

              <form className="adm-tk-reply" onSubmit={(event) => { event.preventDefault(); void sendReply(); }}>
                <textarea value={reply} onChange={(event) => setReply(event.target.value)} rows={4} placeholder="پاسخ خود را برای کاربر بنویسید…" maxLength={5000} />
                <div className="adm-tk-reply-actions">
                  <button type="button" className="button button-outline button-small" disabled={sending || reply.trim().length < 2} onClick={() => void sendReply('closed')}>ارسال و بستن</button>
                  <button className="button button-primary button-small" disabled={sending || reply.trim().length < 2}><Send size={15} /> ارسال پاسخ</button>
                </div>
              </form>
            </>
          )}
        </div>
      </div>
    </div>
  );
}

const demoNow = Date.now();
const demoTickets: SupportTicket[] = [
  {
    id: 1, subject: 'لینک کتاب دانلود نمی‌شود', status: 'open', created_at: new Date(demoNow).toISOString(), updated_at: new Date(demoNow).toISOString(),
    user: { name: 'سارا بهرامی', email: 'sara@example.com' },
    messages: [{ id: 1, body: 'در دریافت کتابم مشکل دارم و پیام خطا می‌بینم.', is_staff: false, author: 'سارا بهرامی', created_at: new Date(demoNow).toISOString() }],
  },
  {
    id: 2, subject: 'سؤال درباره‌ی فرمت فایل', status: 'in_progress', created_at: new Date(demoNow - 86400000).toISOString(), updated_at: new Date(demoNow - 3600000).toISOString(),
    user: { name: 'پویا احمدی', email: 'pouya@example.com' },
    messages: [
      { id: 2, body: 'آیا نسخه‌ی کتاب روی تبلت هم خوانا است؟', is_staff: false, author: 'پویا احمدی', created_at: new Date(demoNow - 86400000).toISOString() },
      { id: 3, body: 'سلام! بله، فایل PDF روی تبلت‌ها با صفحه‌ی قابل‌تنظیم خوانا است.', is_staff: true, author: 'پشتیبانی فایل‌استور', created_at: new Date(demoNow - 3600000).toISOString() },
    ],
  },
];
