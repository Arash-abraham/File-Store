export const toman = (value: number) =>
  new Intl.NumberFormat('fa-IR', { maximumFractionDigits: 0 }).format(Math.round(value));

export const formatPrice = (value: number) => `${toman(value)} تومان`;

export const toPersianDigits = (value: string | number) =>
  new Intl.NumberFormat('fa-IR', { useGrouping: false }).format(Number(value));

export const formatDate = (value?: string | null) => {
  if (!value) return '—';
  return new Intl.DateTimeFormat('fa-IR', { dateStyle: 'medium' }).format(new Date(value));
};
