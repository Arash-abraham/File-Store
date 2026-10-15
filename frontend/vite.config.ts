import { defineConfig, loadEnv } from 'vite';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '');
  const apiTarget = env.API_PROXY_TARGET || 'http://127.0.0.1:8000';

  return {
    plugins: [react(), tailwindcss()],
    server: {
      host: '0.0.0.0',
      port: 5173,
      strictPort: true,
      allowedHosts: true,
      proxy: {
        '/api': { target: apiTarget, changeOrigin: true },
        '/sanctum': { target: apiTarget, changeOrigin: true },
        '/products': { target: apiTarget, changeOrigin: true },
        '/storage': { target: apiTarget, changeOrigin: true },
      },
    },
  };
});
