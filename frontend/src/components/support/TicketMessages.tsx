import { formatDate } from '../../lib/format';
import type { TicketMessage } from '../../types';

/** Chat-style list of a support thread. `viewerIsStaff` decides which side is "mine". */
export default function TicketMessages({ messages, viewerIsStaff }: { messages: TicketMessage[]; viewerIsStaff: boolean }) {
  return (
    <div className="ticket-thread-messages">
      {messages.map((message) => {
        const mine = message.is_staff === viewerIsStaff;
        return (
          <div key={message.id} className={`ticket-bubble ${mine ? 'mine' : 'theirs'}${message.is_staff ? ' staff' : ''}`}>
            <div className="ticket-bubble-head"><b>{message.author}</b><small>{formatDate(message.created_at ?? undefined)}</small></div>
            <p>{message.body}</p>
          </div>
        );
      })}
    </div>
  );
}
