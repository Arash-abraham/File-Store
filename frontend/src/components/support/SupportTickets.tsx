import { useEffect, useState, type FormEvent } from 'react';
import { useQuery, useQueryClient } from '@tanstack/react-query';
import { ArrowLeft, Plus, Send, Ticket } from 'lucide-react';
import { useStore } from '../../StoreContext';
import { api, apiErrorMessage, demoMode } from '../../lib/api';
import { formatDate } from '../../lib/format';
import type { SupportTicket } from '../../types';
import TicketMessages from './TicketMessages';
import { TICKET_STATUS_LABEL } from './statusLabels';

/** Customer side of support: a list of requests and a two-way conversation for each. */
export default function SupportTickets() {
  const { user, setNotice } = useStore();
  const queryClient = useQueryClient();
  const [selectedId, setSelectedId] = useState<number | null>(null);
  const [creating, setCreating] = useState(false);
  const [subject, setSubject] = useState('');
  const [message, setMessage] = useState('');
  const [reply, setReply] = useState('');
  const [sending, setSending] = useState(false);
  const [formError, setFormError] = useState('');
  const [demoList, setDemoList] = useState<SupportTicket[]>(demoTickets);

  const listQuery = useQuery<SupportTicket[]>({
    queryKey: ['account-tickets', user?.id],
    enabled: !!user && !demoMode,
    queryFn: async () => (await api.get('/account/tickets')).data.data as SupportTicket[],
  });
  const detailQuery = useQuery<SupportTicket>({
    queryKey: ['account-ticket', selectedId],
    enabled: !!user && !demoMode && selectedId !== null,
    queryFn: async () => (await api.get(`/account/tickets/${selectedId}`)).data.data as SupportTicket,
  });

  const tickets = demoMode ? demoList : listQuery.data ?? [];
  const selected = demoMode ? demoList.find((ticket) => ticket.id === selectedId) : detailQuery.data;

  useEffect(() => {
    if (selectedId === null && tickets.length && !creating) setSelectedId(tickets[0].id);
  }, [tickets, selectedId, creating]);

  async function createTicket(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setFormError('');
    setSending(true);
    try {
      if (demoMode) {
        const now = new Date().toISOString();
        const created: SupportTicket = { id: Date.now(), subject, status: 'open', created_at: now, updated_at: now, messages: [{ id: Date.now(), body: message, is_staff: false, author: 'شما', created_at: now }] };
        setDemoList((list) => [created, ...list]);
        setSelectedId(created.id);
      } else {
        const response = await api.post('/account/tickets', { subject, message });
        await queryClient.invalidateQueries({ queryKey: ['account-tickets'] });
        setSelectedId(response.data.data.id);
        setNotice('درخواست پشتیبانی ثبت شد.');
      }
      setSubject(''); setMessage(''); setCreating(false);
    } catch (error) {
      setFormError(apiErrorMessage(error, 'ثبت درخواست انجام نشد.'));
    } finally {
      setSending(false);
    }
  }

  async function sendReply(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (selectedId === null || reply.trim().length < 2) return;
    const body = reply.trim();
    setSending(true);
    try {
      if (demoMode) {
        setDemoList((list) => list.map((ticket) => ticket.id !== selectedId ? ticket : {
          ...ticket,
          status: 'open',
          messages: [...(ticket.messages ?? []), { id: Date.now(), body, is_staff: false, author: 'شما', created_at: new Date().toISOString() }],
        }));
      } else {
        await api.post(`/account/tickets/${selectedId}/messages`, { body });
        await queryClient.invalidateQueries({ queryKey: ['account-ticket', selectedId] });
        await queryClient.invalidateQueries({ queryKey: ['account-tickets'] });
      }
      setReply('');
    } catch (error) {
      setNotice(apiErrorMessage(error, 'ارسال پیام انجام نشد.'));
    } finally {
      setSending(false);
    }
  }

  return (
    <div className="support-layout">
      <div className="account-panel ticket-list-panel">
        <div className="account-panel-heading">
          <div><h3>درخواست‌های من</h3><p>هر درخواست یک گفت‌وگو با پشتیبانی است.</p></div>
          <button className="button button-outline button-small" onClick={() => { setCreating(true); setSelectedId(null); }}><Plus size={15} /> درخواست تازه</button>
        </div>
        {tickets.length ? tickets.map((ticket) => (
          <button key={ticket.id} className={`ticket-item ticket-item-button${ticket.id === selectedId && !creating ? ' is-active' : ''}`} onClick={() => { setCreating(false); setSelectedId(ticket.id); }}>
            <div><b>{ticket.subject}</b><small>{formatDate(ticket.updated_at ?? ticket.created_at ?? undefined)}</small></div>
            <span className={`ticket-status ${ticket.status}`}>{TICKET_STATUS_LABEL[ticket.status]}</span>
            <small>{(ticket.messages_count ?? ticket.messages?.length ?? 0).toLocaleString('fa-IR')} پیام</small>
          </button>
        )) : <div className="empty-inline"><Ticket size={24} /><span>هنوز درخواستی ثبت نکرده‌اید.</span></div>}
      </div>

      <div className="account-panel ticket-thread-panel">
        {creating || (!selected && !tickets.length) ? (
          <form className="form-stack" onSubmit={(event) => void createTicket(event)}>
            <div className="account-panel-heading"><div><h3>درخواست تازه</h3><p>تیم ما در اولین فرصت پاسخ می‌دهد.</p></div></div>
            <label className="field-label">موضوع<input value={subject} onChange={(event) => setSubject(event.target.value)} placeholder="مثلاً مشکل در دریافت فایل" required maxLength={200} /></label>
            <label className="field-label">توضیحات<textarea value={message} onChange={(event) => setMessage(event.target.value)} placeholder="کمی بیشتر برایمان توضیح بده…" rows={5} required minLength={10} /></label>
            {formError && <p className="form-error">{formError}</p>}
            <div className="adm-tk-reply-actions">
              {tickets.length > 0 && <button type="button" className="button button-outline" onClick={() => setCreating(false)}>انصراف</button>}
              <button className="button button-primary" disabled={sending}>ثبت درخواست <ArrowLeft size={15} /></button>
            </div>
          </form>
        ) : selected ? (
          <>
            <header className="adm-tk-head">
              <div><b>{selected.subject}</b><small>{formatDate(selected.created_at ?? undefined)}</small></div>
              <span className={`ticket-status ${selected.status}`}>{TICKET_STATUS_LABEL[selected.status]}</span>
            </header>
            <TicketMessages messages={selected.messages ?? []} viewerIsStaff={false} />
            <form className="adm-tk-reply" onSubmit={(event) => void sendReply(event)}>
              <textarea value={reply} onChange={(event) => setReply(event.target.value)} rows={3} maxLength={5000} placeholder={selected.status === 'closed' ? 'این درخواست بسته شده؛ با ارسال پیام دوباره باز می‌شود.' : 'پیام خود را بنویسید…'} />
              <div className="adm-tk-reply-actions">
                <button className="button button-primary button-small" disabled={sending || reply.trim().length < 2}><Send size={15} /> ارسال پیام</button>
              </div>
            </form>
          </>
        ) : <div className="empty-inline"><Ticket size={24} /><span>یک درخواست را انتخاب کنید.</span></div>}
      </div>
    </div>
  );
}

const demoTickets: SupportTicket[] = [
  {
    id: 1, subject: 'راهنمای کتابخانه', status: 'in_progress', created_at: new Date(Date.now() - 86400000).toISOString(), updated_at: new Date().toISOString(),
    messages: [
      { id: 1, body: 'فایل‌ها را از بخش کتابخانه می‌توانم دریافت کنم؟', is_staff: false, author: 'شما', created_at: new Date(Date.now() - 86400000).toISOString() },
      { id: 2, body: 'بله، کتاب‌های خریداری‌شده همیشه در کتابخانه‌تان می‌مانند.', is_staff: true, author: 'پشتیبانی فایل‌استور', created_at: new Date().toISOString() },
    ],
  },
];
