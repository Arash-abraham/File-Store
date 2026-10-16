import { useEffect, useState, type FormEvent } from 'react';
import { Link, Outlet, useLocation } from 'react-router-dom';
import { ArrowLeft, BookOpen, Check, ChevronDown, CircleUserRound, Menu, Moon, ShoppingBag, SlidersHorizontal, Sun, X } from 'lucide-react';
import { useStore } from '../StoreContext';
import { apiErrorMessage, demoMode } from '../lib/api';
import MainNav from './MainNav';
import SearchBox from './SearchBox';

function Brand() {
  return (
    <Link to="/" className="brand" aria-label="فایل‌استور، صفحه‌ی اصلی">
      <span className="brand-mark"><BookOpen size={21} strokeWidth={2.2} /></span>
      <span className="brand-copy"><b>فایل‌استور</b><small>دانش، برای همه</small></span>
    </Link>
  );
}

function Header() {
  const { user, cart, setLoginOpen, theme, toggleTheme } = useStore();
  const [menuOpen, setMenuOpen] = useState(false);

  return (
    <>
      <div className="announcement">
        <div className="container announcement-inner">
          <span><span className="announcement-dot" /> هفته‌ی کتاب؛ با کد <b>WELCOME10</b> ده درصد کمتر بخوان</span>
          <Link to="/catalog">پیشنهادهای این هفته <ArrowLeft size={13} /></Link>
        </div>
      </div>
      <header className="site-header">
        <div className="container header-main">
          <button className="icon-button mobile-menu-button" onClick={() => setMenuOpen(!menuOpen)} aria-label="باز کردن منو">
            {menuOpen ? <X size={21} /> : <Menu size={21} />}
          </button>
          <Brand />
          <MainNav menuOpen={menuOpen} onNavigate={() => setMenuOpen(false)} />
          <SearchBox variant="header" onNavigate={() => setMenuOpen(false)} />
          {/* <Link to="/catalog?advanced=1" className="header-tool" title="جست‌وجوی پیشرفته" aria-label="جست‌وجوی پیشرفته" onClick={() => setMenuOpen(false)}>
            <SlidersHorizontal size={19} />
          </Link> */}
          <div className="header-actions">
            <button
              type="button"
              className="theme-toggle"
              onClick={toggleTheme}
              aria-label={theme === 'dark' ? 'فعال‌کردن پوسته‌ی روشن' : 'فعال‌کردن پوسته‌ی تیره'}
              title={theme === 'dark' ? 'پوسته‌ی روشن' : 'پوسته‌ی تیره'}
              aria-pressed={theme === 'dark'}
            >
              {theme === 'dark' ? <Sun size={17} /> : <Moon size={17} />}
            </button>
            <Link to="/cart" className="header-cart" aria-label="سبد خرید">
              <ShoppingBag size={19} />
              <span>سبد</span>
              {cart.items_count > 0 && <i>{cart.items_count}</i>}
            </Link>
            {user ? (
              <Link to={user.role === 'admin' ? '/admin' : '/account'} className="user-chip">
                <span className="user-avatar">{user.name.slice(0, 1)}</span>
                <span className="user-chip-name">{user.name.split(' ')[0]}</span>
                <ChevronDown size={14} />
              </Link>
            ) : (
              <button className="login-link" onClick={() => setLoginOpen(true)}>
                <CircleUserRound size={18} /><span>ورود</span>
              </button>
            )}
          </div>
        </div>
        <div className="container header-subline">
          <span><Check size={14} /> خرید مطمئن، دسترسی همیشگی</span>
          <span><Check size={14} /> پرداخت امن و سریع</span>
          <span><Check size={14} /> پشتیبانی از آدم‌های واقعی</span>
        </div>
      </header>
    </>
  );
}

function LoginModal() {
  const { loginOpen, setLoginOpen, login, register, setNotice } = useStore();
  const [mode, setMode] = useState<'login' | 'register'>('login');
  const [name, setName] = useState('');
  const [email, setEmail] = useState('user@example.com');
  const [password, setPassword] = useState('password');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  useEffect(() => {
    if (!loginOpen) setError('');
  }, [loginOpen]);

  if (!loginOpen) return null;

  async function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setError('');
    setLoading(true);
    try {
      if (mode === 'login') await login(email, password);
      else await register(name, email, password, passwordConfirmation);
      setNotice(mode === 'login' ? 'خوش آمدید؛ از ادامه‌ی مسیر لذت ببرید.' : 'حساب کاربری‌ات آماده است؛ خوش آمدی.');
    } catch (reason) {
      setError(apiErrorMessage(reason, 'ورود انجام نشد.'));
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="modal-backdrop" onMouseDown={(event) => event.target === event.currentTarget && setLoginOpen(false)}>
      <section className="auth-modal" role="dialog" aria-modal="true" aria-labelledby="login-title">
        <button className="modal-close icon-button" onClick={() => setLoginOpen(false)} aria-label="بستن"><X size={19} /></button>
        <div className="auth-modal-art">
          <div className="auth-art-orbit orbit-one" />
          <div className="auth-art-orbit orbit-two" />
          <div className="auth-art-book"><BookOpen size={35} /></div>
          <span className="auth-art-star star-one">✳</span><span className="auth-art-star star-two">✦</span>
          <p>{mode === 'login' ? <>یک فصل تازه<br />از همین‌جا شروع می‌شود.</> : <>جای تو در این قفسه<br />خالی بود.</>}</p>
        </div>
        <div className="auth-modal-content">
          <div className="eyebrow">{mode === 'login' ? 'به جمع کتاب‌خوان‌ها خوش آمدی' : 'برای خواندن، ساختن و کشف کردن'}</div>
          <h2 id="login-title">{mode === 'login' ? 'ورود به فایل‌استور' : 'ساخت حساب کاربری'}</h2>
          <p className="muted">{mode === 'login' ? 'برای دیدن کتابخانه و ادامه‌ی خرید وارد حسابت شو.' : 'چند قدم کوتاه تا کتابخانه‌ی شخصی‌ات فاصله داری.'}</p>
          <form onSubmit={submit} className="form-stack">
            {mode === 'register' && <label className="field-label">نام و نام خانوادگی<input autoComplete="name" value={name} onChange={(event) => setName(event.target.value)} required maxLength={255} /></label>}
            <label className="field-label">ایمیل<input type="email" autoComplete="email" value={email} onChange={(event) => setEmail(event.target.value)} required /></label>
            <label className="field-label">گذرواژه<input type="password" autoComplete={mode === 'login' ? 'current-password' : 'new-password'} value={password} onChange={(event) => setPassword(event.target.value)} required minLength={8} /></label>
            {mode === 'register' && <label className="field-label">تکرار گذرواژه<input type="password" autoComplete="new-password" value={passwordConfirmation} onChange={(event) => setPasswordConfirmation(event.target.value)} required minLength={8} /></label>}
            {error && <div className="form-error">{error}</div>}
            <button className="button button-primary button-full" disabled={loading}>{loading ? 'یک لحظه…' : mode === 'login' ? 'ورود به حساب' : 'ساخت حساب'} <ArrowLeft size={16} /></button>
          </form>
          {demoMode && mode === 'login' && <div className="demo-credentials"><b>حساب نمایشی</b><span>کاربر: user@example.com · مدیر: admin@example.com</span><span>گذرواژه‌ی هر دو: password</span></div>}
          {mode === 'login' && <Link className="auth-forgot" to="/reset-password" onClick={() => setLoginOpen(false)}>گذرواژه‌ات را فراموش کرده‌ای؟</Link>}
          <p className="auth-switch">{mode === 'login' ? 'حساب نداری؟' : 'قبلاً عضو شده‌ای؟'} <button type="button" className="auth-toggle-link" onClick={() => { setMode(mode === 'login' ? 'register' : 'login'); setError(''); }}>{mode === 'login' ? 'ساخت حساب رایگان' : 'ورود به حساب'}</button></p>
        </div>
      </section>
    </div>
  );
}

function Footer() {
  const { setNotice } = useStore();
  return (
    <footer className="site-footer">
      <div className="container footer-main">
        <div className="footer-brand-block">
          <Brand />
          <p>کتاب‌هایی که کمک می‌کنند<br />یک قدم جلوتر بروی.</p>
          <div className="social-row"><a href="mailto:hello@filestore.local" aria-label="ایمیل">✉</a><a href="/faq" aria-label="راهنما">؟</a><span className="social-note">همیشه کنار خواننده‌ها</span></div>
        </div>
        <div className="footer-column"><b>گشت‌وگذار</b><Link to="/catalog">همه‌ی کتاب‌ها</Link><Link to="/catalog?category=programming">برنامه‌نویسی</Link><Link to="/catalog?category=history-literature">تاریخ و ادبیات</Link><Link to="/catalog?category=science">علم و ریاضی</Link></div>
        <div className="footer-column"><b>راهنما</b><Link to="/print">چاپ نسخه‌ی کاغذی</Link><Link to="/about">درباره‌ی فایل‌استور</Link><Link to="/faq">پرسش‌های پرتکرار</Link><Link to="/account">کتابخانه‌ی من</Link><Link to="/account?tab=support">پشتیبانی</Link></div>
        <div className="footer-newsletter"><span className="eyebrow">نامه‌ای از دنیای کتاب</span><h3>هر هفته یک ایده‌ی تازه</h3><p>تازه‌ترین کتاب‌ها و پیشنهادهای خوب را از دست نده.</p><form onSubmit={(event) => { event.preventDefault(); setNotice('عضویت در خبرنامه به‌زودی فعال می‌شود.'); }}><input type="email" placeholder="ایمیل تو" aria-label="ایمیل برای خبرنامه" required /><button aria-label="ثبت ایمیل"><ArrowLeft size={17} /></button></form><small>با ثبت ایمیل، عضو خبرنامه‌ی فایل‌استور می‌شوی.</small></div>
      </div>
      <div className="container footer-bottom"><span>© {new Date().getFullYear()} فایل‌استور. ساخته‌شده برای آدم‌های کنجکاو.</span><div><span>حریم خصوصی</span><span>شرایط استفاده</span><span className="footer-made">با حوصله در ایران ♡</span></div></div>
    </footer>
  );
}

function ToastNotice() {
  const { notice, setNotice } = useStore();
  useEffect(() => {
    if (!notice) return;
    const timeout = window.setTimeout(() => setNotice(null), 3800);
    return () => window.clearTimeout(timeout);
  }, [notice, setNotice]);

  return notice ? <button className="toast-notice" onClick={() => setNotice(null)}><span className="toast-check"><Check size={15} /></span>{notice}<X size={15} /></button> : null;
}

export default function SiteLayout() {
  const { pathname } = useLocation();
  useEffect(() => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }, [pathname]);

  return (
    <div className="app-shell">
      <Header />
      <main><Outlet /></main>
      <Footer />
      <LoginModal />
      <ToastNotice />
    </div>
  );
}
