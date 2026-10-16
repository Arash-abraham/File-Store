import { useState, type FormEvent } from 'react';
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { Building2, Check, Factory, Handshake, Loader2, Pencil, Plus, Printer, Search, Trash2, Truck, X } from 'lucide-react';
import { useStore } from '../../StoreContext';
import { api, apiErrorMessage, demoMode } from '../../lib/api';
import { formatDate, formatPrice } from '../../lib/format';
import { printStatusLabels } from '../../lib/print';
import type { PrintHouse } from '../../types';

type AdminContract = {
  id: number;
  company_name: string;
  contact_name: string;
  phone: string;
  email?: string | null;
  city: string;
  monthly_capacity?: number | null;
  capabilities: string[];
  message?: string | null;
  status: 'pending' | 'reviewing' | 'approved' | 'rejected';
  admin_note?: string | null;
  reviewed_at?: string | null;
  print_house?: { id: number; name: string; slug: string; status: string } | null;
  created_at?: string | null;
};

type AdminPrintOrder = {
  id: number;
  status: string;
  product_title: string;
  print_house: string;
  customer: string;
  customer_email?: string | null;
  quantity: number;
  paper_type: string;
  binding: string;
  color_print: boolean;
  unit_price: number;
  delivery_fee: number;
  discount_amount: number;
  total_price: number;
  delivery_days: number;
  tracking_code?: string | null;
  shipping_name: string;
  shipping_phone: string;
  shipping_city: string;
  shipping_address: string;
  notes?: string | null;
  created_at?: string | null;
};

type View = 'houses' | 'contracts' | 'orders';

type HouseForm = {
  name: string;
  city: string;
  province: string;
  phone: string;
  email: string;
  address: string;
  description: string;
  capabilities: string;
  unit_price: string;
  hardcover_surcharge: string;
  delivery_fee: string;
  delivery_days: string;
  min_quantity: string;
  max_quantity: string;
  rating: string;
  is_featured: boolean;
  status: 'active' | 'inactive' | 'pending';
};

const emptyHouseForm = (): HouseForm => ({
  name: '', city: '', province: '', phone: '', email: '', address: '', description: '', capabilities: '',
  unit_price: '', hardcover_surcharge: '0', delivery_fee: '0', delivery_days: '7', min_quantity: '1', max_quantity: '20',
  rating: '5', is_featured: false, status: 'active',
});

const toFa = (value: number) => value.toLocaleString('fa-IR');

/** Everything the shop needs to run the printing house network. */
export default function AdminPrint({ initialView = 'houses' }: { initialView?: View }) {
  const { setNotice } = useStore();
  const queryClient = useQueryClient();
  const [view, setView] = useState<View>(initialView);
  const [search, setSearch] = useState('');
  const [showHouseForm, setShowHouseForm] = useState(false);
  const [editingHouse, setEditingHouse] = useState<PrintHouse | null>(null);
  const [houseForm, setHouseForm] = useState<HouseForm>(emptyHouseForm);
  const [houseError, setHouseError] = useState('');
  const [demoHouses, setDemoHouses] = useState<PrintHouse[]>(demoPrintHouses);
  const [demoContracts, setDemoContracts] = useState<AdminContract[]>(demoPrintContracts);
  const [demoOrders, setDemoOrders] = useState<AdminPrintOrder[]>(demoPrintOrders);

  const housesQuery = useQuery<PrintHouse[]>({
    queryKey: ['admin-print-houses'],
    enabled: !demoMode,
    queryFn: async () => (await api.get('/admin/print-houses')).data.data as PrintHouse[],
  });

  const contractsQuery = useQuery<AdminContract[]>({
    queryKey: ['admin-print-contracts'],
    enabled: !demoMode && view === 'contracts',
    queryFn: async () => (await api.get('/admin/print-contracts')).data.data as AdminContract[],
  });

  const ordersQuery = useQuery<AdminPrintOrder[]>({
    queryKey: ['admin-print-orders'],
    enabled: !demoMode && view === 'orders',
    queryFn: async () => (await api.get('/admin/print-orders')).data.data as AdminPrintOrder[],
  });

  const houses = demoMode ? demoHouses : (housesQuery.data ?? []);
  const contracts = demoMode ? demoContracts : (contractsQuery.data ?? []);
  const orders = demoMode ? demoOrders : (ordersQuery.data ?? []);

  const term = search.trim();
  const visibleHouses = houses.filter((house) => !term || `${house.name} ${house.city}`.includes(term));

  const invalidate = async () => {
    await queryClient.invalidateQueries({ queryKey: ['admin-print-houses'] });
    await queryClient.invalidateQueries({ queryKey: ['admin-print-contracts'] });
    await queryClient.invalidateQueries({ queryKey: ['admin-print-orders'] });
    await queryClient.invalidateQueries({ queryKey: ['print-directory'] });
  };

  const saveHouse = useMutation({
    mutationFn: async (payload: Record<string, unknown>) => {
      if (demoMode) {
        const house: PrintHouse = {
          id: editingHouse?.id ?? Math.floor(Math.random() * 9000) + 1000,
          slug: editingHouse?.slug ?? `house-${Date.now()}`,
          name: String(payload.name ?? ''),
          city: String(payload.city ?? ''),
          capabilities: (payload.capabilities as string[]) ?? [],
          unit_price: Number(payload.unit_price ?? 0),
          hardcover_surcharge: Number(payload.hardcover_surcharge ?? 0),
          delivery_fee: Number(payload.delivery_fee ?? 0),
          delivery_days: Number(payload.delivery_days ?? 0),
          min_quantity: Number(payload.min_quantity ?? 1),
          max_quantity: Number(payload.max_quantity ?? 1),
          rating: Number(payload.rating ?? 5),
          is_featured: Boolean(payload.is_featured),
          status: String(payload.status ?? 'active'),
        };
        setDemoHouses((list) => (editingHouse
          ? list.map((item) => (item.id === house.id ? house : item))
          : [house, ...list]));
        return { data: house };
      }

      return editingHouse
        ? api.put(`/admin/print-houses/${editingHouse.id}`, payload)
        : api.post('/admin/print-houses', payload);
    },
    onSuccess: async () => {
      await invalidate();
      closeHouseForm();
      setNotice(editingHouse ? 'اطلاعات چاپخانه به‌روز شد.' : 'چاپخانه ثبت شد.');
    },
    onError: (error) => setHouseError(apiErrorMessage(error, 'ثبت چاپخانه انجام نشد.')),
  });

  const removeHouse = useMutation({
    mutationFn: async (id: number) => {
      if (demoMode) {
        setDemoHouses((list) => list.filter((item) => item.id !== id));
        return { data: id };
      }
      return api.delete(`/admin/print-houses/${id}`);
    },
    onSuccess: async () => {
      await invalidate();
      setNotice('چاپخانه حذف شد.');
    },
    onError: (error) => setNotice(apiErrorMessage(error, 'حذف چاپخانه انجام نشد.')),
  });

  const updateContract = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: Record<string, unknown> }) => api.put(`/admin/print-contracts/${id}`, payload),
    onSuccess: async () => {
      await invalidate();
      setNotice('وضعیت درخواست قرارداد به‌روز شد.');
    },
    onError: (error) => setNotice(apiErrorMessage(error, 'به‌روزرسانی درخواست قرارداد انجام نشد.')),
  });

  const updateOrder = useMutation({
    mutationFn: ({ id, payload }: { id: number; payload: Record<string, unknown> }) => api.put(`/admin/print-orders/${id}`, payload),
    onSuccess: async () => {
      await invalidate();
      setNotice('وضعیت سفارش چاپی به‌روز شد.');
    },
    onError: (error) => setNotice(apiErrorMessage(error, 'به‌روزرسانی سفارش چاپی انجام نشد.')),
  });

  function openNewHouse() {
    setEditingHouse(null);
    setHouseForm(emptyHouseForm());
    setHouseError('');
    setShowHouseForm(true);
  }

  function openEditHouse(house: PrintHouse) {
    setEditingHouse(house);
    setHouseError('');
    setHouseForm({
      name: house.name,
      city: house.city,
      province: house.province ?? '',
      phone: house.phone ?? '',
      email: house.email ?? '',
      address: house.address ?? '',
      description: house.description ?? '',
      capabilities: (house.capabilities ?? []).join('، '),
      unit_price: String(house.unit_price),
      hardcover_surcharge: String(house.hardcover_surcharge),
      delivery_fee: String(house.delivery_fee),
      delivery_days: String(house.delivery_days),
      min_quantity: String(house.min_quantity),
      max_quantity: String(house.max_quantity),
      rating: String(house.rating),
      is_featured: Boolean(house.is_featured),
      status: 'active',
    });
    setShowHouseForm(true);
  }

  function closeHouseForm() {
    setShowHouseForm(false);
    setEditingHouse(null);
    setHouseError('');
  }

  function submitHouse(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setHouseError('');
    saveHouse.mutate({
      name: houseForm.name,
      city: houseForm.city,
      province: houseForm.province || null,
      phone: houseForm.phone || null,
      email: houseForm.email || null,
      address: houseForm.address || null,
      description: houseForm.description || null,
      capabilities: houseForm.capabilities.split(/[،,]/).map((item) => item.trim()).filter(Boolean),
      unit_price: Number(houseForm.unit_price),
      hardcover_surcharge: Number(houseForm.hardcover_surcharge),
      delivery_fee: Number(houseForm.delivery_fee),
      delivery_days: Number(houseForm.delivery_days),
      min_quantity: Number(houseForm.min_quantity),
      max_quantity: Number(houseForm.max_quantity),
      rating: Number(houseForm.rating),
      is_featured: houseForm.is_featured,
      status: houseForm.status,
    });
  }

  function changeContractStatus(contract: AdminContract, status: AdminContract['status']) {
    if (demoMode) {
      setDemoContracts((list) => list.map((item) => item.id === contract.id ? { ...item, status } : item));
      setNotice('وضعیت درخواست قرارداد به‌روز شد.');
      return;
    }
    updateContract.mutate({ id: contract.id, payload: { status } });
  }

  function changeOrderStatus(order: AdminPrintOrder, status: string, trackingCode?: string) {
    if (demoMode) {
      setDemoOrders((list) => list.map((item) => item.id === order.id ? { ...item, status, tracking_code: trackingCode ?? item.tracking_code } : item));
      setNotice('وضعیت سفارش چاپی به‌روز شد.');
      return;
    }
    updateOrder.mutate({ id: order.id, payload: { status, tracking_code: trackingCode ?? order.tracking_code ?? '' } });
  }

  return (
    <div className="admin-panel adm-print">
      <div className="admin-panel-heading">
        <div><h2>چاپ و چاپخانه‌ها</h2><p>چاپخانه‌های همکار، درخواست‌های قرارداد و سفارش‌های نسخه‌ی کاغذی.</p></div>
        <span className="admin-table-count">{toFa(view === 'houses' ? houses.length : view === 'contracts' ? contracts.length : orders.length)} مورد</span>
      </div>

      <div className="admin-list-toolbar">
        <div className="admin-segment">
          <button className={view === 'houses' ? 'active' : ''} onClick={() => setView('houses')}><Factory size={15} /> چاپخانه‌ها</button>
          <button className={view === 'contracts' ? 'active' : ''} onClick={() => setView('contracts')}><Handshake size={15} /> درخواست‌های قرارداد</button>
          <button className={view === 'orders' ? 'active' : ''} onClick={() => setView('orders')}><Printer size={15} /> سفارش‌های چاپی</button>
        </div>
        {view === 'houses' && (
          <>
            <label><Search size={16} /><input value={search} onChange={(event) => setSearch(event.target.value)} placeholder="جست‌وجوی چاپخانه…" /></label>
            <button className="button button-primary button-small" onClick={openNewHouse}><Plus size={16} /> افزودن چاپخانه</button>
          </>
        )}
      </div>

      {view === 'houses' && (
        <div className="admin-product-table">
          <div className="admin-table-head"><span>چاپخانه</span><span>قیمت هر نسخه</span><span>تحویل</span><span>وضعیت</span><span>عملیات</span></div>
          {visibleHouses.map((house) => (
            <div className="admin-product-row" key={house.id}>
              <div className="admin-product-cell">
                <span className="admin-product-cover"><Building2 size={17} /></span>
                <span><b>{house.name}</b><small>{house.city}{house.is_featured ? ' · ویژه' : ''}</small></span>
              </div>
              <b>{formatPrice(house.unit_price)}</b>
              <span>{toFa(house.delivery_days)} روز</span>
              <span className={`published-status ${house.status === 'active' ? '' : 'inactive'}`}><i /> {house.status === 'active' ? 'فعال' : house.status === 'pending' ? 'در انتظار' : 'غیرفعال'}</span>
              <span className="admin-row-actions">
                <button className="row-icon-button" title="ویرایش چاپخانه" onClick={() => openEditHouse(house)}><Pencil size={15} /></button>
                <button className="row-icon-button delete" title="حذف چاپخانه" onClick={() => removeHouse.mutate(house.id)} disabled={removeHouse.isPending}><Trash2 size={15} /></button>
              </span>
            </div>
          ))}
          {visibleHouses.length === 0 && <p className="admin-file-empty">هنوز چاپخانه‌ای ثبت نشده است.</p>}
        </div>
      )}

      {view === 'contracts' && (
        <div className="adm-print-contracts">
          {contractsQuery.isLoading && <p className="ov-loading">در حال بارگذاری…</p>}
          {contracts.map((contract) => (
            <article className="adm-print-contract" key={contract.id}>
              <header>
                <div><b>{contract.company_name}</b><small>{contract.contact_name} · {contract.phone} · {contract.city} · {formatDate(contract.created_at ?? undefined)}</small></div>
                <span className={`print-status ${contract.status === 'approved' ? 'delivered' : contract.status === 'rejected' ? 'cancelled' : 'pending'}`}>
                  {contract.status === 'approved' ? 'تأییدشده' : contract.status === 'rejected' ? 'ردشده' : contract.status === 'reviewing' ? 'در حال بررسی' : 'در انتظار'}
                </span>
              </header>
              {contract.capabilities.length > 0 && <div className="print-house-chips">{contract.capabilities.map((item) => <span key={item}>{item}</span>)}</div>}
              {contract.message && <p>{contract.message}</p>}
              {contract.print_house && <small className="adm-print-linked">چاپخانه‌ی ساخته‌شده: {contract.print_house.name} ({contract.print_house.status === 'active' ? 'فعال' : 'در انتظار قیمت‌گذاری'})</small>}
              <div className="admin-row-actions">
                <button className="button button-outline button-small" disabled={updateContract.isPending} onClick={() => changeContractStatus(contract, 'approved')}><Check size={15} /> تأیید و انتشار</button>
                <button className="button button-outline button-small" disabled={updateContract.isPending} onClick={() => changeContractStatus(contract, 'reviewing')}>در حال بررسی</button>
                <button className="button button-outline button-small" disabled={updateContract.isPending} onClick={() => changeContractStatus(contract, 'rejected')}>رد درخواست</button>
              </div>
            </article>
          ))}
          {contracts.length === 0 && !contractsQuery.isLoading && <p className="admin-file-empty">درخواست قراردادی ثبت نشده است.</p>}
        </div>
      )}

      {view === 'orders' && (
        <div className="admin-product-table">
          <div className="admin-table-head"><span>کتاب و مشتری</span><span>چاپخانه</span><span>مبلغ</span><span>وضعیت</span><span>عملیات</span></div>
          {ordersQuery.isLoading && <p className="ov-loading">در حال بارگذاری…</p>}
          {orders.map((order) => (
            <div className="admin-product-row" key={order.id}>
              <div className="admin-product-cell">
                <span className="admin-product-cover"><Printer size={17} /></span>
                <span><b>{order.product_title}</b><small>{order.customer} · {order.shipping_city} · {toFa(order.quantity)} نسخه</small></span>
              </div>
              <span>{order.print_house}</span>
              <b>{formatPrice(order.total_price)}</b>
              <span className={`print-status ${order.status}`}>{printStatusLabels[order.status] ?? order.status}</span>
              <span className="admin-row-actions">
                <select className="admin-status-select" value={order.status} onChange={(event) => changeOrderStatus(order, event.target.value)}>
                  {['pending', 'confirmed', 'printing', 'shipped', 'delivered', 'cancelled'].map((status) => (
                    <option key={status} value={status}>{printStatusLabels[status]}</option>
                  ))}
                </select>
                <button className="row-icon-button" title="ثبت کد رهگیری" onClick={() => {
                  const code = window.prompt('کد رهگیری مرسوله را وارد کنید:', order.tracking_code ?? '');
                  if (code !== null) changeOrderStatus(order, order.status, code);
                }}><Truck size={15} /></button>
              </span>
            </div>
          ))}
          {orders.length === 0 && !ordersQuery.isLoading && <p className="admin-file-empty">سفارش چاپی ثبت نشده است.</p>}
        </div>
      )}

      {showHouseForm && (
        <div className="modal-backdrop" onMouseDown={(event) => { if (event.target === event.currentTarget) closeHouseForm(); }}>
          <section className="admin-form-modal" role="dialog" aria-modal="true" aria-labelledby="print-house-form-title">
            <button className="modal-close icon-button" onClick={closeHouseForm} aria-label="بستن"><X size={19} /></button>
            <span className="eyebrow">شبکه‌ی چاپ</span>
            <h2 id="print-house-form-title">{editingHouse ? 'ویرایش چاپخانه' : 'افزودن چاپخانه'}</h2>
            <p>قیمت‌ها بابت چاپ هر نسخه و به تومان هستند؛ هزینه‌ی ارسال جدا محاسبه می‌شود.</p>
            <form className="form-stack" onSubmit={submitHouse}>
              <label className="field-label">نام چاپخانه<input required value={houseForm.name} onChange={(event) => setHouseForm({ ...houseForm, name: event.target.value })} maxLength={150} /></label>
              <div className="form-inline-fields">
                <label className="field-label">شهر<input required value={houseForm.city} onChange={(event) => setHouseForm({ ...houseForm, city: event.target.value })} maxLength={80} /></label>
                <label className="field-label">استان<input value={houseForm.province} onChange={(event) => setHouseForm({ ...houseForm, province: event.target.value })} maxLength={80} /></label>
              </div>
              <div className="form-inline-fields">
                <label className="field-label">تلفن<input value={houseForm.phone} onChange={(event) => setHouseForm({ ...houseForm, phone: event.target.value })} maxLength={30} /></label>
                <label className="field-label">ایمیل<input type="email" value={houseForm.email} onChange={(event) => setHouseForm({ ...houseForm, email: event.target.value })} maxLength={255} /></label>
              </div>
              <label className="field-label">نشانی<input value={houseForm.address} onChange={(event) => setHouseForm({ ...houseForm, address: event.target.value })} maxLength={255} /></label>
              <label className="field-label">توانمندی‌ها (با ویرگول جدا کنید)<input value={houseForm.capabilities} onChange={(event) => setHouseForm({ ...houseForm, capabilities: event.target.value })} placeholder="گلاسه، چاپ رنگی، صحافی سخت" /></label>
              <label className="field-label">معرفی کوتاه<textarea rows={2} value={houseForm.description} onChange={(event) => setHouseForm({ ...houseForm, description: event.target.value })} maxLength={2000} /></label>
              <div className="form-inline-fields">
                <label className="field-label">قیمت هر نسخه (تومان)<input type="number" min="0" value={houseForm.unit_price} onChange={(event) => setHouseForm({ ...houseForm, unit_price: event.target.value })} required /></label>
                <label className="field-label">هزینه‌ی جلد سخت<input type="number" min="0" value={houseForm.hardcover_surcharge} onChange={(event) => setHouseForm({ ...houseForm, hardcover_surcharge: event.target.value })} /></label>
              </div>
              <div className="form-inline-fields">
                <label className="field-label">هزینه‌ی ارسال<input type="number" min="0" value={houseForm.delivery_fee} onChange={(event) => setHouseForm({ ...houseForm, delivery_fee: event.target.value })} /></label>
                <label className="field-label">روزهای تحویل<input type="number" min="1" value={houseForm.delivery_days} onChange={(event) => setHouseForm({ ...houseForm, delivery_days: event.target.value })} /></label>
              </div>
              <div className="form-inline-fields">
                <label className="field-label">حداقل تیراژ<input type="number" min="1" value={houseForm.min_quantity} onChange={(event) => setHouseForm({ ...houseForm, min_quantity: event.target.value })} /></label>
                <label className="field-label">حداکثر تیراژ<input type="number" min="1" value={houseForm.max_quantity} onChange={(event) => setHouseForm({ ...houseForm, max_quantity: event.target.value })} /></label>
              </div>
              <div className="form-inline-fields">
                <label className="field-label">امتیاز<input type="number" min="0" max="5" step="0.1" value={houseForm.rating} onChange={(event) => setHouseForm({ ...houseForm, rating: event.target.value })} /></label>
                <label className="field-label">وضعیت<select value={houseForm.status} onChange={(event) => setHouseForm({ ...houseForm, status: event.target.value as HouseForm['status'] })}>
                  <option value="active">فعال</option>
                  <option value="pending">در انتظار قیمت‌گذاری</option>
                  <option value="inactive">غیرفعال</option>
                </select></label>
              </div>
              <button type="button" className={`toggle-row ${houseForm.is_featured ? 'is-on' : ''}`} onClick={() => setHouseForm({ ...houseForm, is_featured: !houseForm.is_featured })}>
                <span><b>چاپخانه‌ی ویژه</b><small>در ابتدای فهرست فروشگاه نمایش داده می‌شود.</small></span>
                <span className={`switch ${houseForm.is_featured ? 'is-on' : ''}`}><i /></span>
              </button>
              {houseError && <div className="form-error">{houseError}</div>}
              <button className="button button-primary button-full" disabled={saveHouse.isPending}>
                {saveHouse.isPending ? <><Loader2 size={16} className="search-spinner" /> در حال ذخیره…</> : <>{editingHouse ? 'ذخیره‌ی تغییرات' : 'ثبت چاپخانه'}</>}
              </button>
            </form>
          </section>
        </div>
      )}
    </div>
  );
}

const demoPrintHouses: PrintHouse[] = [
  { id: 1, name: 'چاپخانه‌ی نقش جهان', slug: 'naqsh-jahan', city: 'تهران', province: 'تهران', capabilities: ['گلاسه', 'چاپ رنگی'], unit_price: 245000, hardcover_surcharge: 95000, delivery_fee: 65000, delivery_days: 5, min_quantity: 1, max_quantity: 10, rating: 4.8, is_featured: true, orders_count: 12 },
  { id: 2, name: 'چاپخانه‌ی مدرن شیراز', slug: 'modern-shiraz', city: 'شیراز', province: 'فارس', capabilities: ['تحویل سریع'], unit_price: 232000, hardcover_surcharge: 84000, delivery_fee: 60000, delivery_days: 4, min_quantity: 1, max_quantity: 12, rating: 4.9, orders_count: 7 },
];

const demoPrintContracts: AdminContract[] = [
  { id: 1, company_name: 'چاپ و نشر البرز', contact_name: 'سارا محمدی', phone: '02122334455', email: 'info@alborz-print.ir', city: 'کرج', monthly_capacity: 12000, capabilities: ['شومیز', 'فنر'], message: 'ظرفیت چاپ رنگی و صحافی سخت داریم.', status: 'pending', created_at: new Date().toISOString(), print_house: null },
];

const demoPrintOrders: AdminPrintOrder[] = [
  {
    id: 1, status: 'pending', product_title: 'لینوکس برای هکرها', print_house: 'چاپخانه‌ی نقش جهان', customer: 'آرمان رضایی', customer_email: 'user@example.com',
    quantity: 1, paper_type: 'premium', binding: 'hardcover', color_print: false, unit_price: 400000, delivery_fee: 65000, discount_amount: 60000,
    total_price: 405000, delivery_days: 5, tracking_code: null, shipping_name: 'آرمان رضایی', shipping_phone: '09123334444',
    shipping_city: 'تهران', shipping_address: 'تهران، خیابان آزادی، پلاک ۱۲', notes: null, created_at: new Date().toISOString(),
  },
];
