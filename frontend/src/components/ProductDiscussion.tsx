import { useEffect, useMemo, useState, type FormEvent } from 'react';
import { useInfiniteQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { CircleHelp, Clock3, ImagePlus, Heart, MessageSquareText, Pencil, Reply, Send, Star, X } from 'lucide-react';
import { api, apiErrorMessage, demoMode } from '../lib/api';
import { formatDate } from '../lib/format';
import { demoReviews } from '../data/demo';
import { useStore } from '../StoreContext';
import type { DiscussionItem, DiscussionPage, DiscussionType } from '../types';

const TYPE_LABELS: Record<DiscussionType, { singular: string; plural: string; hint: string; placeholder: string }> = {
  comment: {
    singular: 'دیدگاه',
    plural: 'دیدگاه‌ها',
    hint: 'تجربه‌ات از خواندن این کتاب را بنویس؛ می‌توانی امتیاز هم بدهی.',
    placeholder: 'نظرت درباره‌ی این کتاب چیست؟',
  },
  question: {
    singular: 'پرسش',
    plural: 'پرسش و پاسخ',
    hint: 'سؤالت درباره‌ی محتوا، فرمت فایل یا نسخه‌ی چاپی را بپرس تا خریداران و مدیریت پاسخ دهند.',
    placeholder: 'سؤالت را بنویس…',
  },
};

const MAX_IMAGE_BYTES = 4 * 1024 * 1024;

type Props = { productId: number; slug: string; type: DiscussionType };

export default function ProductDiscussion({ productId, slug, type }: Props) {
  const queryClient = useQueryClient();
  const { user, setLoginOpen, setNotice } = useStore();
  const labels = TYPE_LABELS[type];
  const queryKey = ['discussion', slug, type, user?.id ?? 'guest'];

  const query = useInfiniteQuery({
    queryKey,
    initialPageParam: 1,
    queryFn: async ({ pageParam }): Promise<DiscussionPage> => {
      if (demoMode) return demoPage(type);
      const response = await api.get(`/products/${slug}/reviews`, { params: { type, page: pageParam } });
      return response.data.data as DiscussionPage;
    },
    getNextPageParam: (last) => (last.meta.current_page < last.meta.last_page ? last.meta.current_page + 1 : undefined),
  });

  const items = query.data?.pages.flatMap((page) => page.items) ?? [];
  const canWrite = query.data?.pages[0]?.can_write ?? false;

  function refresh() {
    void queryClient.invalidateQueries({ queryKey: ['discussion', slug] });
  }

  function requireLogin() {
    setLoginOpen(true);
  }

  return (
    <div className="discussion">
      <div className="discussion-intro">
        <span className="discussion-intro-icon">{type === 'question' ? <CircleHelp size={17} /> : <MessageSquareText size={17} />}</span>
        <div>
          <b>{labels.plural}</b>
          <small>{labels.hint} همه‌ی موارد پیش از نمایش، توسط مدیریت بررسی و تأیید می‌شوند.</small>
        </div>
      </div>

      {!user ? (
        <div className="discussion-cta">
          <span>برای ثبت {labels.singular} وارد حساب کاربری‌ات شو.</span>
          <button className="button button-primary button-small" onClick={requireLogin}>ورود</button>
        </div>
      ) : query.data && !canWrite ? (
        <div className="discussion-cta muted">
          <span>فقط خریداران این کتاب می‌توانند {labels.singular} ثبت کنند.</span>
        </div>
      ) : canWrite ? (
        <Composer
          productId={productId}
          type={type}
          placeholder={labels.placeholder}
          allowRating={type === 'comment'}
          submitLabel={`ثبت ${labels.singular}`}
          onDone={refresh}
          onNotice={setNotice}
        />
      ) : null}

      {query.isLoading && <p className="discussion-empty">در حال دریافت {labels.plural}…</p>}
      {query.isError && <p className="discussion-empty">دریافت {labels.plural} با خطا روبه‌رو شد.</p>}

      {!query.isLoading && items.length === 0 && <p className="discussion-empty">هنوز {labels.singular}ی برای این کتاب ثبت نشده است.</p>}

      <div className="discussion-list">
        {items.map((item) => (
          <ThreadItem
            key={item.id}
            item={item}
            productId={productId}
            type={type}
            canWrite={canWrite}
            onChanged={refresh}
            onNotice={setNotice}
            onRequireLogin={requireLogin}
          />
        ))}
      </div>

      {query.hasNextPage && (
        <button className="button button-outline button-small discussion-more" onClick={() => void query.fetchNextPage()} disabled={query.isFetchingNextPage}>
          {query.isFetchingNextPage ? 'در حال دریافت…' : 'نمایش بیشتر'}
        </button>
      )}
    </div>
  );
}

type ThreadItemProps = {
  item: DiscussionItem;
  productId: number;
  type: DiscussionType;
  canWrite: boolean;
  onChanged: () => void;
  onNotice: (notice: string | null) => void;
  onRequireLogin: () => void;
};

function ThreadItem({ item, productId, type, canWrite, onChanged, onNotice, onRequireLogin }: ThreadItemProps) {
  const { user } = useStore();
  const [replyOpen, setReplyOpen] = useState(false);
  const [editing, setEditing] = useState(false);
  const labels = TYPE_LABELS[type];

  const like = useMutation({
    mutationFn: async () => (await api.post(`/reviews/${item.id}/helpful`)).data,
    onSuccess: (payload) => {
      onNotice(payload.message ?? null);
      onChanged();
    },
    onError: (error) => onNotice(apiErrorMessage(error)),
  });

  function toggleLike() {
    if (demoMode) return onNotice('در حالت نمایشی امکان پسندیدن فعال نیست.');
    if (!user) return onRequireLogin();
    like.mutate();
  }

  return (
    <article className={`discussion-item ${item.parent_id ? 'is-reply' : ''}`}>
      <div className="review-avatar">{item.author.slice(0, 1)}</div>
      <div className="discussion-item-body">
        <div className="review-author">
          <b>{item.author}</b>
          {item.is_mine && <span className="discussion-you">شما</span>}
          <span>{formatDate(item.created_at)}</span>
          {item.is_edited && <span className="discussion-edited">ویرایش‌شده</span>}
        </div>

        {item.is_mine && item.status === 'pending' && <div className="discussion-status pending"><Clock3 size={13} /> در انتظار تأیید مدیریت</div>}
        {item.is_mine && item.status === 'rejected' && <div className="discussion-status rejected">این مورد توسط مدیریت تأیید نشد؛ می‌توانی ویرایشش کنی و دوباره ارسال کنی.</div>}

        {item.rating !== null && item.rating > 0 && (
          <div className="stars small-stars">{[0, 1, 2, 3, 4].map((star) => <Star key={star} size={12} fill={star < (item.rating ?? 0) ? 'currentColor' : 'none'} />)}</div>
        )}

        {editing ? (
          <Composer
            productId={productId}
            type={type}
            editItem={item}
            placeholder={labels.placeholder}
            allowRating={type === 'comment' && item.parent_id === null}
            submitLabel="ذخیره‌ی ویرایش"
            onDone={() => {
              setEditing(false);
              onChanged();
            }}
            onCancel={() => setEditing(false)}
            onNotice={onNotice}
          />
        ) : (
          <>
            <p className="discussion-text">{item.body}</p>
            {item.image_url && (
              <a className="discussion-image" href={item.image_url} target="_blank" rel="noreferrer">
                <img src={item.image_url} alt="تصویر پیوست دیدگاه" loading="lazy" />
              </a>
            )}
          </>
        )}

        {!editing && (
          <div className="discussion-actions">
            <button className={`discussion-action ${item.liked_by_me ? 'is-liked' : ''}`} onClick={toggleLike} disabled={item.is_mine || like.isPending} title={item.is_mine ? 'به مورد خودتان نمی‌توانید رأی دهید' : 'پسندیدن'}>
              <Heart size={14} fill={item.liked_by_me ? 'currentColor' : 'none'} /> {item.likes_count.toLocaleString('fa-IR')}
            </button>
            {item.parent_id === null && (
              <button
                className="discussion-action"
                onClick={() => {
                  if (!user) return onRequireLogin();
                  if (!canWrite) return onNotice(`فقط خریداران می‌توانند پاسخ دهند.`);
                  setReplyOpen(!replyOpen);
                }}
              >
                <Reply size={14} /> پاسخ
              </button>
            )}
            {item.is_mine && (
              <button className="discussion-action" onClick={() => setEditing(true)}>
                <Pencil size={14} /> ویرایش
              </button>
            )}
          </div>
        )}

        {item.replies && item.replies.length > 0 && (
          <div className="discussion-replies">
            {item.replies.map((reply) => (
              <ThreadItem
                key={reply.id}
                item={reply}
                productId={productId}
                type={type}
                canWrite={canWrite}
                onChanged={onChanged}
                onNotice={onNotice}
                onRequireLogin={onRequireLogin}
              />
            ))}
          </div>
        )}

        {replyOpen && (
          <div className="discussion-reply-box">
            <Composer
              productId={productId}
              type={type}
              parentId={item.id}
              placeholder="پاسخ خود را بنویس…"
              allowRating={false}
              submitLabel="ارسال پاسخ"
              compact
              onDone={() => {
                setReplyOpen(false);
                onChanged();
              }}
              onCancel={() => setReplyOpen(false)}
              onNotice={onNotice}
            />
          </div>
        )}
      </div>
    </article>
  );
}

type ComposerProps = {
  productId: number;
  type: DiscussionType;
  placeholder: string;
  allowRating: boolean;
  submitLabel: string;
  parentId?: number;
  editItem?: DiscussionItem;
  compact?: boolean;
  onDone: () => void;
  onCancel?: () => void;
  onNotice: (notice: string | null) => void;
};

function Composer({ productId, type, placeholder, allowRating, submitLabel, parentId, editItem, compact, onDone, onCancel, onNotice }: ComposerProps) {
  const [body, setBody] = useState(editItem?.body ?? '');
  const [rating, setRating] = useState(editItem?.rating ?? 0);
  const [image, setImage] = useState<File | null>(null);
  const [removeImage, setRemoveImage] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const previewUrl = useMemo(() => (image ? URL.createObjectURL(image) : null), [image]);
  useEffect(() => () => {
    if (previewUrl) URL.revokeObjectURL(previewUrl);
  }, [previewUrl]);

  const currentImage = removeImage ? null : editItem?.image_url ?? null;

  const save = useMutation({
    mutationFn: async () => {
      const form = new FormData();
      form.append('body', body.trim());
      if (editItem) {
        if (allowRating && rating > 0) form.append('rating', String(rating));
        if (image) form.append('image', image);
        if (removeImage && !image) form.append('remove_image', '1');
        return (await api.post(`/reviews/${editItem.id}/edit`, form)).data;
      }
      form.append('product_id', String(productId));
      form.append('type', type);
      if (parentId) form.append('parent_id', String(parentId));
      if (allowRating && rating > 0) form.append('rating', String(rating));
      if (image) form.append('image', image);
      return (await api.post('/reviews', form)).data;
    },
    onSuccess: (payload) => {
      setError(null);
      onNotice(payload.message ?? 'ثبت شد.');
      if (!editItem) {
        setBody('');
        setRating(0);
        setImage(null);
      }
      onDone();
    },
    onError: (err) => setError(apiErrorMessage(err)),
  });

  function submit(event: FormEvent) {
    event.preventDefault();
    if (body.trim().length < 3) {
      setError('متن باید حداقل ۳ کاراکتر باشد.');
      return;
    }
    save.mutate();
  }

  function pickImage(file: File | null) {
    if (file && file.size > MAX_IMAGE_BYTES) {
      setError('حداکثر حجم تصویر ۴ مگابایت است.');
      return;
    }
    setError(null);
    setRemoveImage(false);
    setImage(file);
  }

  return (
    <form className={`discussion-composer ${compact ? 'is-compact' : ''}`} onSubmit={submit}>
      <textarea value={body} onChange={(event) => setBody(event.target.value)} placeholder={placeholder} rows={compact ? 2 : 3} maxLength={3000} required />

      {allowRating && (
        <div className="discussion-rating-input" role="radiogroup" aria-label="امتیاز">
          <span>امتیاز (اختیاری):</span>
          {[1, 2, 3, 4, 5].map((value) => (
            <button key={value} type="button" className={value <= rating ? 'on' : ''} onClick={() => setRating(rating === value ? 0 : value)} aria-label={`${value} ستاره`}>
              <Star size={15} fill={value <= rating ? 'currentColor' : 'none'} />
            </button>
          ))}
        </div>
      )}

      {(previewUrl || currentImage) && (
        <div className="discussion-preview">
          <img src={previewUrl ?? currentImage ?? ''} alt="پیش‌نمایش تصویر" />
          <button type="button" onClick={() => { setImage(null); setRemoveImage(true); }} aria-label="حذف تصویر"><X size={14} /> حذف تصویر</button>
        </div>
      )}

      <div className="discussion-composer-actions">
        <label className="discussion-attach">
          <ImagePlus size={15} /> {image || currentImage ? 'تعویض تصویر' : 'افزودن تصویر'}
          <input type="file" accept="image/jpeg,image/png,image/webp" onChange={(event) => pickImage(event.target.files?.[0] ?? null)} />
        </label>
        <div className="discussion-composer-buttons">
          {onCancel && <button type="button" className="button button-outline button-small" onClick={onCancel}>انصراف</button>}
          <button type="submit" className="button button-primary button-small" disabled={save.isPending}>
            <Send size={14} /> {save.isPending ? 'در حال ارسال…' : submitLabel}
          </button>
        </div>
      </div>

      {error && <div className="form-error discussion-error">{error}</div>}
    </form>
  );
}

function demoPage(type: DiscussionType): DiscussionPage {
  const items: DiscussionItem[] = type === 'comment'
    ? demoReviews.map((review) => ({
        id: review.id,
        type: 'comment',
        parent_id: null,
        rating: review.rating,
        body: review.body,
        image_url: null,
        author: review.author,
        status: 'approved',
        is_mine: false,
        is_edited: false,
        likes_count: 0,
        liked_by_me: false,
        created_at: null,
      }))
    : [];
  return { items, meta: { current_page: 1, last_page: 1, total: items.length }, can_write: false, type };
}
