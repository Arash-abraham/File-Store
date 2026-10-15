import { createContext, useCallback, useContext, useEffect, useMemo, useState, type ReactNode } from 'react';
import { api, apiErrorMessage, demoMode, requestCsrfCookie } from './lib/api';
import { demoAdmin, demoUser } from './data/demo';
import type { Cart, Product, User } from './types';

type CheckoutResult = { redirect?: string; orderId?: number | string; paid?: boolean };
type Theme = 'light' | 'dark';

type StoreContextValue = {
  theme: Theme;
  toggleTheme: () => void;
  user: User | null;
  cart: Cart;
  loginOpen: boolean;
  notice: string | null;
  setLoginOpen: (open: boolean) => void;
  setNotice: (notice: string | null) => void;
  login: (email: string, password: string) => Promise<void>;
  register: (name: string, email: string, password: string, passwordConfirmation: string) => Promise<void>;
  logout: () => Promise<void>;
  updateUser: (user: User) => void;
  addToCart: (product: Product) => Promise<void>;
  updateQuantity: (id: number | string, quantity: number) => Promise<void>;
  removeFromCart: (id: number | string) => Promise<void>;
  applyCoupon: (code: string) => Promise<void>;
  removeCoupon: () => Promise<void>;
  checkout: (method: 'zarinpal' | 'wallet') => Promise<CheckoutResult>;
};

const emptyCart: Cart = { items: [], items_count: 0, subtotal: 0, discount: 0, coupon_code: null, total: 0 };
const StoreContext = createContext<StoreContextValue | null>(null);

function readJson<T>(key: string, fallback: T): T {
  try {
    const raw = localStorage.getItem(key);
    return raw ? (JSON.parse(raw) as T) : fallback;
  } catch {
    return fallback;
  }
}

function getInitialTheme(): Theme {
  try {
    const savedTheme = localStorage.getItem('fs-theme');
    if (savedTheme === 'light' || savedTheme === 'dark') return savedTheme;
  } catch {
    // Fall back to the system preference when browser storage is unavailable.
  }
  return window.matchMedia?.('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

function normalizeCart(input: Partial<Cart> | undefined): Cart {
  if (!input) return emptyCart;
  return {
    id: input.id,
    items: input.items ?? [],
    items_count: input.items_count ?? (input.items ?? []).reduce((sum, item) => sum + item.quantity, 0),
    subtotal: input.subtotal ?? 0,
    discount: input.discount ?? 0,
    coupon_code: input.coupon_code ?? null,
    total: input.total ?? 0,
  };
}

export function StoreProvider({ children }: { children: ReactNode }) {
  const [theme, setTheme] = useState<Theme>(getInitialTheme);
  const [user, setUser] = useState<User | null>(() => demoMode ? readJson<User | null>('fs-demo-user', null) : null);
  const [cart, setCart] = useState<Cart>(() => demoMode ? normalizeCart(readJson<Cart>('fs-demo-cart', emptyCart)) : emptyCart);
  const [loginOpen, setLoginOpen] = useState(false);
  const [notice, setNotice] = useState<string | null>(null);

  useEffect(() => {
    document.documentElement.dataset.theme = theme;
    document.querySelector('meta[name="theme-color"]')?.setAttribute('content', theme === 'dark' ? '#121a16' : '#f8f8f5');
    try {
      localStorage.setItem('fs-theme', theme);
    } catch {
      // The theme remains active for this visit if browser storage is unavailable.
    }
  }, [theme]);

  const toggleTheme = useCallback(() => {
    setTheme((currentTheme) => currentTheme === 'dark' ? 'light' : 'dark');
  }, []);

  const refreshCart = useCallback(async () => {
    if (demoMode || !user) return;
    try {
      const response = await api.get('/cart');
      setCart(normalizeCart(response.data.data));
    } catch {
      setCart(emptyCart);
    }
  }, [user]);

  useEffect(() => {
    if (demoMode) {
      localStorage.setItem('fs-demo-cart', JSON.stringify(cart));
      localStorage.setItem('fs-demo-user', JSON.stringify(user));
    }
  }, [cart, user]);

  useEffect(() => {
    if (!demoMode && user) {
      void refreshCart();
    } else if (!demoMode) {
      setCart(emptyCart);
    }
  }, [user, refreshCart]);

  useEffect(() => {
    if (!demoMode) {
      api.get('/auth/me')
        .then((response) => setUser(response.data.data.user as User))
        .catch(() => setUser(null));
    }
  }, []);

  const login = useCallback(async (email: string, password: string) => {
    if (demoMode) {
      if (password !== 'password') throw new Error('گذرواژه‌ی آزمایشی «password» است.');
      const normalized = email.trim().toLowerCase();
      if (normalized === 'admin@example.com') setUser(demoAdmin);
      else if (normalized === 'user@example.com') setUser(demoUser);
      else throw new Error('برای ورود آزمایشی از user@example.com یا admin@example.com استفاده کنید.');
      setLoginOpen(false);
      setNotice('خوش آمدید؛ حالت نمایشی فعال است.');
      return;
    }

    await requestCsrfCookie();
    const response = await api.post('/auth/login', { email, password });
    setUser(response.data.data.user as User);
    setLoginOpen(false);
    setNotice('با موفقیت وارد شدید.');
    await refreshCart();
  }, [refreshCart]);

  const register = useCallback(async (name: string, email: string, password: string, passwordConfirmation: string) => {
    const normalizedEmail = email.trim().toLowerCase();
    if (demoMode) {
      if (password !== passwordConfirmation) throw new Error('تکرار گذرواژه با گذرواژه یکسان نیست.');
      if (password.length < 8) throw new Error('گذرواژه باید دست‌کم ۸ نویسه باشد.');
      setUser({ ...demoUser, id: Date.now(), name: name.trim(), email: normalizedEmail });
      setLoginOpen(false);
      setNotice('حساب نمایشی شما آماده است.');
      return;
    }

    await requestCsrfCookie();
    const response = await api.post('/auth/register', {
      name: name.trim(),
      email: normalizedEmail,
      password,
      password_confirmation: passwordConfirmation,
    });
    setUser(response.data.data.user as User);
    setLoginOpen(false);
    setNotice('حساب کاربری با موفقیت ساخته شد.');
  }, []);

  const updateUser = useCallback((nextUser: User) => setUser(nextUser), []);

  const logout = useCallback(async () => {
    if (!demoMode) {
      try {
        await api.post('/auth/logout');
      } catch {
        // Clear local UI state even if the session expired on the server.
      }
    }
    setUser(null);
    setNotice('از حساب کاربری خارج شدید.');
  }, []);

  const addToCart = useCallback(async (product: Product) => {
    if (!demoMode && !user) {
      setNotice('برای افزودن کتاب به سبد خرید، ابتدا وارد حساب شوید.');
      setLoginOpen(true);
      return;
    }

    if (demoMode) {
      const existing = cart.items.find((item) => item.product_id === product.id);
      const items = existing
        ? cart.items.map((item) => item.product_id === product.id ? { ...item, quantity: item.quantity + 1, subtotal: (item.quantity + 1) * product.price } : item)
        : [...cart.items, {
            id: `demo-${product.id}`,
            product_id: product.id,
            title: product.title,
            slug: product.slug,
            cover_url: product.cover_url,
            quantity: 1,
            unit_price: product.price,
            subtotal: product.price,
          }];
      const subtotal = items.reduce((sum, item) => sum + item.subtotal, 0);
      const discount = cart.coupon_code ? Math.min(subtotal, Math.round(subtotal * (cart.coupon_code === 'WELCOME10' ? 0.1 : 0.15))) : 0;
      setCart({ ...cart, items, items_count: items.reduce((sum, item) => sum + item.quantity, 0), subtotal, discount, total: subtotal - discount });
      setNotice('کتاب به سبد خرید اضافه شد.');
      return;
    }

    try {
      const response = await api.post('/cart/items', { product_id: product.id, quantity: 1 });
      setCart(normalizeCart(response.data.data));
      setNotice('کتاب به سبد خرید اضافه شد.');
    } catch (error) {
      setNotice(apiErrorMessage(error, 'افزودن کتاب به سبد خرید انجام نشد.'));
    }
  }, [cart, user]);

  const updateQuantity = useCallback(async (id: number | string, quantity: number) => {
    const nextQuantity = Math.max(1, Math.min(20, quantity));
    if (demoMode) {
      const items = cart.items.map((item) => item.id === id ? { ...item, quantity: nextQuantity, subtotal: item.unit_price * nextQuantity } : item);
      const subtotal = items.reduce((sum, item) => sum + item.subtotal, 0);
      const discount = cart.coupon_code ? Math.min(subtotal, Math.round(subtotal * (cart.coupon_code === 'WELCOME10' ? 0.1 : 0.15))) : 0;
      setCart({ ...cart, items, items_count: items.reduce((sum, item) => sum + item.quantity, 0), subtotal, discount, total: subtotal - discount });
      return;
    }
    try {
      const response = await api.patch(`/cart/items/${id}`, { quantity: nextQuantity });
      setCart(normalizeCart(response.data.data));
    } catch (error) {
      setNotice(apiErrorMessage(error));
    }
  }, [cart]);

  const removeFromCart = useCallback(async (id: number | string) => {
    if (demoMode) {
      const items = cart.items.filter((item) => item.id !== id);
      const subtotal = items.reduce((sum, item) => sum + item.subtotal, 0);
      setCart({ ...cart, items, items_count: items.reduce((sum, item) => sum + item.quantity, 0), subtotal, discount: 0, coupon_code: null, total: subtotal });
      setNotice('کتاب از سبد خرید حذف شد.');
      return;
    }
    try {
      const response = await api.delete(`/cart/items/${id}`);
      setCart(normalizeCart(response.data.data));
    } catch (error) {
      setNotice(apiErrorMessage(error));
    }
  }, [cart]);

  const applyCoupon = useCallback(async (code: string) => {
    if (demoMode) {
      const coupon = code.trim().toUpperCase();
      if (!['WELCOME10', 'READMORE15'].includes(coupon)) throw new Error('کد تخفیف معتبر نیست.');
      const discount = Math.round(cart.subtotal * (coupon === 'WELCOME10' ? 0.1 : 0.15));
      setCart({ ...cart, coupon_code: coupon, discount, total: Math.max(0, cart.subtotal - discount) });
      return;
    }
    const response = await api.post('/cart/coupon', { code });
    setCart(normalizeCart(response.data.data));
  }, [cart]);

  const removeCoupon = useCallback(async () => {
    if (demoMode) {
      setCart({ ...cart, coupon_code: null, discount: 0, total: cart.subtotal });
      return;
    }
    const response = await api.delete('/cart/coupon');
    setCart(normalizeCart(response.data.data));
  }, [cart]);

  const checkout = useCallback(async (method: 'zarinpal' | 'wallet'): Promise<CheckoutResult> => {
    if (!demoMode && !user) {
      setLoginOpen(true);
      throw new Error('برای ادامه‌ی خرید وارد حساب کاربری شوید.');
    }
    if (cart.items.length === 0) throw new Error('سبد خرید خالی است.');

    if (demoMode) {
      const orderId = Math.floor(2400 + Math.random() * 700);
      if (method === 'wallet' && cart.total > 1250000) throw new Error('موجودی کیف پول آزمایشی کافی نیست.');
      setCart(emptyCart);
      return { orderId, paid: true };
    }

    const response = await api.post('/checkout', { payment_method: method });
    const result = response.data.data;
    if (result.payment_url) return { redirect: result.payment_url, orderId: result.order_id };
    setCart(emptyCart);
    return { orderId: result.order_id, paid: result.status === 'paid' };
  }, [cart, user]);

  const value = useMemo<StoreContextValue>(() => ({
    theme,
    toggleTheme,
    user,
    cart,
    loginOpen,
    notice,
    setLoginOpen,
    setNotice,
    login,
    register,
    logout,
    updateUser,
    addToCart,
    updateQuantity,
    removeFromCart,
    applyCoupon,
    removeCoupon,
    checkout,
  }), [theme, toggleTheme, user, cart, loginOpen, notice, login, register, logout, updateUser, addToCart, updateQuantity, removeFromCart, applyCoupon, removeCoupon, checkout]);

  return <StoreContext.Provider value={value}>{children}</StoreContext.Provider>;
}

export function useStore() {
  const context = useContext(StoreContext);
  if (!context) throw new Error('useStore must be used within StoreProvider');
  return context;
}
