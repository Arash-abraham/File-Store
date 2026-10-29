import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { useStore } from '../StoreContext';
import { api, apiErrorMessage, demoMode } from './api';
import type { Product, ProductFavorite } from '../types';

const DEMO_FAVORITES_KEY = 'filestore-demo-favorites';

function readDemoFavorites(): ProductFavorite[] {
  try {
    const value = localStorage.getItem(DEMO_FAVORITES_KEY);
    return value ? JSON.parse(value) as ProductFavorite[] : [];
  } catch {
    return [];
  }
}

function writeDemoFavorites(favorites: ProductFavorite[]) {
  localStorage.setItem(DEMO_FAVORITES_KEY, JSON.stringify(favorites));
}

export function useFavorites() {
  const { user, setLoginOpen, setNotice } = useStore();
  const queryClient = useQueryClient();
  const query = useQuery<ProductFavorite[]>({
    queryKey: ['account-favorites', user?.id],
    enabled: !!user,
    queryFn: async () => demoMode ? readDemoFavorites() : (await api.get('/account/favorites')).data.data as ProductFavorite[],
  });
  const mutation = useMutation({
    mutationFn: async ({ product, saved }: { product: Product; saved: boolean }) => {
      if (demoMode) {
        const favorites = readDemoFavorites();
        if (saved) writeDemoFavorites(favorites.filter((favorite) => favorite.product_id !== product.id));
        else writeDemoFavorites([...favorites, {
          id: product.id,
          product_id: product.id,
          saved_price: product.price,
          current_price: product.price,
          price_dropped: false,
          product,
        }]);
        return;
      }
      if (saved) await api.delete(`/favorites/${product.id}`);
      else await api.post(`/favorites/${product.id}`);
    },
    onSuccess: async (_data, variables) => {
      await queryClient.invalidateQueries({ queryKey: ['account-favorites', user?.id] });
      await queryClient.invalidateQueries({ queryKey: ['account-overview', user?.id] });
      setNotice(variables.saved ? 'از فهرست علاقه‌مندی‌ها حذف شد.' : 'به علاقه‌مندی‌ها اضافه شد؛ کاهش قیمت را همین‌جا دنبال کن.');
    },
    onError: (error) => setNotice(apiErrorMessage(error, 'به‌روزرسانی علاقه‌مندی‌ها انجام نشد.')),
  });

  function isSaved(productId: number) {
    return (query.data ?? []).some((favorite) => favorite.product_id === productId);
  }

  function toggle(product: Product) {
    if (!user) {
      setLoginOpen(true);
      return;
    }
    mutation.mutate({ product, saved: isSaved(product.id) });
  }

  return { favorites: query.data ?? [], isSaved, toggle, loading: query.isLoading, busy: mutation.isPending, refresh: query.refetch };
}
