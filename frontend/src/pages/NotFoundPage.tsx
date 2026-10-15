import { ArrowRight, BookOpen } from 'lucide-react';
import { Link } from 'react-router-dom';

export default function NotFoundPage() {
  return <section className="container not-found"><span className="not-found-icon"><BookOpen size={33} /></span><span className="eyebrow">۴۰۴ · این صفحه پیدا نشد</span><h1>شاید کتاب دیگری<br /><em>صدایت می‌زند.</em></h1><Link className="button button-primary" to="/"><ArrowRight size={16} /> برگشت به خانه</Link></section>;
}
