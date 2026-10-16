import { useEffect, useRef } from 'react';
import {
  ArcElement,
  BarController,
  BarElement,
  CategoryScale,
  Chart,
  DoughnutController,
  Filler,
  LineController,
  LineElement,
  LinearScale,
  PointElement,
  Tooltip,
  type ChartConfiguration,
  type ChartOptions,
  type ScriptableContext,
  type TooltipItem,
} from 'chart.js';

Chart.register(ArcElement, BarController, BarElement, CategoryScale, DoughnutController, Filler, LineController, LineElement, LinearScale, PointElement, Tooltip);

Chart.defaults.font.family = "'Vazirmatn', Tahoma, sans-serif";
Chart.defaults.color = '#7b867f';
Chart.defaults.borderColor = '#eef1ec';

export const CHART_COLORS = ['#215446', '#e97b51', '#4a7fb5', '#d49b54', '#7a6bb5', '#3f9d8f', '#c66b60', '#9bb7a5'];

const toFa = (value: number) => value.toLocaleString('fa-IR');

/** Creates a Chart.js instance on a canvas and keeps it in sync with the config. */
function useChart(build: (canvas: HTMLCanvasElement) => unknown, key: string) {
  const ref = useRef<HTMLCanvasElement>(null);
  const chartRef = useRef<Chart | null>(null);

  useEffect(() => {
    const canvas = ref.current;
    if (!canvas) return;
    chartRef.current?.destroy();
    chartRef.current = new Chart(canvas, build(canvas) as ChartConfiguration);
    return () => {
      chartRef.current?.destroy();
      chartRef.current = null;
    };
    // The config is rebuilt whenever the serialized data changes.
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [key]);

  return ref;
}

function gradientFor(context: ScriptableContext<'line'>, color: string) {
  const { chart } = context;
  const { ctx, chartArea } = chart;
  if (!chartArea) return 'transparent';
  const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
  gradient.addColorStop(0, `${color}55`);
  gradient.addColorStop(1, `${color}00`);
  return gradient;
}

/* ------------------------------------------------------------ area / line */

export function AreaChart({ labels, values, color = '#215446', height = 260, format = toFa, label = 'مقدار' }: {
  labels: string[];
  values: number[];
  color?: string;
  height?: number;
  format?: (value: number) => string;
  label?: string;
}) {
  const key = JSON.stringify({ labels, values, color, label });
  const ref = useChart(() => ({
    type: 'line',
    data: {
      labels,
      datasets: [{
        label,
        data: values,
        borderColor: color,
        backgroundColor: (context: ScriptableContext<'line'>) => gradientFor(context, color),
        fill: true,
        tension: 0.38,
        borderWidth: 2.5,
        pointRadius: 0,
        pointHoverRadius: 5,
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: color,
        pointHoverBorderWidth: 2,
      }],
    },
    options: lineOptions(format),
  }), key);
  return <div className="ch-box" style={{ height }}><canvas ref={ref} /></div>;
}

/** Tiny line without axes, used inside KPI cards. */
export function Sparkline({ values, color }: { values: number[]; color: string }) {
  const key = JSON.stringify({ values, color });
  const ref = useChart(() => ({
    type: 'line',
    data: {
      labels: values.map((_, index) => String(index)),
      datasets: [{
        data: values,
        borderColor: color,
        backgroundColor: (context: ScriptableContext<'line'>) => gradientFor(context, color),
        fill: true,
        tension: 0.4,
        borderWidth: 2,
        pointRadius: 0,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: { duration: 500 },
      plugins: { legend: { display: false }, tooltip: { enabled: false } },
      scales: { x: { display: false }, y: { display: false, beginAtZero: true } },
    },
  }), key);
  return <div className="ch-spark"><canvas ref={ref} /></div>;
}

/* ----------------------------------------------------------------- bars */

export function BarsChart({ labels, datasets, horizontal = false, height = 260, stacked = false, format = toFa }: {
  labels: string[];
  datasets: { label: string; data: number[]; color: string }[];
  horizontal?: boolean;
  height?: number;
  stacked?: boolean;
  format?: (value: number) => string;
}) {
  const key = JSON.stringify({ labels, datasets, horizontal, stacked });
  const ref = useChart(() => ({
    type: 'bar',
    data: {
      labels,
      datasets: datasets.map((dataset) => ({
        label: dataset.label,
        data: dataset.data,
        backgroundColor: dataset.color,
        borderRadius: 7,
        borderSkipped: false,
        maxBarThickness: horizontal ? 22 : 34,
      })),
    },
    options: {
      ...baseOptions(format, datasets.length > 1),
      indexAxis: horizontal ? 'y' : 'x',
      scales: {
        x: { stacked, grid: { display: horizontal }, ticks: { callback: (value) => (horizontal ? format(Number(value)) : undefined) } },
        y: { stacked, beginAtZero: true, grid: { color: '#f2f4f0' }, ticks: { callback: (value) => (horizontal ? undefined : format(Number(value))) } },
      },
    } as ChartOptions<'bar'>,
  }), key);
  return <div className="ch-box" style={{ height }}><canvas ref={ref} /></div>;
}

/* ------------------------------------------------------------- doughnut */

export function DoughnutChart({ labels, values, colors = CHART_COLORS, cutout = '72%', height = 220, format = toFa }: {
  labels: string[];
  values: number[];
  colors?: string[];
  cutout?: string;
  height?: number;
  format?: (value: number) => string;
}) {
  const key = JSON.stringify({ labels, values, colors, cutout });
  const ref = useChart(() => ({
    type: 'doughnut',
    data: {
      labels,
      datasets: [{ data: values, backgroundColor: colors, borderColor: '#ffffff', borderWidth: 3, hoverOffset: 6 }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout,
      animation: { duration: 600 },
      plugins: {
        legend: { display: false },
        tooltip: {
          ...tooltipStyle,
          callbacks: {
            label: (item: TooltipItem<'doughnut'>) => {
              const total = values.reduce((sum, value) => sum + value, 0) || 1;
              return ` ${item.label}: ${format(Number(item.raw))} (${Math.round((Number(item.raw) / total) * 100).toLocaleString('fa-IR')}٪)`;
            },
          },
        },
      },
    },
  }), key);
  return <div className="ch-box" style={{ height }}><canvas ref={ref} /></div>;
}

/* ------------------------------------------------------------- helpers */

const tooltipStyle = {
  rtl: true,
  textDirection: 'rtl' as const,
  backgroundColor: '#1f2d28',
  titleColor: '#f4f7f3',
  bodyColor: '#e3ebe5',
  padding: 10,
  cornerRadius: 10,
  displayColors: true,
  boxPadding: 4,
  titleFont: { size: 12, weight: 'bold' as const },
  bodyFont: { size: 12 },
};

function lineOptions(format: (value: number) => string): ChartOptions<'line'> {
  return {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: 'index', intersect: false },
    animation: { duration: 700 },
    plugins: {
      legend: { display: false },
      tooltip: { ...tooltipStyle, callbacks: { label: (item) => ` ${format(Number(item.raw))}` } },
    },
    scales: {
      x: { grid: { display: false }, ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
      y: { beginAtZero: true, grid: { color: '#f2f4f0' }, ticks: { maxTicksLimit: 5, callback: (value) => format(Number(value)) } },
    },
    elements: { line: { capBezierPoints: true } },
  } as ChartOptions<'line'>;
}

function baseOptions(format: (value: number) => string, legend: boolean): ChartOptions<'bar'> {
  return {
    responsive: true,
    maintainAspectRatio: false,
    animation: { duration: 600 },
    interaction: { mode: 'index', intersect: false },
    plugins: {
      legend: { display: legend, position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true, padding: 14 } },
      tooltip: { ...tooltipStyle, callbacks: { label: (item) => ` ${item.dataset.label ?? ''}: ${format(Number(item.raw))}` } },
    },
  };
}
