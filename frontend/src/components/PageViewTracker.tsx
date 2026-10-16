import { useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import { api, demoMode } from '../lib/api';

/** Sends one anonymous page view per route change. Admin screens are not counted. */
export default function PageViewTracker() {
  const { pathname } = useLocation();

  useEffect(() => {
    if (demoMode || pathname.startsWith('/admin')) return;
    api.post('/analytics/pageview', { path: pathname }).catch(() => undefined);
  }, [pathname]);

  return null;
}
