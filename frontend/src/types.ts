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

export type DiscussionType = 'comment' | 'question';

export type DiscussionItem = {
  id: number;
  type: DiscussionType;
  parent_id: number | null;
  rating: number | null;
  body: string;
  image_url: string | null;
  author: string;
  status: 'approved' | 'pending' | 'rejected';
  is_mine: boolean;
  is_edited: boolean;
  likes_count: number;
  liked_by_me: boolean;
  created_at?: string | null;
  edited_at?: string | null;
  replies?: DiscussionItem[];
};

export type DiscussionPage = {
  items: DiscussionItem[];
  meta: { current_page: number; last_page: number; total: number };
  can_write: boolean;
  type: DiscussionType;
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

export type PrintHouse = {
  id: number;
  name: string;
  slug: string;
  city: string;
  province?: string | null;
  description?: string | null;
  capabilities: string[];
  phone?: string | null;
  email?: string | null;
  website?: string | null;
  unit_price: number;
  hardcover_surcharge: number;
  delivery_fee: number;
  delivery_days: number;
  min_quantity: number;
  max_quantity: number;
  rating: number;
  orders_count?: number;
};

export type PrintOptions = {
  paper_types: { value: string; label: string }[];
  bindings: { value: string; label: string }[];
  color_surcharge: number;
  digital_owner_discount: number;
};

export type PrintQuote = {
  unit_price: number;
  subtotal: number;
  delivery_fee: number;
  discount_amount: number;
  total_price: number;
  delivery_days: number;
  min_quantity: number;
  max_quantity: number;
  breakdown: { label: string; amount: number }[];
  digital_owner: boolean;
  print_house?: { id: number; name: string; city?: string | null };
};

export type PrintOrder = {
  id: number;
  status: 'pending' | 'confirmed' | 'printing' | 'shipped' | 'delivered' | 'cancelled' | string;
  product: { id: number; title: string; slug?: string | null; cover_url?: string | null };
  print_house: { id?: number | null; name?: string | null; city?: string | null; phone?: string | null };
  quantity: number;
  paper_type: string;
  paper_label: string;
  binding: string;
  binding_label: string;
  color_print: boolean;
  unit_price: number;
  delivery_fee: number;
  discount_amount: number;
  total_price: number;
  digital_owner: boolean;
  delivery_days: number;
  shipping: { name: string; phone: string; city: string; address: string; postal_code?: string | null };
  created_at?: string | null;
};

export type LibraryBook = {
  id: number;
  slug: string;
  title: string;
  cover_url?: string | null;
  purchased_at?: string | null;
};

export type Library = {
  product_ids: number[];
  products: LibraryBook[];
  stats: { owned_books: number; print_orders: number };
};

export type SupportTicketStatus = 'open' | 'in_progress' | 'closed';

export type TicketMessage = {
  id: number;
  body: string;
  is_staff: boolean;
  author: string;
  created_at?: string | null;
};

export type SupportTicket = {
  id: number;
  subject: string;
  status: SupportTicketStatus;
  created_at?: string | null;
  updated_at?: string | null;
  user?: { id?: number; name?: string; email?: string } | null;
  messages_count?: number | null;
  messages?: TicketMessage[];
};

export type AdminUser = {
  id: number;
  name: string;
  email: string;
  phone?: string | null;
  role: string;
  verified: boolean;
  created_at?: string | null;
  paid_orders_count: number;
  tickets_count: number;
};

export type AdminUserDetail = {
  user: AdminUser;
  total_spent: number;
  orders: { id: number; status: string; amount: number; created_at?: string | null }[];
  tickets: SupportTicket[];
};
