<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

/**
 * Admin dashboard: summary numbers plus three daily series (sales, new users, visits).
 * Everything is computed here so the React side only renders.
 */
class AdminStatsController extends Controller
{
    public function dashboard()
    {
        $now = CarbonImmutable::now();
        $todayStart = $now->startOfDay();
        $since30 = $now->subDays(29)->startOfDay();

        $ordersByStatus = Order::query()->select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total', 'status');

        $visitsToday = DB::table('page_views')->where('created_at', '>=', $todayStart)->count();
        $visits30 = DB::table('page_views')->where('created_at', '>=', $since30)->count();
        $visitors30 = DB::table('page_views')->where('created_at', '>=', $since30)->distinct('visitor_hash')->count('visitor_hash');

        return response()->json([
            'data' => [
                'stats' => [
                    'products' => Product::query()->count(),
                    'users' => User::query()->count(),
                    'new_users_30d' => User::query()->where('created_at', '>=', $since30)->count(),
                    'orders' => (int) $ordersByStatus->sum(),
                    'paid_orders' => (int) ($ordersByStatus['paid'] ?? 0),
                    'pending_orders' => (int) ($ordersByStatus['pending'] ?? 0),
                    'revenue' => $this->revenueBetween(null, null),
                    'revenue_30d' => $this->revenueBetween($since30, $now),
                    'open_tickets' => Ticket::query()->whereIn('status', ['open', 'in_progress'])->count(),
                    'pending_reviews' => Review::query()->where('status', 'pending')->count(),
                    'visits_today' => $visitsToday,
                    'visits_30d' => $visits30,
                    'visitors_30d' => $visitors30,
                ],
                'sales' => [
                    'daily' => $this->revenueDaily($since30, $now),
                ],
                'users' => [
                    'daily_new' => $this->userSignupsDaily($since30, $now),
                ],
                'visits' => [
                    'daily' => $this->visitsDaily($since30, $now),
                ],
                'recent_orders' => Order::query()->with('user:id,name')->where('status', 'paid')->latest('paid_at')->limit(6)->get()->map(fn (Order $order) => [
                    'id' => $order->id,
                    'customer' => $order->user?->name ?? 'مهمان',
                    'amount' => (int) $order->total_amount - (int) $order->discount_amount,
                    'paid_at' => $order->paid_at?->toISOString(),
                ])->values(),
            ],
        ]);
    }

    /** Paid revenue (total minus discount) in an optional window. */
    private function revenueBetween(?CarbonImmutable $from, ?CarbonImmutable $to): int
    {
        $query = Order::query()->where('status', 'paid');
        if ($from && $to) {
            $query->whereBetween('paid_at', [$from, $to]);
        }

        return (int) $query->selectRaw('COALESCE(SUM(total_amount - discount_amount), 0) as revenue')->value('revenue');
    }

    /** @return array<int, array{day: string, amount: int}> */
    private function revenueDaily(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $rows = Order::query()->where('status', 'paid')->where('paid_at', '>=', $from)
            ->get(['paid_at', 'total_amount', 'discount_amount']);

        $buckets = [];
        foreach ($rows as $row) {
            $day = CarbonImmutable::parse($row->paid_at)->toDateString();
            $buckets[$day] = ($buckets[$day] ?? 0) + ((int) $row->total_amount - (int) $row->discount_amount);
        }

        return $this->fillDays($from, $to, fn (string $day) => ['day' => $day, 'amount' => (int) ($buckets[$day] ?? 0)]);
    }

    /** @return array<int, array{day: string, count: int}> */
    private function userSignupsDaily(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $counts = User::query()->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')->pluck('total', 'day');

        return $this->fillDays($from, $to, fn (string $day) => ['day' => $day, 'count' => (int) ($counts[$day] ?? 0)]);
    }

    /** @return array<int, array{day: string, views: int, visitors: int}> */
    private function visitsDaily(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $rows = DB::table('page_views')->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as visitors')
            ->groupBy('day')->get()->keyBy('day');

        return $this->fillDays($from, $to, function (string $day) use ($rows) {
            $row = $rows->get($day);

            return ['day' => $day, 'views' => (int) ($row->views ?? 0), 'visitors' => (int) ($row->visitors ?? 0)];
        });
    }

    /** Builds one entry per calendar day in [from, to], so charts never have gaps. */
    private function fillDays(CarbonImmutable $from, CarbonImmutable $to, callable $make): array
    {
        $series = [];
        for ($day = $from; $day <= $to; $day = $day->addDay()) {
            $series[] = $make($day->toDateString());
        }

        return $series;
    }
}
