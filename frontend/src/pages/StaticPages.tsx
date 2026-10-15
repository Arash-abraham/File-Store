import { useQuery } from '@tanstack/react-query';
import {
  ArrowLeft,
  ArrowUpLeft,
  Code2,
  ExternalLink,
  Github,
  Lightbulb,
  Mail,
  MessageCircle,
  ShieldCheck,
  Sparkles,
  Target,
  Telescope,
  TrendingUp,
  Users,
  type LucideIcon,
} from 'lucide-react';
import { Link } from 'react-router-dom';
import { api, demoMode } from '../lib/api';
import { demoAboutPage } from '../data/demo';
import type { PageFeature, PageLink, PageMilestone, PageActor, PageStat, StorePage } from '../types';

const responsibilityIcons: Record<string, LucideIcon> = {
  strategy: Target,
  team: Users,
  growth: TrendingUp,
  innovation: Lightbulb,
};

const pillarIcons: Record<string, LucideIcon> = {
  mission: ShieldCheck,
  vision: Telescope,
};

const linkIcons: Record<string, LucideIcon> = {
  code: Code2,
  github: Github,
  faq: MessageCircle,
  mail: Mail,
  external: ExternalLink,
};

function isExternalLink(url: string) {
  return /^(https?:)?\/\//.test(url);
}

function PageLinkItem({ link }: { link: PageLink }) {
  const Icon = linkIcons[link.icon ?? 'external'] ?? ExternalLink;
  const external = isExternalLink(link.url);
  // The shared `.contact-options` styles pin the trailing icon to the row end.
  const content = <><Icon size={17} /> {link.label} {external ? <ExternalLink size={13} /> : <ArrowLeft size={13} />}</>;

  return external
    ? <a href={link.url} target="_blank" rel="noreferrer">{content}</a>
    : <Link to={link.url}>{content}</Link>;
}

function AboutLoading() {
  return (
    <div className="about-skeleton" aria-busy="true" aria-label="در حال دریافت محتوای صفحه">
      <div className="about-skeleton-bar" />
      <div className="about-skeleton-block" />
      <div className="about-skeleton-grid">{[0, 1].map((item) => <div className="about-skeleton-block" key={item} />)}</div>
    </div>
  );
}

export function AboutPage() {
  const { data, isLoading, isError, refetch } = useQuery<StorePage>({
    queryKey: ['page', 'about'],
    queryFn: async () => {
      if (demoMode) return demoAboutPage;
      const response = await api.get('/pages/about');
      return response.data.data as StorePage;
    },
  });

  const page = data ?? (demoMode ? demoAboutPage : undefined);

  if (isLoading && !page) {
    return (
      <section className="container static-page section-pad">
        <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>درباره‌ی ما</b></div>
        <AboutLoading />
      </section>
    );
  }

  if (!page) {
    return (
      <section className="container static-page section-pad">
        <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>درباره‌ی ما</b></div>
        <div className="empty-state">
          <span className="empty-state-icon"><Sparkles size={22} /></span>
          <h3>{isError ? 'محتوای این صفحه در دسترس نیست.' : 'این صفحه هنوز منتشر نشده است.'}</h3>
          <p>اگر چند لحظه‌ی دیگر هم باز نشد، از پشتیبانی پیگیری کن.</p>
          <button className="button button-outline" onClick={() => void refetch()}>تلاش دوباره <ArrowLeft size={15} /></button>
        </div>
      </section>
    );
  }

  const { content } = page;
  const story = content.story;
  const founders = content.founders;
  const responsibilities = content.responsibilities;
  const timeline = content.timeline;
  const missionVision = content.mission_vision;
  const stats = content.stats;
  const contact = content.contact;

  return (
    <section className="container static-page section-pad">
      <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>{page.title}</b></div>

      <header className="static-hero about-hero">
        <span className="eyebrow">داستان فایل‌استور</span>
        <h1>{page.title}</h1>
        {page.subtitle && <p>{page.subtitle}</p>}
      </header>

      {!!story?.paragraphs?.length && (
        <article className="about-story">
          <span className="about-story-mark">“</span>
          {story.heading && <h2>{story.heading}</h2>}
          {story.paragraphs.map((paragraph, index) => <p key={index}>{paragraph}</p>)}
          <span className="about-story-line" />
        </article>
      )}

      {!!founders?.items?.length && (
        <section className="about-section">
          {founders.heading && <h2 className="about-section-title">{founders.heading}</h2>}
          <div className="about-founders">
            {founders.items.map((founder: PageActor, index: number) => (
              <article className="about-founder-card" key={index}>
                <span className="about-founder-avatar" aria-hidden="true">{founder.name.trim().slice(0, 1)}</span>
                <h3>{founder.name}</h3>
                {founder.role && <span className="about-founder-role">{founder.role}</span>}
                {founder.bio && <p>{founder.bio}</p>}
              </article>
            ))}
          </div>
        </section>
      )}

      {!!responsibilities?.items?.length && (
        <section className="about-section">
          {responsibilities.heading && <h2 className="about-section-title">{responsibilities.heading}</h2>}
          <div className="about-responsibilities">
            {responsibilities.items.map((item: PageFeature, index: number) => {
              const Icon = responsibilityIcons[item.icon ?? ''] ?? Sparkles;
              return (
                <article className={`about-responsibility about-tone-${index % 4}`} key={index}>
                  <span className="about-responsibility-icon"><Icon size={20} /></span>
                  <div>
                    <h3>{item.title}</h3>
                    {item.description && <p>{item.description}</p>}
                  </div>
                </article>
              );
            })}
          </div>
        </section>
      )}

      {!!timeline?.items?.length && (
        <section className="about-section">
          {timeline.heading && <h2 className="about-section-title">{timeline.heading}</h2>}
          <ol className="about-timeline">
            {timeline.items.map((milestone: PageMilestone, index: number) => (
              <li className={index % 2 === 0 ? 'is-start' : 'is-end'} key={index}>
                <div className="timeline-card">
                  {milestone.year && <span className="timeline-year">{milestone.year}</span>}
                  <b>{milestone.title}</b>
                  {milestone.description && <p>{milestone.description}</p>}
                </div>
                <span className="timeline-dot" aria-hidden="true" />
              </li>
            ))}
          </ol>
        </section>
      )}

      {(missionVision?.mission?.title || missionVision?.vision?.title) && (
        <section className="about-pillars">
          {(['mission', 'vision'] as const).map((key) => {
            const pillar = missionVision?.[key];
            if (!pillar?.title) return null;
            const Icon = pillarIcons[pillar.icon ?? key] ?? Sparkles;
            return (
              <article key={key}>
                <span className={`about-pillar-icon ${key}`}><Icon size={23} /></span>
                <h3>{pillar.title}</h3>
                {pillar.body && <p>{pillar.body}</p>}
              </article>
            );
          })}
        </section>
      )}

      {!!stats?.items?.length && (
        <section className="about-stats">
          {stats.heading && <h2>{stats.heading}</h2>}
          <div className="about-stats-grid">
            {stats.items.map((stat: PageStat, index: number) => (
              <div key={index}>
                <b>{stat.value}</b>
                <span>{stat.label}</span>
              </div>
            ))}
          </div>
        </section>
      )}

      {(contact?.heading || contact?.body) && (
        <div className="about-contact">
          <div>
            <span className="eyebrow">ارتباط با ما</span>
            {contact?.heading && <h2>{contact.heading}</h2>}
            {contact?.body && <p>{contact.body}</p>}
          </div>
          {!!contact?.links?.length && (
            <div className="contact-options">
              {contact.links.map((link: PageLink, index: number) => <PageLinkItem link={link} key={index} />)}
            </div>
          )}
        </div>
      )}

      <div className="about-back"><Link to="/catalog" className="text-link">برو سراغ قفسه‌ی کتاب‌ها <ArrowUpLeft size={15} /></Link></div>
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
