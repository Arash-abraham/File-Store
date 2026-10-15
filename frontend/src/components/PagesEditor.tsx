import { useEffect, useState, type ReactNode } from 'react';
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { ArrowLeft, FileText, ListPlus, Plus, Save, Trash2 } from 'lucide-react';
import { Link } from 'react-router-dom';
import { api, apiErrorMessage, demoMode } from '../lib/api';
import { demoAboutPage } from '../data/demo';
import { formatDate } from '../lib/format';
import type { PageContent, PageFeature, PageLink, PageMilestone, PageActor, PageStat, StorePage } from '../types';

const responsibilityIconOptions = [['strategy', 'استراتژی'], ['team', 'تیم'], ['growth', 'رشد'], ['innovation', 'نوآوری']];
const pillarIconOptions = [['mission', 'ماموریت'], ['vision', 'چشم‌انداز']];
const linkIconOptions = [['code', 'کد'], ['github', 'گیت‌هاب'], ['faq', 'پرسش‌های پرتکرار'], ['mail', 'ایمیل'], ['external', 'لینک بیرونی']];

function Section({ title, description, children, open }: { title: string; description: string; children: ReactNode; open?: boolean }) {
  return (
    <details className="page-editor-section" open={open}>
      <summary><span><b>{title}</b><small>{description}</small></span><span className="faq-plus">+</span></summary>
      <div className="page-editor-body">{children}</div>
    </details>
  );
}

function Field({ label, value, onChange, placeholder, maxLength = 4000, wide }: {
  label: string;
  value: string;
  onChange: (value: string) => void;
  placeholder?: string;
  maxLength?: number;
  wide?: boolean;
}) {
  return (
    <label className={wide ? 'field-label page-editor-wide' : 'field-label'}>
      {label}
      <input value={value} placeholder={placeholder} maxLength={maxLength} onChange={(event) => onChange(event.target.value)} />
    </label>
  );
}

function SelectField({ label, value, options, onChange }: { label: string; value: string; options: string[][]; onChange: (value: string) => void }) {
  return (
    <label className="field-label">
      {label}
      <select value={value} onChange={(event) => onChange(event.target.value)}>
        {options.map(([optionValue, optionLabel]) => <option key={optionValue} value={optionValue}>{optionLabel}</option>)}
      </select>
    </label>
  );
}

function Rows({ children, onAdd, addLabel, empty }: { children: ReactNode; onAdd: () => void; addLabel: string; empty: boolean }) {
  return (
    <>
      {empty ? <p className="page-editor-empty">هنوز موردی اضافه نشده است.</p> : <div className="page-editor-rows">{children}</div>}
      <button type="button" className="button button-outline button-small" onClick={onAdd}><Plus size={15} /> {addLabel}</button>
    </>
  );
}

function Row({ children, onRemove }: { children: ReactNode; onRemove: () => void }) {
  return (
    <div className="page-editor-row">
      <div className="page-editor-row-fields">{children}</div>
      <button type="button" className="row-icon-button delete" title="حذف این مورد" onClick={onRemove}><Trash2 size={15} /></button>
    </div>
  );
}

function collapse(list: unknown[] | undefined) {
  return (list ?? []).filter((item) => item != null);
}

export default function PagesEditor({ onNotice }: { onNotice: (message: string) => void }) {
  const queryClient = useQueryClient();
  const [form, setForm] = useState<StorePage | null>(null);
  const [formError, setFormError] = useState('');

  const pagesQuery = useQuery<StorePage[]>({
    queryKey: ['admin-pages'],
    // The editor holds unsaved drafts in local state; a background refetch must not overwrite them.
    staleTime: Infinity,
    refetchOnWindowFocus: false,
    queryFn: async () => demoMode ? [demoAboutPage] : (await api.get('/admin/pages')).data.data as StorePage[],
  });

  const page = pagesQuery.data?.[0];

  useEffect(() => {
    if (page) setForm(page);
  }, [page]);

  const savePage = useMutation({
    mutationFn: async (payload: StorePage) => {
      if (demoMode) return payload;
      const response = await api.put(`/admin/pages/${payload.id}`, {
        title: payload.title,
        subtitle: payload.subtitle ?? null,
        status: payload.status ?? 'published',
        content: payload.content ?? {},
      });
      return response.data.data as StorePage;
    },
    onSuccess: async (saved) => {
      queryClient.setQueryData(['admin-pages'], [saved]);
      await queryClient.invalidateQueries({ queryKey: ['page', 'about'] });
      setFormError('');
      onNotice(demoMode ? 'تغییرات نمایشی ذخیره شد.' : 'محتوای صفحه ذخیره شد.');
    },
    onError: (error) => setFormError(apiErrorMessage(error, 'ذخیره‌ی محتوای صفحه انجام نشد.')),
  });

  if (pagesQuery.isLoading) return <div className="admin-panel"><p className="admin-file-empty">در حال دریافت محتوای صفحه‌ها…</p></div>;
  if (!form) return <div className="admin-panel"><div className="admin-panel-heading"><div><h2>صفحه‌های ثابت</h2><p>محتوای صفحه‌های عمومی فروشگاه.</p></div></div><p className="admin-file-empty">هنوز صفحه‌ای برای ویرایش ثبت نشده است. پس از اجرای seeder‌ها، صفحه‌ی «درباره ما» اینجا نمایش داده می‌شود.</p></div>;

  const content: PageContent = form.content ?? {};
  const story = content.story ?? {};
  const founders = content.founders ?? {};
  const responsibilities = content.responsibilities ?? {};
  const timeline = content.timeline ?? {};
  const missionVision = content.mission_vision ?? {};
  const stats = content.stats ?? {};
  const contact = content.contact ?? {};

  function patchContent(next: Partial<PageContent>) {
    setForm((current) => current ? { ...current, content: { ...current.content, ...next } } : current);
  }

  function updateFounders(items: PageActor[]) {
    patchContent({ founders: { ...founders, items } });
  }

  function updateResponsibilities(items: PageFeature[]) {
    patchContent({ responsibilities: { ...responsibilities, items } });
  }

  function updateTimeline(items: PageMilestone[]) {
    patchContent({ timeline: { ...timeline, items } });
  }

  function updateStats(items: PageStat[]) {
    patchContent({ stats: { ...stats, items } });
  }

  function updateLinks(items: PageLink[]) {
    patchContent({ contact: { ...contact, links: items } });
  }

  const founderItems = collapse(founders.items) as PageActor[];
  const responsibilityItems = collapse(responsibilities.items) as PageFeature[];
  const timelineItems = collapse(timeline.items) as PageMilestone[];
  const statItems = collapse(stats.items) as PageStat[];
  const linkItems = collapse(contact.links) as PageLink[];
  const storyParagraphs = (story.paragraphs ?? []).filter((paragraph) => typeof paragraph === 'string');

  return (
    <div className="admin-panel page-editor">
      <div className="admin-panel-heading">
        <div><h2>صفحه‌های ثابت</h2><p>متن صفحه‌ی «درباره ما» را از همین‌جا ویرایش کن؛ فروشگاه بلافاصله همان محتوا را نشان می‌دهد.</p></div>
        <Link to="/about" className="button button-outline button-small" target="_blank" rel="noreferrer"><FileText size={15} /> دیدن صفحه</Link>
      </div>

      <div className="page-editor-meta">
        <span>نشانی صفحه: <b>/{form.slug}</b></span>
        <span>آخرین به‌روزرسانی: <b>{formatDate(form.updated_at)}</b></span>
      </div>

      <div className="page-editor-head">
        <Field label="عنوان صفحه" value={form.title} onChange={(title) => setForm({ ...form, title })} maxLength={200} />
        <Field label="زیرعنوان" value={form.subtitle ?? ''} onChange={(subtitle) => setForm({ ...form, subtitle })} maxLength={300} />
        <label className="field-label">وضعیت انتشار
          <select value={form.status ?? 'published'} onChange={(event) => setForm({ ...form, status: event.target.value })}>
            <option value="published">منتشرشده</option>
            <option value="draft">پیش‌نویس</option>
          </select>
        </label>
      </div>

      <Section title="داستان" description="بخش آغازین صفحه" open>
        <Field label="عنوان بخش" value={story.heading ?? ''} onChange={(heading) => patchContent({ story: { ...story, heading } })} maxLength={200} />
        <Rows empty={storyParagraphs.length === 0} addLabel="افزودن پاراگراف" onAdd={() => patchContent({ story: { ...story, paragraphs: [...storyParagraphs, ''] } })}>
          {storyParagraphs.map((paragraph, index) => (
            <Row key={index} onRemove={() => patchContent({ story: { ...story, paragraphs: storyParagraphs.filter((_, position) => position !== index) } })}>
              <label className="field-label page-editor-wide">پاراگراف {index + 1}
                <textarea rows={3} value={paragraph} onChange={(event) => patchContent({ story: { ...story, paragraphs: storyParagraphs.map((item, position) => position === index ? event.target.value : item) } })} />
              </label>
            </Row>
          ))}
        </Rows>
      </Section>

      <Section title="بنیان‌گذاران" description="معرفی افراد و نقش‌ها" open>
        <Field label="عنوان بخش" value={founders.heading ?? ''} onChange={(heading) => patchContent({ founders: { ...founders, heading } })} maxLength={200} />
        <Rows empty={founderItems.length === 0} addLabel="افزودن بنیان‌گذار" onAdd={() => updateFounders([...founderItems, { name: '', role: '', bio: '' }])}>
          {founderItems.map((founder, index) => (
            <Row key={index} onRemove={() => updateFounders(founderItems.filter((_, position) => position !== index))}>
              <Field label="نام" value={founder.name} onChange={(name) => updateFounders(founderItems.map((item, position) => position === index ? { ...item, name } : item))} maxLength={120} />
              <Field label="نقش" value={founder.role ?? ''} onChange={(role) => updateFounders(founderItems.map((item, position) => position === index ? { ...item, role } : item))} maxLength={150} />
              <label className="field-label page-editor-wide">معرفی
                <textarea rows={3} value={founder.bio ?? ''} onChange={(event) => updateFounders(founderItems.map((item, position) => position === index ? { ...item, bio: event.target.value } : item))} />
              </label>
            </Row>
          ))}
        </Rows>
      </Section>

      <Section title="مسئولیت‌های مشترک" description="چهار کارت با آیکون">
        <Field label="عنوان بخش" value={responsibilities.heading ?? ''} onChange={(heading) => patchContent({ responsibilities: { ...responsibilities, heading } })} maxLength={200} />
        <Rows empty={responsibilityItems.length === 0} addLabel="افزودن مسئولیت" onAdd={() => updateResponsibilities([...responsibilityItems, { icon: 'strategy', title: '', description: '' }])}>
          {responsibilityItems.map((item, index) => (
            <Row key={index} onRemove={() => updateResponsibilities(responsibilityItems.filter((_, position) => position !== index))}>
              <SelectField label="آیکون" value={item.icon ?? 'strategy'} options={responsibilityIconOptions} onChange={(icon) => updateResponsibilities(responsibilityItems.map((row, position) => position === index ? { ...row, icon } : row))} />
              <Field label="عنوان" value={item.title} onChange={(title) => updateResponsibilities(responsibilityItems.map((row, position) => position === index ? { ...row, title } : row))} maxLength={200} />
              <label className="field-label page-editor-wide">توضیح
                <textarea rows={2} value={item.description ?? ''} onChange={(event) => updateResponsibilities(responsibilityItems.map((row, position) => position === index ? { ...row, description: event.target.value } : row))} />
              </label>
            </Row>
          ))}
        </Rows>
      </Section>

      <Section title="مسیر رشد" description="خط زمانی رویدادها">
        <Field label="عنوان بخش" value={timeline.heading ?? ''} onChange={(heading) => patchContent({ timeline: { ...timeline, heading } })} maxLength={200} />
        <Rows empty={timelineItems.length === 0} addLabel="افزودن رویداد" onAdd={() => updateTimeline([...timelineItems, { year: '', title: '', description: '' }])}>
          {timelineItems.map((milestone, index) => (
            <Row key={index} onRemove={() => updateTimeline(timelineItems.filter((_, position) => position !== index))}>
              <Field label="سال" value={milestone.year ?? ''} onChange={(year) => updateTimeline(timelineItems.map((row, position) => position === index ? { ...row, year } : row))} maxLength={20} />
              <Field label="عنوان رویداد" value={milestone.title} onChange={(title) => updateTimeline(timelineItems.map((row, position) => position === index ? { ...row, title } : row))} maxLength={200} />
              <label className="field-label page-editor-wide">توضیح
                <textarea rows={2} value={milestone.description ?? ''} onChange={(event) => updateTimeline(timelineItems.map((row, position) => position === index ? { ...row, description: event.target.value } : row))} />
              </label>
            </Row>
          ))}
        </Rows>
      </Section>

      <Section title="ماموریت و چشم‌انداز" description="دو کارت کنار هم">
        <div className="form-inline-fields">
          {(['mission', 'vision'] as const).map((key) => {
            const pillar = missionVision[key] ?? {};
            const update = (patch: Partial<NonNullable<NonNullable<PageContent['mission_vision']>[typeof key]>>) => patchContent({
              mission_vision: { ...missionVision, [key]: { ...pillar, ...patch } },
            });
            return (
              <div className="page-editor-group" key={key}>
                <span className="page-editor-group-title">{key === 'mission' ? 'ماموریت' : 'چشم‌انداز'}</span>
                <SelectField label="آیکون" value={pillar.icon ?? key} options={pillarIconOptions} onChange={(icon) => update({ icon })} />
                <Field label="عنوان" value={pillar.title ?? ''} onChange={(title) => update({ title })} maxLength={200} />
                <label className="field-label">متن
                  <textarea rows={4} value={pillar.body ?? ''} onChange={(event) => update({ body: event.target.value })} />
                </label>
              </div>
            );
          })}
        </div>
      </Section>

      <Section title="آمار" description="اعداد شاخص در نوار پایانی">
        <Field label="عنوان بخش" value={stats.heading ?? ''} onChange={(heading) => patchContent({ stats: { ...stats, heading } })} maxLength={200} />
        <Rows empty={statItems.length === 0} addLabel="افزودن آمار" onAdd={() => updateStats([...statItems, { value: '', label: '' }])}>
          {statItems.map((stat, index) => (
            <Row key={index} onRemove={() => updateStats(statItems.filter((_, position) => position !== index))}>
              <Field label="عدد" value={stat.value} onChange={(value) => updateStats(statItems.map((row, position) => position === index ? { ...row, value } : row))} maxLength={40} />
              <Field label="برچسب" value={stat.label} onChange={(label) => updateStats(statItems.map((row, position) => position === index ? { ...row, label } : row))} maxLength={120} />
            </Row>
          ))}
        </Rows>
      </Section>

      <Section title="ارتباط با ما" description="لینک‌های پایانی صفحه">
        <Field label="عنوان بخش" value={contact.heading ?? ''} onChange={(heading) => patchContent({ contact: { ...contact, heading } })} maxLength={200} />
        <label className="field-label">متن
          <textarea rows={2} value={contact.body ?? ''} onChange={(event) => patchContent({ contact: { ...contact, body: event.target.value } })} />
        </label>
        <Rows empty={linkItems.length === 0} addLabel="افزودن لینک" onAdd={() => updateLinks([...linkItems, { icon: 'external', label: '', url: '' }])}>
          {linkItems.map((link, index) => (
            <Row key={index} onRemove={() => updateLinks(linkItems.filter((_, position) => position !== index))}>
              <SelectField label="آیکون" value={link.icon ?? 'external'} options={linkIconOptions} onChange={(icon) => updateLinks(linkItems.map((row, position) => position === index ? { ...row, icon } : row))} />
              <Field label="برچسب" value={link.label} onChange={(label) => updateLinks(linkItems.map((row, position) => position === index ? { ...row, label } : row))} maxLength={150} />
              <Field label="نشانی" value={link.url} onChange={(url) => updateLinks(linkItems.map((row, position) => position === index ? { ...row, url } : row))} placeholder="/faq یا https://…" maxLength={500} wide />
            </Row>
          ))}
        </Rows>
      </Section>

      {formError && <div className="form-error">{formError}</div>}

      <div className="page-editor-actions">
        <button className="button button-primary" onClick={() => savePage.mutate(form)} disabled={savePage.isPending}>
          <Save size={16} /> {savePage.isPending ? 'در حال ذخیره…' : 'ذخیره‌ی تغییرات'} <ArrowLeft size={15} />
        </button>
        <span className="page-editor-hint"><ListPlus size={15} /> تغییرات پس از ذخیره در صفحه‌ی «درباره ما» نمایش داده می‌شوند.</span>
      </div>
    </div>
  );
}
