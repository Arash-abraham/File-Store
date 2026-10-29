import { useEffect, useState, type FormEvent } from 'react';
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { Activity, ArrowLeft, BookOpen, Check, ClipboardList, Eye, FilePlus2, FileText, LayoutDashboard, MessageSquareText, Paperclip, Pencil, Plus, Search, Settings2, ShieldCheck, Ticket, Trash2, UploadCloud, Users, X, XCircle } from 'lucide-react';
import { Link } from 'react-router-dom';
import { useStore } from '../StoreContext';
import PagesEditor from '../components/PagesEditor';
import { demoCategories, demoProducts } from '../data/demo';
import AdminOverview, { demoDashboard, type AdminDashboard, type AdminTabKey } from '../components/admin/AdminOverview';
import AdminTickets from '../components/admin/AdminTickets';
import AdminTaskCenter from '../components/admin/AdminTaskCenter';
import AdminUsers from '../components/admin/AdminUsers';
import { api, apiErrorMessage, demoMode } from '../lib/api';
import { formatDate, formatPrice } from '../lib/format';
import type { Category, Product } from '../types';

type AdminTab = AdminTabKey;
type ProductForm = { title: string; slug: string; category_id: string; original_price: string; price: string; cover_url: string; description: string; status: 'active' | 'inactive' | 'draft' };
type AdminReview = {
  id: number;
  rating: number | null;
  body: string;
  status: 'approved' | 'pending' | 'rejected';
  type?: 'comment' | 'question';
  parent_id?: number | null;
  parent?: { id: number; body: string; type?: 'comment' | 'question'; user?: { name?: string } } | null;
  image_url?: string | null;
  edited_at?: string | null;
  replies_count?: number;
  created_at?: string;
  user?: { name?: string };
  product?: { title?: string };
};
type ReviewFilter = 'all' | 'pending' | 'approved' | 'rejected';
type ReviewTypeFilter = 'all' | 'comment' | 'question' | 'reply';
type AdminFile = { id: number; name: string; type: string; size_label?: string };

const emptyForm = (): ProductForm => ({ title: '', slug: '', category_id: String(demoCategories[0]?.id ?? 1), original_price: '', price: '', cover_url: '', description: '', status: 'active' });

export default function AdminPage() {
  const { user, setLoginOpen, setNotice } = useStore();
  const queryClient = useQueryClient();
  const [tab, setTab] = useState<AdminTab>('overview');
  const [showProductForm, setShowProductForm] = useState(false);
  const [editingProduct, setEditingProduct] = useState<Product | null>(null);
  const [search, setSearch] = useState('');
  const [formError, setFormError] = useState('');
  const [form, setForm] = useState<ProductForm>(emptyForm);
  const [demoProductList, setDemoProductList] = useState<Product[]>(demoProducts);
  const [demoFilesByProduct, setDemoFilesByProduct] = useState<Record<number, AdminFile[]>>({});
  const [fileToUpload, setFileToUpload] = useState<File | null>(null);
  const [fileInputKey, setFileInputKey] = useState(0);
  const [fileDisplayName, setFileDisplayName] = useState('');
  const [fileType, setFileType] = useState('pdf');
  const [ticketSeed, setTicketSeed] = useState('');
  const [demoReviewList, setDemoReviewList] = useState<AdminReview[]>(demoReviews);
  const [reviewFilter, setReviewFilter] = useState<ReviewFilter>('pending');
  const [reviewTypeFilter, setReviewTypeFilter] = useState<ReviewTypeFilter>('all');

  const dashboardQuery = useQuery<AdminDashboard>({
    queryKey: ['admin-dashboard'],
    enabled: user?.role === 'admin',
    refetchInterval: demoMode ? false : 60000,
    queryFn: async () => demoMode ? demoDashboard() : (await api.get('/admin/dashboard')).data.data as AdminDashboard,
  });
  const productsQuery = useQuery<Product[]>({
    queryKey: ['admin-products'],
    enabled: user?.role === 'admin' && tab === 'products',
    queryFn: async () => demoMode ? demoProductList : (await api.get('/admin/products')).data.data as Product[],
  });
  const categoriesQuery = useQuery<Category[]>({
    queryKey: ['admin-categories'],
    enabled: user?.role === 'admin' && showProductForm,
    queryFn: async () => demoMode ? demoCategories : (await api.get('/admin/categories')).data.data as Category[],
  });
  const filesQuery = useQuery<AdminFile[]>({
    queryKey: ['admin-product-files', editingProduct?.id],
    enabled: user?.role === 'admin' && showProductForm && !!editingProduct,
    queryFn: async () => {
      const productId = editingProduct?.id;
      if (!productId) return [];
      return demoMode
        ? demoFilesByProduct[productId] ?? []
        : (await api.get(`/admin/products/${productId}/files`)).data.data as AdminFile[];
    },
  });
  const reviewsQuery = useQuery<AdminReview[]>({
    queryKey: ['admin-reviews'],
    enabled: user?.role === 'admin' && tab === 'reviews',
    queryFn: async () => demoMode ? demoReviewList : (await api.get('/admin/reviews')).data.data as AdminReview[],
  });

  useEffect(() => {
    if (!showProductForm || editingProduct || !categoriesQuery.data?.length) return;
    setForm((current) => categoriesQuery.data!.some((category) => category.id === Number(current.category_id))
      ? current
      : { ...current, category_id: String(categoriesQuery.data![0].id) });
  }, [showProductForm, editingProduct, categoriesQuery.data]);

  const invalidateProducts = async () => {
    await queryClient.invalidateQueries({ queryKey: ['admin-products'] });
    await queryClient.invalidateQueries({ queryKey: ['storefront-home'] });
    await queryClient.invalidateQueries({ queryKey: ['products'] });
  };
  const createProduct = useMutation({
    mutationFn: async (payload: Record<string, unknown>) => api.post('/admin/products', payload),
    onSuccess: async () => { await invalidateProducts(); closeProductForm(); setNotice('کتاب جدید منتشر شد.'); },
    onError: (error) => setFormError(apiErrorMessage(error, 'ثبت کتاب انجام نشد.')),
  });
  const updateProduct = useMutation({
    mutationFn: async ({ id, payload }: { id: number; payload: Record<string, unknown> }) => api.put(`/admin/products/${id}`, payload),
    onSuccess: async () => { await invalidateProducts(); closeProductForm(); setNotice('اطلاعات کتاب به‌روز شد.'); },
    onError: (error) => setFormError(apiErrorMessage(error, 'به‌روزرسانی کتاب انجام نشد.')),
  });
  const deleteProduct = useMutation({
    mutationFn: async (id: number) => api.delete(`/admin/products/${id}`),
    onSuccess: async () => { await invalidateProducts(); setNotice('کتاب از فروشگاه حذف شد.'); },
    onError: (error) => setNotice(apiErrorMessage(error, 'حذف کتاب انجام نشد.')),
  });
  const updateReview = useMutation({
    mutationFn: async ({ id, status }: { id: number; status: AdminReview['status'] }) => api.put(`/admin/reviews/${id}`, { status }),
    onSuccess: async () => { await queryClient.invalidateQueries({ queryKey: ['admin-reviews'] }); await queryClient.invalidateQueries({ queryKey: ['admin-dashboard'] }); setNotice('وضعیت دیدگاه به‌روز شد.'); },
    onError: (error) => setNotice(apiErrorMessage(error, 'به‌روزرسانی دیدگاه انجام نشد.')),
  });
  const uploadProductFile = useMutation({
    mutationFn: async ({ productId, file, name, type }: { productId: number; file: File; name: string; type: string }) => {
      const payload = new FormData();
      payload.append('name', name || file.name);
      payload.append('file', file);
      payload.append('type', type);
      return api.post(`/admin/products/${productId}/files`, payload);
    },
    onSuccess: async (_response, variables) => {
      await queryClient.invalidateQueries({ queryKey: ['admin-product-files', variables.productId] });
      setFileToUpload(null);
      setFileInputKey((key) => key + 1);
      setFileDisplayName('');
      setNotice('فایل محصول بارگذاری شد.');
    },
    onError: (error) => setFormError(apiErrorMessage(error, 'بارگذاری فایل انجام نشد.')),
  });
  const deleteProductFile = useMutation({
    mutationFn: async ({ productId, fileId }: { productId: number; fileId: number }) => api.delete(`/admin/products/${productId}/files/${fileId}`),
    onSuccess: async (_response, variables) => {
      await queryClient.invalidateQueries({ queryKey: ['admin-product-files', variables.productId] });
      setNotice('فایل محصول حذف شد.');
    },
    onError: (error) => setNotice(apiErrorMessage(error, 'حذف فایل انجام نشد.')),
  });

  function closeProductForm() {
    setShowProductForm(false);
    setEditingProduct(null);
    setForm(emptyForm());
    setFormError('');
    setFileToUpload(null);
    setFileInputKey((key) => key + 1);
    setFileDisplayName('');
    setFileType('pdf');
  }

  function openNewProductForm() {
    setEditingProduct(null);
    setForm(emptyForm());
    setFormError('');
    setFileToUpload(null);
    setFileInputKey((key) => key + 1);
    setFileDisplayName('');
    setFileType('pdf');
    setShowProductForm(true);
  }

  function openEditProductForm(product: Product) {
    setEditingProduct(product);
    setForm({
      title: product.title,
      slug: product.slug,
      category_id: String(product.category_id),
      original_price: String(product.original_price),
      price: product.price === product.original_price ? '' : String(product.price),
      cover_url: product.cover_url ?? product.images?.[0] ?? '',
      description: product.description ?? '',
      status: product.status === 'inactive' || product.status === 'draft' ? product.status : 'active',
    });
    setFormError('');
    setFileToUpload(null);
    setFileInputKey((key) => key + 1);
    setFileDisplayName('');
    setFileType('pdf');
    setShowProductForm(true);
  }

  function submitProduct(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setFormError('');
    const generatedSlug = form.title.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || `book-${Date.now()}`;
    const originalPrice = Number(form.original_price);
    const salePrice = form.price ? Number(form.price) : null;
    const payload = {
      title: form.title.trim(),
      slug: form.slug.trim() || generatedSlug,
      category_id: Number(form.category_id),
      original_price: originalPrice,
      price: salePrice,
      description: form.description.trim(),
      status: form.status,
      availability: form.status === 'active',
      image_urls: form.cover_url.trim() ? [form.cover_url.trim()] : [],
      key_features: editingProduct?.key_features ?? [],
    };

    if (demoMode) {
      const price = salePrice ?? originalPrice;
      const category = (categoriesQuery.data ?? demoCategories).find((item) => item.id === Number(form.category_id));
      const nextProduct: Product = {
        id: editingProduct?.id ?? Date.now(),
        title: payload.title,
        slug: payload.slug,
        description: payload.description,
        category_id: payload.category_id,
        category: category ? { id: category.id, name: category.name, slug: category.slug } : null,
        original_price: originalPrice,
        price,
        discount_percent: originalPrice > price ? Math.round((1 - price / originalPrice) * 100) : 0,
        images: payload.image_urls,
        cover_url: payload.image_urls[0] ?? null,
        key_features: editingProduct?.key_features ?? [],
        rating: editingProduct?.rating ?? 0,
        reviews_count: editingProduct?.reviews_count ?? 0,
        availability: payload.availability,
        status: form.status,
      };
      const nextList = editingProduct
        ? demoProductList.map((item) => item.id === editingProduct.id ? nextProduct : item)
        : [nextProduct, ...demoProductList];
      setDemoProductList(nextList);
      queryClient.setQueryData(['admin-products'], nextList);
      closeProductForm();
      setNotice(editingProduct ? 'کتاب نمایشی به‌روز شد.' : 'کتاب نمایشی به فهرست اضافه شد.');
      return;
    }

    if (editingProduct) updateProduct.mutate({ id: editingProduct.id, payload });
    else createProduct.mutate(payload);
  }

  function submitFile(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!editingProduct || !fileToUpload) {
      setFormError('ابتدا یک فایل برای بارگذاری انتخاب کن.');
      return;
    }
    if (fileToUpload.size > 30 * 1024 * 1024) {
      setFormError('حجم فایل نباید از ۳۰ مگابایت بیشتر باشد.');
      return;
    }
    setFormError('');
    const name = fileDisplayName.trim() || fileToUpload.name;
    if (demoMode) {
      const productId = editingProduct.id;
      const nextFiles = [...(demoFilesByProduct[productId] ?? []), { id: Date.now(), name, type: fileType, size_label: formatFileSize(fileToUpload.size) }];
      setDemoFilesByProduct((current) => ({ ...current, [productId]: nextFiles }));
      queryClient.setQueryData(['admin-product-files', productId], nextFiles);
      setFileToUpload(null);
      setFileInputKey((key) => key + 1);
      setFileDisplayName('');
      setNotice('فایل نمایشی به محصول اضافه شد.');
    } else {
      uploadProductFile.mutate({ productId: editingProduct.id, file: fileToUpload, name, type: fileType });
    }
  }

  function removeProductFile(file: AdminFile) {
    if (!editingProduct || !window.confirm(`فایل «${file.name}» حذف شود؟`)) return;
    if (demoMode) {
      const productId = editingProduct.id;
      const nextFiles = (demoFilesByProduct[productId] ?? []).filter((item) => item.id !== file.id);
      setDemoFilesByProduct((current) => ({ ...current, [productId]: nextFiles }));
      queryClient.setQueryData(['admin-product-files', productId], nextFiles);
      setNotice('فایل نمایشی حذف شد.');
    } else {
      deleteProductFile.mutate({ productId: editingProduct.id, fileId: file.id });
    }
  }

  function removeProduct(product: Product) {
    if (!window.confirm(`کتاب «${product.title}» حذف شود؟`)) return;
    if (demoMode) {
      const nextList = demoProductList.filter((item) => item.id !== product.id);
      setDemoProductList(nextList);
      queryClient.setQueryData(['admin-products'], nextList);
      setNotice('کتاب نمایشی حذف شد.');
    } else {
      deleteProduct.mutate(product.id);
    }
  }


  const deleteReview = useMutation({
    mutationFn: async (id: number) => api.delete(`/admin/reviews/${id}`),
    onSuccess: async () => { await queryClient.invalidateQueries({ queryKey: ['admin-reviews'] }); setNotice('دیدگاه حذف شد.'); },
  });

  function removeReview(review: AdminReview) {
    if (!window.confirm('این مورد و پاسخ‌های آن برای همیشه حذف شود؟')) return;
    if (demoMode) {
      const nextReviews = demoReviewList.filter((item) => item.id !== review.id);
      setDemoReviewList(nextReviews);
      queryClient.setQueryData(['admin-reviews'], nextReviews);
      setNotice('دیدگاه نمایشی حذف شد.');
    } else deleteReview.mutate(review.id);
  }

  function changeReviewStatus(review: AdminReview, status: AdminReview['status']) {
    if (demoMode) {
      const nextReviews = demoReviewList.map((item) => item.id === review.id ? { ...item, status } : item);
      setDemoReviewList(nextReviews);
      queryClient.setQueryData(['admin-reviews'], nextReviews);
      setNotice('وضعیت دیدگاه نمایشی به‌روز شد.');
    } else updateReview.mutate({ id: review.id, status });
  }

  if (!user) return <section className="container account-locked section-pad"><span className="account-lock-icon"><ShieldCheck size={29} /></span><span className="eyebrow">محیط امن مدیریت</span><h1>فقط مدیرها<br /><em>وارد می‌شوند.</em></h1><p>برای ادامه با حساب مدیر وارد شوید.</p><button className="button button-primary" onClick={() => setLoginOpen(true)}>ورود مدیر <ArrowLeft size={16} /></button></section>;
  if (user.role !== 'admin') return <section className="container account-locked section-pad"><span className="eyebrow">دسترسی محدود</span><h1>این بخش برای مدیران است.</h1><Link className="button button-primary" to="/account">بازگشت به حساب کاربری <ArrowLeft size={16} /></Link></section>;

  const stats = dashboardQuery.data?.stats ?? demoDashboard().stats;
  const visibleProducts = (productsQuery.data ?? demoProductList).filter((product) => !search || product.title.toLowerCase().includes(search.toLowerCase()));
  const allReviews = reviewsQuery.data ?? demoReviewList;
  const reviews = allReviews.filter((review) => {
    if (reviewFilter !== 'all' && review.status !== reviewFilter) return false;
    if (reviewTypeFilter === 'reply') return Boolean(review.parent_id);
    if (reviewTypeFilter !== 'all' && (review.parent_id || (review.type ?? 'comment') !== reviewTypeFilter)) return false;
    return true;
  });
  const reviewStatusCounts = {
    pending: allReviews.filter((review) => review.status === 'pending').length,
    approved: allReviews.filter((review) => review.status === 'approved').length,
    rejected: allReviews.filter((review) => review.status === 'rejected').length,
  };
  const categories = categoriesQuery.data ?? demoCategories;
  const formPending = createProduct.isPending || updateProduct.isPending;

  return (
    <section className="container admin-page section-pad">
      <div className="breadcrumbs"><Link to="/">خانه</Link><span>/</span><b>پنل مدیریت</b></div>
      <div className="admin-topbar"><div><span className="eyebrow">فایل‌استور / مدیریت</span><h1>سلام، مدیر <span>✳</span></h1><p>اینجا نبض قفسه‌ها و سفارش‌ها دست توست.</p></div><Link to="/" className="button button-outline">مشاهده‌ی فروشگاه <Eye size={16} /></Link></div>
      <div className="admin-layout">
        <aside className="admin-sidebar">
          <div className="admin-sidebar-label">فضای کاری</div>
          <button onClick={() => setTab('overview')} className={tab === 'overview' ? 'active' : ''}><LayoutDashboard size={17} /> نمای کلی</button>
          <button onClick={() => setTab('tasks')} className={tab === 'tasks' ? 'active' : ''}><ClipboardList size={17} /> کارهای مدیریتی</button>
          <button onClick={() => setTab('users')} className={tab === 'users' ? 'active' : ''}><Users size={17} /> کاربران <span>{stats.users.toLocaleString('fa-IR')}</span></button>
          <button onClick={() => setTab('products')} className={tab === 'products' ? 'active' : ''}><BookOpen size={17} /> کتاب‌ها <span>{stats.products.toLocaleString('fa-IR')}</span></button>
          <button onClick={() => setTab('tickets')} className={tab === 'tickets' ? 'active' : ''}><Ticket size={17} /> پشتیبانی <span className="sidebar-count">{stats.open_tickets.toLocaleString('fa-IR')}</span></button>
          <button onClick={() => setTab('pages')} className={tab === 'pages' ? 'active' : ''}><FileText size={17} /> صفحه‌های ثابت</button>
          <button onClick={() => setTab('reviews')} className={tab === 'reviews' ? 'active' : ''}><MessageSquareText size={17} /> دیدگاه‌ها <span>{stats.pending_reviews.toLocaleString('fa-IR')}</span></button>
          <div className="admin-sidebar-rule" /><div className="admin-sidebar-label">حساب</div>
          <Link to="/account"><Settings2 size={17} /> حساب کاربری <ArrowLeft size={14} /></Link>
          <div className="admin-sidebar-note"><Activity size={17} /><span><b>همه‌چیز رو به راه است</b><small>سرویس فروشگاه فعال است.</small></span></div>
        </aside>

        <div className="admin-content">
          {tab === 'overview' && <AdminOverview data={dashboardQuery.data ?? (demoMode ? demoDashboard() : undefined)} loading={dashboardQuery.isLoading} userName={user.name} onNavigate={(next: AdminTabKey) => setTab(next)} />}

          {tab === 'tasks' && <AdminTaskCenter onNavigate={(next) => setTab(next)} />}

          {tab === 'products' && <div className="admin-panel"><div className="admin-panel-heading"><div><h2>کتاب‌های فروشگاه</h2><p>فهرست، وضعیت و اطلاعات عنوان‌های منتشرشده.</p></div><button className="button button-primary button-small" onClick={openNewProductForm}><Plus size={16} /> افزودن کتاب</button></div><div className="admin-list-toolbar"><label><Search size={16} /><input value={search} onChange={(event) => setSearch(event.target.value)} placeholder="جست‌وجوی کتاب…" /></label><span>{visibleProducts.length.toLocaleString('fa-IR')} عنوان</span></div><div className="admin-product-table"><div className="admin-table-head"><span>کتاب</span><span>دسته‌بندی</span><span>قیمت</span><span>وضعیت</span><span>عملیات</span></div>{visibleProducts.map((product) => <div className="admin-product-row" key={product.id}><div className="admin-product-cell"><span className="admin-product-cover">{product.cover_url ? <img src={product.cover_url} alt="" /> : <BookOpen size={17} />}</span><span><b>{product.title}</b><small>/{product.slug}</small></span></div><span>{product.category?.name ?? '—'}</span><b>{formatPrice(product.price)}</b><span className={`published-status ${product.status === 'active' ? '' : 'inactive'}`}><i /> {product.status === 'draft' ? 'پیش‌نویس' : product.status === 'inactive' ? 'غیرفعال' : 'منتشر'}</span><span className="admin-row-actions"><button className="row-icon-button" title="ویرایش کتاب" onClick={() => openEditProductForm(product)}><Pencil size={15} /></button><button className="row-icon-button delete" title="حذف کتاب" onClick={() => removeProduct(product)}><Trash2 size={15} /></button></span></div>)}</div></div>}

          {tab === 'pages' && <PagesEditor onNotice={setNotice} />}

          {tab === 'tickets' && <AdminTickets initialSearch={ticketSeed} key={ticketSeed} />}

          {tab === 'users' && <AdminUsers onOpenTickets={(email) => { setTicketSeed(email); setTab('tickets'); }} />}

          {tab === 'reviews' && <div className="admin-panel"><div className="admin-panel-heading"><div><h2>دیدگاه‌ها و پرسش و پاسخ</h2><p>هر دیدگاه، پرسش، پاسخ یا ویرایشی تا پیش از تأیید شما در فروشگاه نمایش داده نمی‌شود.</p></div><span className="admin-table-count">{reviews.length.toLocaleString('fa-IR')} مورد</span></div>
            <div className="admin-review-filters">
              <div className="admin-segment">
                {([['pending', `در انتظار (${reviewStatusCounts.pending.toLocaleString('fa-IR')})`], ['approved', `منتشرشده (${reviewStatusCounts.approved.toLocaleString('fa-IR')})`], ['rejected', `ردشده (${reviewStatusCounts.rejected.toLocaleString('fa-IR')})`], ['all', 'همه']] as [ReviewFilter, string][]).map(([value, label]) => <button key={value} className={reviewFilter === value ? 'active' : ''} onClick={() => setReviewFilter(value)}>{label}</button>)}
              </div>
              <div className="admin-segment">
                {([['all', 'همه‌ی انواع'], ['comment', 'دیدگاه'], ['question', 'پرسش'], ['reply', 'پاسخ‌ها']] as [ReviewTypeFilter, string][]).map(([value, label]) => <button key={value} className={reviewTypeFilter === value ? 'active' : ''} onClick={() => setReviewTypeFilter(value)}>{label}</button>)}
              </div>
            </div>
            <div className="admin-review-list">{reviews.length ? reviews.map((review) => <article key={review.id}><div className="review-avatar">{review.user?.name?.slice(0, 1) ?? 'ک'}</div><div><b>{review.user?.name ?? 'کاربر فایل‌استور'} <small>برای {review.product?.title ?? 'کتاب'}</small></b><div className="review-stars"><span className="review-type-badge">{review.parent_id ? 'پاسخ' : review.type === 'question' ? 'پرسش' : 'دیدگاه'}</span>{review.rating ? '★'.repeat(review.rating) : null}<span>{formatDate(review.created_at)}{review.edited_at ? ' · ویرایش‌شده' : ''}{review.replies_count ? ` · ${review.replies_count.toLocaleString('fa-IR')} پاسخ` : ''}</span></div>{review.parent && <small className="review-parent-note">در پاسخ به {review.parent.user?.name ?? 'کاربر'}: «{review.parent.body.slice(0, 80)}{review.parent.body.length > 80 ? '…' : ''}»</small>}<p>{review.body}</p>{review.image_url && <a className="review-image-link" href={review.image_url} target="_blank" rel="noreferrer"><Paperclip size={13} /> مشاهده‌ی تصویر پیوست</a>}</div><div className="admin-review-actions"><span className={`ticket-status ${review.status}`}>{review.status === 'approved' ? 'منتشرشده' : review.status === 'pending' ? 'در انتظار' : 'ردشده'}</span>{review.status !== 'approved' && <button className="review-action approve" title="تأیید و انتشار" onClick={() => changeReviewStatus(review, 'approved')}><Check size={15} /></button>}{review.status !== 'rejected' && <button className="review-action reject" title="رد" onClick={() => changeReviewStatus(review, 'rejected')}><XCircle size={15} /></button>}<button className="review-action delete" title="حذف" onClick={() => removeReview(review)}><Trash2 size={15} /></button></div></article>) : <p className="admin-file-empty">موردی با این فیلتر پیدا نشد.</p>}</div></div>}
        </div>
      </div>

      {showProductForm && <div className="modal-backdrop" onMouseDown={(event) => event.target === event.currentTarget && closeProductForm()}><section className="admin-form-modal" role="dialog" aria-modal="true" aria-labelledby="product-form-title"><button className="modal-close icon-button" onClick={closeProductForm} aria-label="بستن"><X size={19} /></button><span className="eyebrow">قفسه را تازه کن</span><h2 id="product-form-title">{editingProduct ? 'ویرایش کتاب' : 'افزودن کتاب جدید'}</h2><p>اطلاعات کتاب را وارد کن؛ پس از ثبت، فایل‌های دانلود را همین‌جا به محصول اضافه کن.</p><form className="form-stack" onSubmit={submitProduct}><label className="field-label">عنوان کتاب<input value={form.title} onChange={(event) => setForm({ ...form, title: event.target.value })} required maxLength={200} /></label><label className="field-label">شناسه‌ی URL<input value={form.slug} onChange={(event) => setForm({ ...form, slug: event.target.value })} placeholder="اگر خالی بماند، خودکار ساخته می‌شود" /></label><label className="field-label">دسته‌بندی<select value={form.category_id} onChange={(event) => setForm({ ...form, category_id: event.target.value })} required>{categories.map((category) => <option key={category.id} value={category.id}>{category.name}</option>)}</select></label><div className="form-inline-fields"><label className="field-label">قیمت اصلی (تومان)<input type="number" min="0" value={form.original_price} onChange={(event) => setForm({ ...form, original_price: event.target.value })} required /></label><label className="field-label">قیمت فروش (اختیاری)<input type="number" min="0" value={form.price} onChange={(event) => setForm({ ...form, price: event.target.value })} /></label></div><label className="field-label">نشانی تصویر جلد<input type="text" value={form.cover_url} onChange={(event) => setForm({ ...form, cover_url: event.target.value })} placeholder="https://… یا products/cover.png" /></label><label className="field-label">معرفی کوتاه<textarea rows={3} value={form.description} onChange={(event) => setForm({ ...form, description: event.target.value })} /></label>{editingProduct && <label className="field-label">وضعیت انتشار<select value={form.status} onChange={(event) => setForm({ ...form, status: event.target.value as ProductForm['status'] })}><option value="active">منتشرشده</option><option value="inactive">غیرفعال</option><option value="draft">پیش‌نویس</option></select></label>}{formError && <div className="form-error">{formError}</div>}<button className="button button-primary button-full" disabled={formPending}><FilePlus2 size={16} /> {formPending ? 'در حال ذخیره…' : editingProduct ? 'ذخیره‌ی تغییرات' : 'ثبت کتاب'} <ArrowLeft size={15} /></button></form>{editingProduct && <div className="admin-file-manager"><div className="admin-file-heading"><div><span className="eyebrow">کتابخانه‌ی دانلود</span><h3>فایل‌های محصول</h3></div><span className="admin-table-count">{(filesQuery.data ?? []).length.toLocaleString('fa-IR')} فایل</span></div><div className="admin-file-list">{filesQuery.isLoading ? <p className="admin-file-empty">در حال دریافت فهرست فایل‌ها…</p> : (filesQuery.data ?? []).length ? filesQuery.data!.map((file) => <div className="admin-file-row" key={file.id}><span className="admin-file-icon"><FileText size={17} /></span><span className="admin-file-name"><b>{file.name}</b><small>{file.type.toUpperCase()} · {file.size_label ?? 'اندازه نامشخص'}</small></span><button type="button" className="row-icon-button delete" title="حذف فایل" onClick={() => removeProductFile(file)} disabled={deleteProductFile.isPending}><Trash2 size={15} /></button></div>) : <p className="admin-file-empty">هنوز فایلی برای این کتاب ثبت نشده است.</p>}</div><form className="admin-upload-form" onSubmit={submitFile}><div className="form-inline-fields"><label className="field-label">نام نمایشی<input value={fileDisplayName} onChange={(event) => setFileDisplayName(event.target.value)} placeholder="در صورت خالی بودن، نام فایل استفاده می‌شود" maxLength={255} /></label><label className="field-label">نوع فایل<select value={fileType} onChange={(event) => setFileType(event.target.value)}><option value="pdf">PDF</option><option value="zip">ZIP</option><option value="rar">RAR</option><option value="mp4">MP4</option><option value="txt">TXT</option><option value="other">سایر</option></select></label></div><label className="field-label">انتخاب فایل<input key={fileInputKey} type="file" accept=".pdf,.zip,.rar,.mp4,.txt" required={!fileToUpload} onChange={(event) => { const selected = event.target.files?.[0] ?? null; setFileToUpload(selected); if (selected) { const extension = selected.name.split('.').pop()?.toLowerCase() ?? 'other'; setFileType(['pdf', 'zip', 'rar', 'mp4', 'txt'].includes(extension) ? extension : 'other'); } }} /></label>{fileToUpload && <small className="admin-file-hint">{fileToUpload.name} · {formatFileSize(fileToUpload.size)} (حداکثر ۳۰ مگابایت)</small>}<button type="submit" className="button button-outline button-small" disabled={uploadProductFile.isPending}><UploadCloud size={16} /> {uploadProductFile.isPending ? 'در حال بارگذاری…' : 'افزودن فایل به محصول'}</button></form></div>}</section></div>}
    </section>
  );
}

function formatFileSize(bytes: number) {
  if (bytes < 1024) return `${bytes} B`;
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
  return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}


const demoReviews: AdminReview[] = [
  { id: 1, rating: 5, type: 'comment', body: 'برای شروع کار با لینوکس خیلی روان و مفید بود.', status: 'pending', created_at: new Date().toISOString(), user: { name: 'مینا سلیمانی' }, product: { title: demoProducts[0].title } },
  { id: 2, rating: 4, type: 'question', body: 'آیا این نسخه روی تبلت هم خوانا است؟', status: 'pending', created_at: new Date(Date.now() - 86400000 * 2).toISOString(), user: { name: 'امیرحسین کاظمی' }, product: { title: demoProducts[1].title } },
];
