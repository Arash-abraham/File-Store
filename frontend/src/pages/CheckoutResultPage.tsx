import { Check, CircleCheck, Home, LibraryBig, XCircle } from 'lucide-react';
import { Link, useSearchParams } from 'react-router-dom';

export default function CheckoutResultPage() {
  const [params] = useSearchParams();
  const status = params.get('status');
  const orderId = params.get('order_id');
  const ref = params.get('ref');
  const success = status === 'success';
  const cancelled = status === 'cancelled';

  return <section className="container result-page section-pad"><div className={`result-card ${success ? 'success' : 'failed'}`}>
    <span className="result-illustration">{success ? <CircleCheck size={37} /> : <XCircle size={37} />}</span>
    <span className="eyebrow">{success ? 'یک فصل تازه شروع شد' : cancelled ? 'پرداخت لغو شد' : 'پرداخت کامل نشد'}</span>
    <h1>{success ? 'کتابخانه‌ات پربارتر شد.' : cancelled ? 'هر وقت خواستی برگرد.' : 'این‌بار نشد؛ دوباره امتحان کن.'}</h1>
    <p>{success ? 'سفارش با موفقیت ثبت شد و کتاب‌های خریداری‌شده در بخش کتابخانه‌ی من قرار می‌گیرند.' : 'از مبلغی کسر نشده است. می‌توانی دوباره تلاش کنی یا روش پرداخت دیگری را انتخاب کنی.'}</p>
    {success && <div className="result-details"><span>شماره‌ی سفارش</span><b>{orderId ?? '—'}</b>{ref && <><span>شماره‌ی پیگیری</span><b>{ref}</b></>}</div>}
    <div className="result-actions">{success ? <Link to="/account?tab=library" className="button button-primary"><LibraryBig size={17} /> رفتن به کتابخانه</Link> : <Link to="/cart" className="button button-primary">بازگشت به سبد <Check size={16} /></Link>}<Link to="/" className="button button-outline"><Home size={16} /> صفحه‌ی اصلی</Link></div>
    <small className="result-secure"><Check size={13} /> اطلاعات پرداخت شما به‌صورت امن پردازش می‌شود.</small>
  </div></section>;
}
