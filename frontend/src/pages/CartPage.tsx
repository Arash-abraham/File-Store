import { useState, type FormEvent } from 'react';
import { useQuery } from '@tanstack/react-query';
import { ArrowLeft, ArrowRight, Check, CreditCard, Gift, ShieldCheck, ShoppingBag, Trash2, Wallet } from 'lucide-react';
import { Link, useNavigate } from 'react-router-dom';
import { useStore } from '../StoreContext';
import { formatPrice } from '../lib/format';
import { api, demoMode } from '../lib/api';

export default function CartPage() {
  const { cart, user, removeFromCart, applyCoupon, removeCoupon, checkout, setNotice, setLoginOpen } = useStore();
  const [coupon, setCoupon] = useState('');
  const [couponError, setCouponError] = useState('');
  const [paymentMethod, setPaymentMethod] = useState<'zarinpal' | 'wallet'>('zarinpal');
  const [loading, setLoading] = useState(false);
  const [checkoutError, setCheckoutError] = useState('');
  const navigate = useNavigate();
  const walletQuery = useQuery({
    queryKey: ['checkout-wallet', user?.id],
    enabled: !!user && !demoMode,
    queryFn: async () => (await api.get('/wallet')).data.data,
  });
  const walletBalance = demoMode ? 1250000 : Number(walletQuery.data?.balance ?? 0);
  const walletUnavailable = !demoMode && (!user || walletBalance < cart.total);

  async function submitCoupon(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setCouponError('');
    try {
      await applyCoupon(coupon);
      setCoupon('');
      setNotice('کد تخفیف با موفقیت اعمال شد.');
    } catch (error) {
      setCouponError(error instanceof Error ? error.message : 'این کد تخفیف معتبر نیست.');
    }
  }

  async function completeCheckout() {
    setLoading(true);
    setCheckoutError('');
    try {
      const result = await checkout(paymentMethod);
      if (result.redirect) {
        window.location.assign(result.redirect);
      } else {
        navigate(`/checkout/result?status=success&order_id=${result.orderId ?? ''}`);
      }
    } catch (error) {
      setCheckoutError(error instanceof Error ? error.message : 'پرداخت انجام نشد.');
    } finally {
      setLoading(false);
    }
  }

  return (
    <section className="container cart-page section-pad">
      <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>سبد خرید</b></div>
      <div className="cart-page-heading"><div><span className="eyebrow">قبل از شروع فصل بعد</span><h1>سبد خرید <span>تو</span></h1><p>{cart.items_count.toLocaleString('fa-IR')} کتاب در سبدت منتظرند.</p></div><Link className="text-link" to="/catalog"><ArrowRight size={15} /> ادامه‌ی گشت‌وگذار</Link></div>
      {cart.items.length === 0 ? <div className="empty-cart"><span className="empty-cart-icon"><ShoppingBag size={32} /></span><h2>سبدت هنوز قصه‌ای ندارد.</h2><p>یک کتاب خوب انتخاب کن و به کتابخانه‌ی آینده‌ات اضافه‌اش کن.</p><Link className="button button-primary" to="/catalog">رفتن به قفسه‌ی کتاب‌ها <ArrowLeft size={16} /></Link></div> : (
        <div className="cart-layout">
          <div className="cart-lines-card">
            <div className="cart-lines-header"><b>کتاب‌های انتخاب‌شده</b><span>{cart.items_count.toLocaleString('fa-IR')} عنوان</span></div>
            {cart.items.map((item) => <article className="cart-line" key={item.id}>
              <Link to={`/book/${item.slug}`} className="cart-line-cover">{item.cover_url ? <img src={item.cover_url} alt={item.title} /> : <span>کتاب</span>}</Link>
              <div className="cart-line-main"><Link to={`/book/${item.slug}`} className="cart-line-title">{item.title}</Link><small>نسخه‌ی دیجیتال · PDF</small><button className="cart-line-remove" onClick={() => void removeFromCart(item.id)}><Trash2 size={14} /> حذف از سبد</button></div>
              <div className="cart-line-qty is-single"><span title="کتاب دیجیتال تک‌نسخه است"><Check size={13} /> یک نسخه</span></div>
              <div className="cart-line-price"><b>{formatPrice(item.subtotal)}</b><small>{formatPrice(item.unit_price)} / هر کتاب</small></div>
            </article>)}
            <div className="cart-promise"><ShieldCheck size={17} /><span>خرید امن است؛ فایل کتاب‌ها بعد از پرداخت در کتابخانه‌ی شما می‌ماند.</span></div>
          </div>
          <aside className="cart-summary">
            <div className="cart-summary-title"><span>خلاصه‌ی سفارش</span><span className="summary-lock"><ShieldCheck size={15} /> امن</span></div>
            <div className="coupon-block"><label htmlFor="coupon-input"><Gift size={15} /> کد تخفیف داری؟</label>{cart.coupon_code ? <div className="coupon-applied"><span><Check size={14} /> {cart.coupon_code}</span><button onClick={() => void removeCoupon()}>حذف</button></div> : <form onSubmit={submitCoupon}><input id="coupon-input" value={coupon} onChange={(event) => setCoupon(event.target.value)} placeholder="کد را اینجا بنویس" /><button type="submit">اعمال</button></form>}{couponError && <small className="form-error inline-error">{couponError}</small>}</div>
            <div className="summary-row"><span>جمع کتاب‌ها</span><b>{formatPrice(cart.subtotal)}</b></div>
            <div className="summary-row summary-discount"><span>تخفیف</span><b>{cart.discount > 0 ? `− ${formatPrice(cart.discount)}` : '—'}</b></div>
            <div className="summary-total"><span>مبلغ نهایی</span><b>{formatPrice(cart.total)}</b></div>
            <div className="payment-choices"><label className={paymentMethod === 'zarinpal' ? 'selected' : ''}><input type="radio" name="payment" checked={paymentMethod === 'zarinpal'} onChange={() => setPaymentMethod('zarinpal')} /><span className="payment-choice-icon"><CreditCard size={17} /></span><span><b>پرداخت آنلاین</b><small>درگاه امن بانکی</small></span><i /></label><label className={`${paymentMethod === 'wallet' ? 'selected' : ''} ${walletUnavailable ? 'wallet-unavailable' : ''}`}><input type="radio" name="payment" checked={paymentMethod === 'wallet'} disabled={walletUnavailable} onChange={() => setPaymentMethod('wallet')} /><span className="payment-choice-icon wallet-icon"><Wallet size={17} /></span><span><b>کیف پول</b><small>موجودی: {formatPrice(walletBalance)}</small></span><i /></label></div>
            {!user && !demoMode && <div className="cart-login-hint">برای ادامه‌ی خرید باید وارد حساب شوید. <button onClick={() => setLoginOpen(true)}>ورود</button></div>}
            {checkoutError && <div className="form-error checkout-error">{checkoutError}</div>}
            <button className="button button-primary button-full checkout-button" onClick={() => void completeCheckout()} disabled={loading}>{loading ? 'در حال آماده‌سازی…' : 'ادامه و پرداخت'} <ArrowLeft size={17} /></button>
            <p className="checkout-terms">با ادامه‌ی خرید، <Link to="/faq">شرایط استفاده</Link> را می‌پذیری.</p>
          </aside>
        </div>
      )}
      <div className="cart-under-note"><span>✓</span> هیچ هزینه‌ی پنهانی وجود ندارد؛ مبلغ نهایی همین است.</div>
    </section>
  );
}
