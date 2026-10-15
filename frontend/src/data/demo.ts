import type { Category, Product, StorePage, User } from '../types';

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
