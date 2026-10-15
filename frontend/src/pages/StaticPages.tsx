import { ArrowLeft, Code2, ExternalLink, Github, HeartHandshake, MessageCircle, ShieldCheck, Sparkles } from 'lucide-react';
import { Link } from 'react-router-dom';

const githubProfile = 'https://github.com/Arash-abraham';
const githubRepository = `${githubProfile}/File-Store`;

export function AboutPage() {
  return (
    <section className="container static-page section-pad">
      <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>درباره‌ی ما</b></div>

      <div className="about-intro">
        <div className="about-intro-copy">
          <span className="eyebrow">درباره‌ی سازنده</span>
          <h1>سلام، من آرش هستم؛<br /><em>سازنده‌ی فایل‌استور.</em></h1>
          <p>فایل‌استور را با این ایده ساختم که پیدا کردن کتاب‌های دیجیتال و رسیدن به محتوای مفید، ساده‌تر و دل‌نشین‌تر باشد. این پروژه ترکیبی از یک فروشگاه مستقل React و یک API با Laravel است که قدم‌به‌قدم توسعه‌اش می‌دهم.</p>
          <div className="about-intro-actions">
            <a className="button button-primary" href={githubProfile} target="_blank" rel="noreferrer">
              <Github size={17} /> گیت‌هاب آرش <ExternalLink size={14} />
            </a>
            <Link to="/catalog" className="text-link">گشت‌وگذار در کتاب‌ها <ArrowLeft size={15} /></Link>
          </div>
        </div>
        <aside className="about-profile-card">
          <div className="about-profile-decoration"><i /><i /><i /></div>
          <div className="about-avatar" aria-hidden="true">آ</div>
          <span className="about-profile-kicker">خالق فایل‌استور</span>
          <h2>آرش</h2>
          <p>توسعه‌دهنده‌ی فروشگاه فایل‌استور</p>
          <a href={githubProfile} target="_blank" rel="noreferrer"><Github size={15} /> @Arash-abraham <ExternalLink size={13} /></a>
        </aside>
      </div>

      <div className="about-values">
        <article><span><Sparkles /></span><b>انتخاب با معنا</b><p>هر عنوان باید چیزی به مسیر یادگیری تو اضافه کند.</p></article>
        <article><span><ShieldCheck /></span><b>تجربه‌ی قابل اتکا</b><p>رابط روشن، خرید امن و دسترسی حساب‌شده به کتاب‌ها.</p></article>
        <article><span><HeartHandshake /></span><b>ساخته‌شده با دقت</b><p>از طراحی فارسی و راست‌چین تا جزئیات تجربه‌ی خواننده.</p></article>
      </div>

      <div className="about-contact">
        <div>
          <span className="eyebrow">کد پروژه</span>
          <h2>فایل‌استور را در گیت‌هاب ببین.</h2>
          <p>برای دیدن کد پروژه، خبر گرفتن از تغییرات یا فرستادن پیشنهاد، از لینک‌های گیت‌هاب استفاده کن.</p>
        </div>
        <div className="contact-options">
          <a href={githubRepository} target="_blank" rel="noreferrer"><Code2 size={17} /> مخزن فایل‌استور <ExternalLink size={14} /></a>
          <a href={githubProfile} target="_blank" rel="noreferrer"><Github size={17} /> پروفایل آرش <ExternalLink size={14} /></a>
          <Link to="/faq"><MessageCircle size={17} /> پرسش‌های پرتکرار <ArrowLeft size={15} /></Link>
        </div>
      </div>
    </section>
  );
}

const faqItems = [
  ['بعد از خرید چطور کتاب را دریافت کنم؟', 'پس از تأیید پرداخت، فایل‌های خریداری‌شده در بخش «کتابخانه‌ی من» حساب کاربری شما قرار می‌گیرند.'],
  ['آیا خریدها برای همیشه در حسابم می‌مانند؟', 'بله، کتاب‌های خریداری‌شده در حساب کاربری و بخش کتابخانه‌ی من در دسترس شما باقی می‌مانند.'],
  ['پرداخت با کیف پول چطور انجام می‌شود؟', 'در مرحله‌ی پرداخت می‌توانید از موجودی کیف پول استفاده کنید. اگر موجودی کافی باشد، سفارش همان لحظه ثبت می‌شود.'],
  ['کد تخفیف را کجا وارد کنم؟', 'در صفحه‌ی سبد خرید، کد تخفیف را وارد کنید. مبلغ نهایی پس از اعتبارسنجی سمت سرور نمایش داده می‌شود.'],
  ['اگر لینک دانلود کار نکرد چه کنم؟', 'در بخش پشتیبانی حساب خود یک درخواست ثبت کنید و شماره‌ی سفارش را هم بنویسید تا پیگیری کنیم.'],
];

export function FaqPage() {
  return <section className="container static-page section-pad"><div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>پرسش‌های پرتکرار</b></div><div className="faq-layout"><div className="faq-intro"><span className="eyebrow">راهنمای کوتاه</span><h1>سؤال خوب،<br /><em>جواب روشن.</em></h1><p>هر چیزی که قبل یا بعد از خرید لازم است بدانی، اینجاست.</p><div className="faq-help-card"><span><MessageCircle size={21} /></span><b>جوابت را پیدا نکردی؟</b><p>از پنل کاربری برایمان تیکت بفرست.</p><Link to="/account">رفتن به پشتیبانی <ArrowLeft size={14} /></Link></div></div><div className="faq-list">{faqItems.map(([question, answer], index) => <details key={question} open={index === 0}><summary><span className="faq-number">{String(index + 1).padStart(2, '0')}</span><b>{question}</b><span className="faq-plus">+</span></summary><p>{answer}</p></details>)}</div></div></section>;
}
