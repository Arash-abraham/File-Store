import { useState, type FormEvent } from 'react';
import { ArrowLeft, CheckCircle2, KeyRound, Mail } from 'lucide-react';
import { Link, useSearchParams } from 'react-router-dom';
import { useStore } from '../StoreContext';
import { api, apiErrorMessage, demoMode, requestCsrfCookie } from '../lib/api';

export default function PasswordResetPage() {
  const [params] = useSearchParams();
  const token = params.get('token') ?? '';
  const [email, setEmail] = useState(params.get('email') ?? '');
  const [password, setPassword] = useState('');
  const [confirmation, setConfirmation] = useState('');
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const { setLoginOpen } = useStore();
  const resetting = Boolean(token);

  async function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setError('');
    setSuccess('');
    setBusy(true);
    try {
      if (demoMode) {
        setSuccess(resetting ? 'در حالت نمایشی، تغییر گذرواژه به سرور ارسال نمی‌شود.' : 'اگر این ایمیل ثبت شده باشد، پیوند بازیابی برایش ارسال می‌شود.');
      } else {
        await requestCsrfCookie();
        if (resetting) {
          await api.post('/auth/reset-password', { token, email, password, password_confirmation: confirmation });
          setSuccess('گذرواژه با موفقیت تغییر کرد. حالا می‌توانی وارد شوی.');
        } else {
          await api.post('/auth/forgot-password', { email });
          setSuccess('اگر این ایمیل در سامانه ثبت شده باشد، پیوند بازیابی برایش ارسال می‌شود.');
        }
      }
    } catch (reason) {
      setError(apiErrorMessage(reason, 'درخواست انجام نشد. دوباره تلاش کن.'));
    } finally {
      setBusy(false);
    }
  }

  return <section className="container reset-page section-pad"><div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>{resetting ? 'تغییر گذرواژه' : 'بازیابی گذرواژه'}</b></div><div className="reset-card"><span className="reset-icon">{resetting ? <KeyRound size={26} /> : <Mail size={26} />}</span><span className="eyebrow">حساب امن، خیال آسوده</span><h1>{resetting ? 'گذرواژه‌ی تازه بساز.' : 'راه برگشت همیشه هست.'}</h1><p>{resetting ? 'یک گذرواژه‌ی قوی انتخاب کن تا دوباره به کتابخانه‌ات برگردی.' : 'ایمیلی را که با آن ثبت‌نام کردی بنویس؛ اگر حسابی پیدا شود، پیوند بازیابی می‌فرستیم.'}</p><form className="form-stack" onSubmit={(event) => void submit(event)}><label className="field-label">ایمیل<input type="email" autoComplete="email" value={email} onChange={(event) => setEmail(event.target.value)} required /></label>{resetting && <><label className="field-label">گذرواژه‌ی تازه<input type="password" autoComplete="new-password" value={password} onChange={(event) => setPassword(event.target.value)} minLength={8} required /></label><label className="field-label">تکرار گذرواژه<input type="password" autoComplete="new-password" value={confirmation} onChange={(event) => setConfirmation(event.target.value)} minLength={8} required /></label></>}{error && <div className="form-error">{error}</div>}{success && <div className="reset-success"><CheckCircle2 size={17} />{success}</div>}<button className="button button-primary button-full" disabled={busy}>{busy ? 'در حال ارسال…' : resetting ? 'ذخیره‌ی گذرواژه‌ی تازه' : 'ارسال پیوند بازیابی'} <ArrowLeft size={16} /></button></form><button className="reset-login-link" onClick={() => setLoginOpen(true)}>یادت آمد؟ <b>ورود به حساب</b></button></div></section>;
}
