import axios from 'axios';

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || '/api/v1';
const absoluteApiBase = new URL(apiBaseUrl, window.location.origin);

export const api = axios.create({
  baseURL: apiBaseUrl,
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    Accept: 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
});

export const demoMode = import.meta.env.VITE_DEMO_MODE === 'true';

export async function requestCsrfCookie() {
  await axios.get(new URL('/sanctum/csrf-cookie', absoluteApiBase.origin).toString(), {
    withCredentials: true,
    headers: { Accept: 'application/json' },
  });
}

export function apiErrorMessage(error: unknown, fallback = 'در ارتباط با سرور مشکلی پیش آمد.') {
  if (axios.isAxiosError(error)) {
    const response = error.response?.data;
    const firstValidationError = response?.errors && typeof response.errors === 'object'
      ? Object.values(response.errors as Record<string, unknown[]>).flat().find((message) => typeof message === 'string')
      : undefined;
    return (typeof response?.message === 'string' && response.message) || (typeof firstValidationError === 'string' && firstValidationError) || fallback;
  }
  return error instanceof Error ? error.message : fallback;
}

declare global {
  interface ImportMetaEnv {
    readonly VITE_API_BASE_URL?: string;
    readonly VITE_DEMO_MODE?: string;
  }
  interface ImportMeta {
    readonly env: ImportMetaEnv;
  }
}
