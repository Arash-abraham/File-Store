import type { SupportTicketStatus } from '../../types';

export const TICKET_STATUS_LABEL: Record<SupportTicketStatus, string> = {
  open: 'در انتظار بررسی',
  in_progress: 'در حال پیگیری',
  closed: 'بسته شده',
};
