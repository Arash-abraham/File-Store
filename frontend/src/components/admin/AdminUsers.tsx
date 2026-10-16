import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { Search, Ticket, X } from 'lucide-react';
import { demoMode, api } from '../../lib/api';
import { formatDate, formatPrice } from '../../lib/format';
import type { AdminUser, AdminUserDetail } from '../../types';
import { TICKET_STATUS_LABEL } from '../support/statusLabels';

const toFa = (value: number) => value.toLocaleString('fa-IR');

/** Customer list with a detail panel (orders, tickets, lifetime spend). */
export default function AdminUsers({ onOpenTickets }: { onOpenTickets: (email: string) => void }) {
  const [search, setSearch] = useState('');
  const [selectedId, setSelectedId] = useState<number | null>(null);

  const listQuery = useQuery<AdminUser[]>({
    queryKey: ['admin-users', search],
    enabled: !demoMode,
    queryFn: async () => (await api.get('/admin/users', { params: search.trim() ? { search: search.trim() } : {} })).data.data as AdminUser[],
  });
  const detailQuery = useQuery<AdminUserDetail>({
    queryKey: ['admin-user', selectedId],
    enabled: !demoMode && selectedId !== null,
    queryFn: async () => (await api.get(`/admin/users/${selectedId}`)).data.data as AdminUserDetail,
  });

  const users = demoMode ? demoUsers.filter((user) => !search.trim() || `${user.name} ${user.email}`.includes(search.trim())) : listQuery.data ?? [];
  const detail: AdminUserDetail | undefined = demoMode
    ? (selectedId === null ? undefined : demoDetail(demoUsers.find((user) => user.id === selectedId)))
    : detailQuery.data;

  return (
    <div className="admin-panel adm-users">
      <div className="admin-panel-heading">
        <div><h2>کاربران</h2><p>فهرست خریداران و حساب‌ها؛ برای جزئیات هر کاربر روی ردیف آن کلیک کنید.</p></div>
        <span className="admin-table-count">{toFa(users.length)} کاربر</span>
      </div>

      <div className="admin-list-toolbar">
        <label><Search size={16} /><input value={search} onChange={(event) => setSearch(event.target.value)} placeholder="جست‌وجوی نام، ایمیل یا شماره…" /></label>
      </div>

      <div className="adm-users-grid">
        <div className="admin-table-wrap">
          <table className="admin-table">
            <thead><tr><th>کاربر</th><th>نقش</th><th>عضویت</th><th>خرید</th><th>درخواست</th></tr></thead>
            <tbody>
              {users.map((user) => (
                <tr key={user.id} className={user.id === selectedId ? 'is-active' : ''} onClick={() => setSelectedId(user.id)}>
                  <td><b>{user.name}</b><small>{user.email}</small></td>
                  <td><span className={`adm-role ${user.role}`}>{user.role === 'admin' ? 'مدیر' : 'کاربر'}</span>{!user.verified && <small className="adm-unverified">ایمیل تأیید نشده</small>}</td>
                  <td>{formatDate(user.created_at ?? undefined)}</td>
                  <td>{toFa(user.paid_orders_count)}</td>
                  <td>{toFa(user.tickets_count)}</td>
                </tr>
              ))}
            </tbody>
          </table>
          {users.length === 0 && <p className="ov-empty">کاربری پیدا نشد.</p>}
        </div>

        {detail && (
          <aside className="adm-user-detail">
            <button className="icon-button adm-user-close" onClick={() => setSelectedId(null)} aria-label="بستن"><X size={16} /></button>
            <span className="review-avatar">{detail.user.name.slice(0, 1)}</span>
            <h3>{detail.user.name}</h3>
            <p>{detail.user.email}{detail.user.phone ? ` · ${detail.user.phone}` : ''}</p>
            <div className="adm-user-stats">
              <div><small>مجموع خرید</small><b>{formatPrice(detail.total_spent)}</b></div>
              <div><small>سفارش پرداخت‌شده</small><b>{toFa(detail.user.paid_orders_count)}</b></div>
            </div>
            <button className="button button-outline button-small adm-user-tickets" onClick={() => onOpenTickets(detail.user.email)}><Ticket size={15} /> درخواست‌های این کاربر</button>

            <h4>آخرین سفارش‌ها</h4>
            {detail.orders.length ? <ul className="adm-mini-list">{detail.orders.map((order) => (
              <li key={order.id}><span>#{toFa(order.id)} · {formatDate(order.created_at ?? undefined)}</span><b>{formatPrice(order.amount)}</b><small>{order.status === 'paid' ? 'پرداخت‌شده' : order.status}</small></li>
            ))}</ul> : <p className="ov-empty">سفارشی ثبت نشده است.</p>}

            <h4>آخرین درخواست‌ها</h4>
            {detail.tickets.length ? <ul className="adm-mini-list">{detail.tickets.map((ticket) => (
              <li key={ticket.id}><span>{ticket.subject}</span><small>{TICKET_STATUS_LABEL[ticket.status]}</small></li>
            ))}</ul> : <p className="ov-empty">درخواستی ثبت نشده است.</p>}
          </aside>
        )}
      </div>
    </div>
  );
}

const demoUsers: AdminUser[] = [
  { id: 1, name: 'سارا بهرامی', email: 'sara@example.com', phone: '۰۹۱۲۱۱۱۲۲۳۳', role: 'customer', verified: true, created_at: '2026-09-02T10:00:00Z', paid_orders_count: 4, tickets_count: 1 },
  { id: 2, name: 'پویا احمدی', email: 'pouya@example.com', phone: null, role: 'customer', verified: true, created_at: '2026-10-01T08:30:00Z', paid_orders_count: 1, tickets_count: 1 },
  { id: 3, name: 'مینا سلیمانی', email: 'mina@example.com', phone: null, role: 'customer', verified: false, created_at: '2026-10-10T16:00:00Z', paid_orders_count: 0, tickets_count: 0 },
];

function demoDetail(user?: AdminUser): AdminUserDetail | undefined {
  if (!user) return undefined;
  return {
    user,
    total_spent: user.paid_orders_count * 120000,
    orders: user.paid_orders_count ? [{ id: 1040, status: 'paid', amount: 120000, created_at: '2026-10-05T10:00:00Z' }] : [],
    tickets: user.tickets_count ? [{ id: 1, subject: 'لینک کتاب دانلود نمی‌شود', status: 'open', created_at: '2026-10-15T10:00:00Z' }] : [],
  };
}
