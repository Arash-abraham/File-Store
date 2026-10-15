import { ArrowLeft, HeartHandshake, Mail, MapPin, MessageCircle, ShieldCheck, Sparkles } from 'lucide-react';
import { Link } from 'react-router-dom';

export function AboutPage() {
  return <section className="container static-page section-pad"><div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>داستان ما</b></div><div className="static-hero"><span className="eyebrow">چرا فایل‌استور؟</span><h1>برای آدم‌هایی که<br /><em>همیشه کنجکاوند.</em></h1><p>ما باور داریم کتاب خوب نباید پشت شلوغیِ یک فروشگاه گم شود. فایل‌استور از همین فکر شروع شد: قفسه‌ای کوچک‌تر، انتخاب‌هایی دقیق‌تر و دسترسی‌ای که همیشه همراه توست.</p><Link to="/catalog" className="button button-primary">رفتن به قفسه‌ها <ArrowLeft size={16} /></Link></div><div className="about-values"><article><span><Sparkles /></span><b>انتخاب با معنا</b><p>هر عنوان باید چیزی به مسیر یادگیری تو اضافه کند.</p></article><article><span><ShieldCheck /></span><b>اعتماد، نه شعار</b><p>قیمت روشن، پرداخت امن و دسترسی قابل اتکا.</p></article><article><span><HeartHandshake /></span><b>آدم‌ها در مرکز</b><p>پشتیبانی واقعی و تجربه‌ای که برای خواننده ساخته شده.</p></article></div><div className="about-contact"><div><span className="eyebrow">حرفی داری؟</span><h2>ما واقعاً گوش می‌دهیم.</h2><p>پیشنهاد کتاب، پرسش درباره‌ی سفارش یا حتی یک سلام ساده—همه‌شان برای ما مهم‌اند.</p></div><div className="contact-options"><a href="mailto:hello@filestore.local"><Mail size={17} /> hello@filestore.local <ArrowLeft size={15} /></a><a href="/faq"><MessageCircle size={17} /> پرسش‌های پرتکرار <ArrowLeft size={15} /></a><span><MapPin size={17} /> تهران، ایران</span></div></div></section>;
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
