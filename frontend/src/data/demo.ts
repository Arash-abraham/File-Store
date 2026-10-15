import type { Category, PrintHouse, Product, StorePage, User } from '../types';

export const demoCategories: Category[] = [
  { id: 1, name: 'برنامه‌نویسی', slug: 'programming', icon: 'code', color: 'indigo', products_count: 3 },
  { id: 2, name: 'کسب‌وکار و رشد فردی', slug: 'business', icon: 'briefcase', color: 'amber', products_count: 0 },
  { id: 3, name: 'تاریخ و ادبیات', slug: 'history-literature', icon: 'book-open', color: 'rose', products_count: 1 },
  { id: 4, name: 'طراحی و خلاقیت', slug: 'design', icon: 'palette', color: 'violet', products_count: 0 },
  { id: 5, name: 'علم و ریاضی', slug: 'science', icon: 'sparkles', color: 'cyan', products_count: 1 },
];

export const demoProducts: Product[] = [
  {
    id: 1,
    title: 'لینوکس برای هکرها',
    slug: 'linux-for-hackers',
    description: 'از خط فرمان تا ابزارهای پایه‌ی امنیت؛ یک شروع روشن و کاربردی برای ورود به دنیای لینوکس.',
    category_id: 1,
    category: { id: 1, name: 'برنامه‌نویسی', slug: 'programming' },
    original_price: 890000,
    price: 690000,
    discount_percent: 22,
    images: ['/images/books/linux-basics.svg'],
    cover_url: '/images/books/linux-basics.svg',
    key_features: ['آشنایی با خط فرمان لینوکس', 'مدیریت فایل و دسترسی‌ها', 'مناسب برای شروع مسیر امنیت'],
    rating: 4.9,
    reviews_count: 128,
    availability: true,
    files_count: 1,
  },
  {
    id: 2,
    title: 'HTTP؛ راهنمای قطعی وب',
    slug: 'http-definitive-guide',
    description: 'مرجعی خوش‌ساخت برای شناخت پروتکل HTTP و سازوکارهایی که وب مدرن را سرپا نگه می‌دارند.',
    category_id: 1,
    category: { id: 1, name: 'برنامه‌نویسی', slug: 'programming' },
    original_price: 740000,
    price: 590000,
    discount_percent: 20,
    images: ['/images/books/http-definitive-guide.png'],
    cover_url: '/images/books/http-definitive-guide.png',
    key_features: ['مبانی HTTP و ارتباطات وب', 'مثال‌های عملی', 'مناسب برای توسعه‌دهندگان'],
    rating: 4.8,
    reviews_count: 96,
    availability: true,
    files_count: 1,
  },
  {
    id: 3,
    title: 'آشپزخانه‌ی NGINX',
    slug: 'nginx-cookbook',
    description: 'دستورالعمل‌های عملی برای پیکربندی، بهینه‌سازی و مدیریت ترافیک با NGINX.',
    category_id: 1,
    category: { id: 1, name: 'برنامه‌نویسی', slug: 'programming' },
    original_price: 980000,
    price: 790000,
    discount_percent: 19,
    images: ['/images/books/nginx-cookbook.png'],
    cover_url: '/images/books/nginx-cookbook.png',
    key_features: ['پیکربندی سرور وب', 'بهینه‌سازی و کش', 'تعادل بار و امنیت'],
    rating: 4.7,
    reviews_count: 74,
    availability: true,
    files_count: 1,
  },
  {
    id: 4,
    title: 'نادرشاه افشار',
    slug: 'nader-shah-afshar',
    description: 'روایتی خواندنی از زندگی و زمانه‌ی یکی از تأثیرگذارترین چهره‌های تاریخ ایران.',
    category_id: 3,
    category: { id: 3, name: 'تاریخ و ادبیات', slug: 'history-literature' },
    original_price: 420000,
    price: 350000,
    discount_percent: 17,
    images: ['/images/books/nader-shah.png'],
    cover_url: '/images/books/nader-shah.png',
    key_features: ['روایت تاریخی مستند', 'صفحه‌آرایی باکیفیت', 'نسخه‌ی دیجیتال قابل دریافت'],
    rating: 4.6,
    reviews_count: 52,
    availability: true,
    files_count: 1,
  },
  {
    id: 5,
    title: 'ریاضیات جامع تجربی',
    slug: 'comprehensive-math',
    description: 'مرور مفهومی و تمرین‌محور مباحث منتخب ریاضی برای مطالعه‌ی هدفمند.',
    category_id: 5,
    category: { id: 5, name: 'علم و ریاضی', slug: 'science' },
    original_price: 520000,
    price: 430000,
    discount_percent: 17,
    images: ['/images/books/math-complete.png'],
    cover_url: '/images/books/math-complete.png',
    key_features: ['درس‌نامه‌ی طبقه‌بندی‌شده', 'تمرین‌های متنوع', 'مناسب مرور و جمع‌بندی'],
    rating: 4.5,
    reviews_count: 39,
    availability: true,
    files_count: 1,
  },
];

export const demoReviews = [
  { id: 1, rating: 5, body: 'مطالب کتاب خوش‌ساخت و کاربردی است؛ برای شروع کار با لینوکس انتخاب خیلی خوبی بود.', author: 'مریم نادری' },
  { id: 2, rating: 5, body: 'چیدمان صفحه‌ها عالی است و نسخه‌ی دیجیتال روی تبلت هم خوانایی خوبی دارد.', author: 'پارسا کاظمی' },
  { id: 3, rating: 4, body: 'محتوای خوبی دارد. دوست داشتم چند مثال عملی بیشتر هم داخلش بود.', author: 'آرمان رضایی' },
] satisfies { id: number; rating: number; body: string; author: string }[];

export const demoUser: User = {
  id: 1,
  name: 'آرمان رضایی',
  email: 'user@example.com',
  phone: '09123334444',
  role: 'user',
  email_verified_at: new Date().toISOString(),
};

export const demoAdmin: User = {
  id: 2,
  name: 'مدیر فایل‌استور',
  email: 'admin@example.com',
  role: 'admin',
  email_verified_at: new Date().toISOString(),
};

/**
 * Mirror of `PagesSeeder` so the storefront stays explorable in demo mode.
 */
export const demoAboutPage: StorePage = {
  id: 1,
  slug: 'about',
  title: 'درباره ما',
  subtitle: 'داستان شکل‌گیری و مسیر رشد فایل‌استور',
  status: 'published',
  updated_at: new Date().toISOString(),
  content: {
    story: {
      heading: 'آغاز یک داستان موفق',
      paragraphs: ['در سال ۱۳۹۹، آرش و امیرحسین با یک ایده‌ی ساده اما قدرتمند تصمیم گرفتند فروشگاه آنلاین خود را در قالب فروش پی‌دی‌اف کتاب راه‌اندازی کنند. آن‌ها باور داشتند که دانش باید به راحتی در دسترس همه قرار گیرد و کتاب‌های دیجیتال می‌توانند این امکان را فراهم کنند.'],
    },
    founders: {
      heading: 'بنیان‌گذاران',
      items: [
        { name: 'آرش', role: 'مؤسس و مدیر فنی', bio: 'آرش با تخصص در زمینه‌ی فناوری اطلاعات و برنامه‌نویسی، مسئولیت توسعه‌ی پلتفرم و اطمینان از عملکرد بهینه‌ی سایت را بر عهده دارد.' },
        { name: 'امیرحسین', role: 'مؤسس و مدیر محتوا', bio: 'امیرحسین با دانش گسترده در حوزه‌ی برنامه‌نویسی و بازاریابی، مسئولیت انتخاب کتاب‌ها و ارتباط با ناشران و نویسندگان را بر عهده دارد.' },
      ],
    },
    responsibilities: {
      heading: 'مسئولیت‌های مشترک',
      items: [
        { icon: 'strategy', title: 'توسعه استراتژی کسب‌وکار', description: 'هر دو مؤسس در تدوین و اجرای استراتژی‌های کلان کسب‌وکار نقش فعالی دارند.' },
        { icon: 'team', title: 'مدیریت تیم', description: 'رهبری و هدایت تیم‌های فنی و محتوایی به صورت مشترک انجام می‌شود.' },
        { icon: 'growth', title: 'برنامه‌ریزی رشد', description: 'تعیین اهداف کوتاه‌مدت و بلندمدت و نظارت بر تحقق آن‌ها.' },
        { icon: 'innovation', title: 'نوآوری و توسعه', description: 'هر دو در شناسایی فرصت‌های جدید و توسعه‌ی خدمات نوین مشارکت دارند.' },
      ],
    },
    timeline: {
      heading: 'مسیر رشد ما',
      items: [
        { year: '۱۳۹۹', title: 'شروع فعالیت', description: 'آغاز فعالیت با مجموعه‌ای کوچک از کتاب‌های پی‌دی‌اف.' },
        { year: '۱۴۰۰', title: 'افزایش محبوبیت', description: 'رشد چشمگیر کاربران و اضافه شدن کتاب‌های جدید به مجموعه.' },
        { year: '۱۴۰۱', title: 'توسعه‌ی پلتفرم', description: 'بهبود رابط کاربری و اضافه شدن قابلیت‌های جدید.' },
        { year: '۱۴۰۲', title: 'دستاوردهای جدید', description: 'همکاری با ناشران معتبر و ارائه‌ی بیش از ۱۰۰۰ عنوان کتاب.' },
      ],
    },
    mission_vision: {
      mission: { icon: 'mission', title: 'ماموریت ما', body: 'ما باور داریم که دسترسی به دانش باید برای همه آسان و مقرون به صرفه باشد. ماموریت ما ارائه‌ی کتاب‌های دیجیتال با کیفیت بالا، با قیمت مناسب و ایجاد تجربه‌ای لذت‌بخش برای مطالعه است.' },
      vision: { icon: 'vision', title: 'چشم‌انداز ما', body: 'چشم‌انداز ما تبدیل شدن به بزرگ‌ترین پلتفرم فروش کتاب‌های دیجیتال در ایران و ارائه‌ی خدمات نوآورانه برای بهبود تجربه‌ی مطالعه‌ی دیجیتال است.' },
    },
    stats: {
      heading: 'دستاوردهای ما در یک نگاه',
      items: [
        { value: '۳+', label: 'سال تجربه' },
        { value: '۱۰۰۰+', label: 'عنوان کتاب' },
        { value: '۵۰ هزار+', label: 'کاربر راضی' },
        { value: '۹۸٪', label: 'رضایت‌مندی' },
      ],
    },
    contact: {
      heading: 'کد پروژه را ببین',
      body: 'برای دیدن کد فایل‌استور، خبر گرفتن از تغییرات یا فرستادن پیشنهاد، از لینک‌های زیر استفاده کن.',
      links: [
        { icon: 'code', label: 'مخزن فایل‌استور', url: 'https://github.com/Arash-abraham/File-Store' },
        { icon: 'github', label: 'پروفایل آرش', url: 'https://github.com/Arash-abraham' },
        { icon: 'faq', label: 'پرسش‌های پرتکرار', url: '/faq' },
      ],
    },
  },
};

/**
 * Mirror of `PrintHousesSeeder` so the print section works in demo mode.
 */
export const demoPrintHouses: PrintHouse[] = [
  { id: 1, name: 'چاپخانه‌ی نقش جهان', slug: 'naqsh-jahan', city: 'تهران', province: 'تهران', description: 'چاپ و صحافی کتاب با کاغذ تحریر و گلاسه؛ تیراژ کم و سفارش تک‌نسخه هم می‌پذیریم.', capabilities: ['کاغذ تحریر', 'گلاسه', 'صحافی سخت', 'چاپ رنگی'], phone: '021-55667788', email: 'order@naqshjahan.example', website: 'https://naqshjahan.example', unit_price: 245000, hardcover_surcharge: 95000, delivery_fee: 65000, delivery_days: 5, min_quantity: 1, max_quantity: 10, rating: 4.8, orders_count: 42 },
  { id: 2, name: 'مجموعه‌ی چاپ آفتاب', slug: 'aftab-print', city: 'اصفهان', province: 'اصفهان', description: 'تخصص ما چاپ رنگی و جلد سخت برای کتاب‌های هنری و دانشگاهی است.', capabilities: ['چاپ رنگی', 'صحافی سخت', 'گلاسه', 'ارسال سراسری'], phone: '031-32233445', email: 'hello@aftabprint.example', website: null, unit_price: 268000, hardcover_surcharge: 78000, delivery_fee: 72000, delivery_days: 6, min_quantity: 1, max_quantity: 15, rating: 4.6, orders_count: 28 },
  { id: 3, name: 'چاپ و صحافی پارس', slug: 'pars-print', city: 'مشهد', province: 'خراسان رضوی', description: 'قیمت مناسب برای سفارش‌های چندنسخه‌ای با کاغذ کرافت و صحافی فنر.', capabilities: ['کاغذ کرافت', 'صحافی فنر', 'تیراژ بالا', 'قیمت اقتصادی'], phone: '051-37654321', email: 'salam@parsprint.example', website: null, unit_price: 198000, hardcover_surcharge: 88000, delivery_fee: 58000, delivery_days: 7, min_quantity: 2, max_quantity: 20, rating: 4.4, orders_count: 61 },
  { id: 4, name: 'چاپخانه‌ی مدرن شیراز', slug: 'modern-shiraz', city: 'شیراز', province: 'فارس', description: 'چاپ سریع کتاب با تحویل یک‌هفته‌ای و بسته‌بندی شکیل برای هدیه.', capabilities: ['تحویل سریع', 'بسته‌بندی هدیه', 'کاغذ تحریر', 'چاپ رنگی'], phone: '071-36221100', email: 'info@modernshiraz.example', website: 'https://modernshiraz.example', unit_price: 232000, hardcover_surcharge: 84000, delivery_fee: 60000, delivery_days: 4, min_quantity: 1, max_quantity: 12, rating: 4.9, orders_count: 35 },
  { id: 5, name: 'چاپخانه‌ی تبریز آرا', slug: 'tabriz-ara', city: 'تبریز', province: 'آذربایجان شرقی', description: 'چاپ تخصصی کتاب‌های فنی و مهندسی با نقشه‌ها و جدول‌های تخصصی.', capabilities: ['کتاب فنی', 'کاغذ گلاسه', 'صحافی سخت', 'تیراژ بالا'], phone: '041-33344556', email: 'base@tabrizara.example', website: null, unit_price: 255000, hardcover_surcharge: 90000, delivery_fee: 68000, delivery_days: 8, min_quantity: 1, max_quantity: 20, rating: 4.5, orders_count: 19 },
];

export const demoPrintOptions = {
  paper_types: [
    { value: 'standard', label: 'کاغذ تحریر معمولی' },
    { value: 'cream', label: 'کاغذ کرافت/کرم' },
    { value: 'premium', label: 'کاغذ گلاسه‌ی درجه‌یک' },
  ],
  bindings: [
    { value: 'paperback', label: 'شومیز' },
    { value: 'hardcover', label: 'سخت (گالینگور)' },
    { value: 'spiral', label: 'فنر' },
  ],
  color_surcharge: 45000,
  digital_owner_discount: 0.15,
};

/**
 * Demo ownership: books the demo reader supposedly bought already.
 */
export const demoOwnedProductIds: number[] = [2];

/**
 * Demo print orders shown in the account area before the API is connected.
 */
export const demoPrintOrders = [
  {
    id: 3101,
    status: 'printing',
    product: { id: 1, title: 'لینوکس برای هکرها', slug: 'linux-for-hackers', cover_url: '/images/books/linux-basics.svg' },
    print_house: { id: 1, name: 'چاپخانه‌ی نقش جهان', city: 'تهران', phone: '021-55667788' },
    quantity: 1,
    paper_type: 'premium',
    paper_label: 'کاغذ گلاسه‌ی درجه‌یک',
    binding: 'hardcover',
    binding_label: 'سخت (گالینگور)',
    color_print: false,
    unit_price: 340000,
    delivery_fee: 65000,
    discount_amount: 51000,
    total_price: 354000,
    digital_owner: true,
    delivery_days: 5,
    shipping: { name: 'آرمان رضایی', phone: '09123334444', city: 'تهران', address: 'خیابان آزادی، پلاک ۱۲', postal_code: '1418754512' },
    created_at: new Date(Date.now() - 86400000 * 3).toISOString(),
  },
];
