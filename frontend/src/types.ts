export type User = {
  id: number;
  name: string;
  email: string;
  phone?: string | null;
  role: 'admin' | 'user';
  avatar_url?: string | null;
  email_verified_at?: string | null;
};

export type Category = {
  id: number;
  name: string;
  slug: string;
  icon?: string | null;
  color?: string | null;
  products_count: number;
};

export type Product = {
  id: number;
  title: string;
  slug: string;
  description: string;
  category_id: number;
  category?: Pick<Category, 'id' | 'name' | 'slug'> | null;
  original_price: number;
  price: number;
  discount_percent: number;
  images: string[];
  cover_url?: string | null;
  key_features: string[];
  rating: number;
  reviews_count: number;
  availability: boolean;
  status?: string;
  files_count?: number;
};

export type CartLine = {
  id: number | string;
  product_id: number;
  title: string;
  slug: string;
  cover_url?: string | null;
  quantity: number;
  unit_price: number;
  subtotal: number;
};

export type Cart = {
  id?: number;
  items: CartLine[];
  items_count: number;
  subtotal: number;
  discount: number;
  coupon_code?: string | null;
  total: number;
};

export type Review = {
  id: number;
  rating: number;
  body: string;
  author: string;
  created_at?: string;
};

export type SearchSuggestions = {
  query: string;
  products: Product[];
  categories: Category[];
};

export type PageActor = {
  name: string;
  role?: string | null;
  bio?: string | null;
};

export type PageFeature = {
  icon?: string | null;
  title: string;
  description?: string | null;
};

export type PageMilestone = {
  year?: string | null;
  title: string;
  description?: string | null;
};

export type PageStat = {
  value: string;
  label: string;
};

export type PageLink = {
  icon?: string | null;
  label: string;
  url: string;
};

export type PageContent = {
  story?: { heading?: string | null; paragraphs?: string[] } | null;
  founders?: { heading?: string | null; items?: PageActor[] } | null;
  responsibilities?: { heading?: string | null; items?: PageFeature[] } | null;
  timeline?: { heading?: string | null; items?: PageMilestone[] } | null;
  mission_vision?: {
    mission?: { icon?: string | null; title?: string | null; body?: string | null } | null;
    vision?: { icon?: string | null; title?: string | null; body?: string | null } | null;
  } | null;
  stats?: { heading?: string | null; items?: PageStat[] } | null;
  contact?: { heading?: string | null; body?: string | null; links?: PageLink[] } | null;
};

export type StorePage = {
  id: number;
  slug: string;
  title: string;
  subtitle?: string | null;
  content: PageContent;
  status?: 'draft' | 'published' | string;
  updated_at?: string | null;
};

export type Order = {
  id: number;
  status: 'pending' | 'paid' | 'failed' | 'refunded' | 'cancelled' | string;
  total_amount: number;
  discount_amount: number;
  final_amount: number;
  payment_method?: string | null;
  paid_at?: string | null;
  created_at?: string;
  items: { id: number; product_id: number; title: string; unit_price: number; quantity: number; subtotal: number }[];
};
