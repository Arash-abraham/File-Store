import { BrowserRouter, Navigate, Route, Routes, useLocation } from 'react-router-dom';
import { StoreProvider } from './StoreContext';
import SiteLayout from './components/SiteLayout';
import AccountPage from './pages/AccountPage';
import AdminPage from './pages/AdminPage';
import CartPage from './pages/CartPage';
import CatalogPage from './pages/CatalogPage';
import CheckoutResultPage from './pages/CheckoutResultPage';
import HomePage from './pages/HomePage';
import NotFoundPage from './pages/NotFoundPage';
import ProductPage from './pages/ProductPage';
import PasswordResetPage from './pages/PasswordResetPage';
import { AboutPage, FaqPage } from './pages/StaticPages';

function WalletCallbackRedirect() {
  const location = useLocation();
  const params = new URLSearchParams(location.search);
  params.set('tab', 'wallet');
  return <Navigate to={`/account?${params.toString()}`} replace />;
}

export default function App() {
  return (
    <StoreProvider>
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<SiteLayout />}>
            <Route index element={<HomePage />} />
            <Route path="catalog" element={<CatalogPage />} />
            <Route path="book/:slug" element={<ProductPage />} />
            <Route path="cart" element={<CartPage />} />
            <Route path="checkout/result" element={<CheckoutResultPage />} />
            <Route path="account" element={<AccountPage />} />
            <Route path="account/wallet" element={<WalletCallbackRedirect />} />
            <Route path="reset-password" element={<PasswordResetPage />} />
            <Route path="admin" element={<AdminPage />} />
            <Route path="about" element={<AboutPage />} />
            <Route path="faq" element={<FaqPage />} />
            <Route path="*" element={<NotFoundPage />} />
          </Route>
        </Routes>
      </BrowserRouter>
    </StoreProvider>
  );
}
