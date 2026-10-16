<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\FileProduct;
use App\Models\Order;
use App\Models\PrintContractRequest;
use App\Models\PrintHouse;
use App\Models\PrintOrder;
use App\Models\Product;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

/**
 * Aggregated statistics for the admin overview. Everything the dashboard shows
 * is computed here, so the React side only renders.
 */
class AdminStatsController extends Controller
{
    private const PAID_REVENUE = '(orders.total_amount - orders.discount_amount)';

    public function dashboard()
    {
        $now = CarbonImmutable::now();
        $since30 = $now->subDays(29)->startOfDay();
        $prevFrom = $now->subDays(59)->startOfDay();

        $revenue = $this->revenue();
        $revenueLast30 = $this->revenueBetween($since30, $now);
        $revenuePrev30 = $this->revenueBetween($prevFrom, $since30);

        $ordersByStatus = Order::query()->select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total', 'status');

        $reviewsByStatus = Review::query()->select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total', 'status');
        $ticketsByStatus = Ticket::query()->select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total', 'status');
        $printByStatus = PrintOrder::query()->select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total', 'status');
        $contractsByStatus = PrintContractRequest::query()->select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total', 'status');

        $productStatus = Product::query()->select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total', 'status');

        $users = User::query()->where('role', 'user');
        $newUsers = $this->dailySeries(User::query()->where('role', 'user')->where('created_at', '>=', $since30)->pluck('created_at'), $since30, $now);

        return response()->json([
            'data' => [
                // Kept for the existing dashboard consumers.
                'stats' => [
                    'products' => Product::query()->count(),
                    'users' => (clone $users)->count(),
                    'orders' => Order::query()->count(),
                    'revenue' => $revenue['total'],
                    'pending_orders' => (int) ($ordersByStatus['pending'] ?? 0),
                    'open_tickets' => (int) (($ticketsByStatus['open'] ?? 0) + ($ticketsByStatus['in_progress'] ?? 0)),
                    'pending_reviews' => (int) ($reviewsByStatus['pending'] ?? 0),
                ],
                'sales' => [
                    'revenue_total' => $revenue['total'],
                    'revenue_today' => $this->revenueBetween($now->startOfDay(), $now),
                    'revenue_7d' => $this->revenueBetween($now->subDays(6)->startOfDay(), $now),
                    'revenue_30d' => $revenueLast30,
                    'revenue_growth_30d' => $revenuePrev30 > 0 ? round(($revenueLast30 - $revenuePrev30) / $revenuePrev30 * 100, 1) : null,
                    'paid_orders' => (int) ($ordersByStatus['paid'] ?? 0),
                    'average_order_value' => (int) ($ordersByStatus['paid'] ?? 0) > 0 ? (int) round($revenue['total'] / $ordersByStatus['paid']) : 0,
                    'conversion_rate' => (int) ($ordersByStatus->sum()) > 0 ? round(($ordersByStatus['paid'] ?? 0) / $ordersByStatus->sum() * 100, 1) : 0,
                    'refunded_orders' => (int) ($ordersByStatus['refunded'] ?? 0),
                    'failed_orders' => (int) ($ordersByStatus['failed'] ?? 0) + (int) ($ordersByStatus['cancelled'] ?? 0),
                    'pending_orders' => (int) ($ordersByStatus['pending'] ?? 0),
                    'orders_by_status' => $this->statusMap($ordersByStatus, ['paid', 'pending', 'failed', 'cancelled', 'refunded']),
                    'daily' => $this->revenueDaily($since30, $now),
                    'daily_orders' => $this->dailySeries(Order::query()->where('created_at', '>=', $since30)->pluck('created_at'), $since30, $now),
                ],
                'users' => [
                    'total' => (clone $users)->count(),
                    'admins' => User::query()->where('role', 'admin')->count(),
                    'verified' => (clone $users)->whereNotNull('email_verified_at')->count(),
                    'new_7d' => (clone $users)->where('created_at', '>=', $now->subDays(6)->startOfDay())->count(),
                    'new_30d' => (clone $users)->where('created_at', '>=', $since30)->count(),
                    'buyers' => Order::query()->where('status', 'paid')->whereNotNull('user_id')->distinct('user_id')->count('user_id'),
                    'daily_new' => $newUsers,
                ],
                'catalog' => [
                    'total' => Product::query()->count(),
                    'active' => (int) ($productStatus['active'] ?? 0),
                    'draft' => (int) ($productStatus['draft'] ?? 0),
                    'inactive' => (int) ($productStatus['inactive'] ?? 0),
                    'unavailable' => Product::query()->where('availability', false)->count(),
                    'files' => FileProduct::query()->count(),
                    'categories' => DB::table('categories')->count(),
                    'tags' => DB::table('tags')->count(),
                    'top_products' => $this->topProducts(),
                    'revenue_by_category' => $this->revenueByCategory(),
                ],
                'coupons' => [
                    'total' => Coupon::query()->count(),
                    'active' => Coupon::query()->where('status', 'active')->count(),
                    'orders_with_coupon' => Order::query()->where('status', 'paid')->whereNotNull('coupon_id')->count(),
                    'discount_given' => (int) Order::query()->where('status', 'paid')->sum('discount_amount'),
                ],
                'community' => [
                    'comments_approved' => Review::query()->approved()->topLevelComments()->count(),
                    'comments_pending' => Review::query()->pending()->topLevelComments()->count(),
                    'questions_approved' => Review::query()->approved()->questions()->count(),
                    'questions_pending' => Review::query()->pending()->questions()->count(),
                    'replies_total' => Review::query()->replies()->count(),
                    'replies_pending' => Review::query()->replies()->pending()->count(),
                    'rejected' => (int) ($reviewsByStatus['rejected'] ?? 0),
                    'likes_total' => DB::table('review_helpfuls')->count(),
                    'reports_total' => DB::table('review_reports')->count(),
                    'average_rating' => round((float) Review::query()->approved()->topLevelComments()->whereNotNull('rating')->avg('rating'), 1),
                    'by_status' => $this->statusMap($reviewsByStatus, ['approved', 'pending', 'rejected']),
                ],
                'support' => [
                    'open' => (int) ($ticketsByStatus['open'] ?? 0),
                    'in_progress' => (int) ($ticketsByStatus['in_progress'] ?? 0),
                    'closed' => (int) ($ticketsByStatus['closed'] ?? 0),
                    'created_7d' => Ticket::query()->where('created_at', '>=', $now->subDays(6)->startOfDay())->count(),
                ],
                'printing' => [
                    'orders_total' => PrintOrder::query()->count(),
                    'revenue' => (int) PrintOrder::query()->where('status', '!=', 'cancelled')->sum('total_price'),
                    'orders_by_status' => $this->statusMap($printByStatus, ['pending', 'confirmed', 'printing', 'shipped', 'delivered', 'cancelled']),
                    'houses_total' => PrintHouse::query()->count(),
                    'houses_active' => PrintHouse::query()->where('status', 'active')->count(),
                    'houses_pending' => PrintHouse::query()->where('status', 'pending')->count(),
                    'contracts_pending' => (int) ($contractsByStatus['pending'] ?? 0) + (int) ($contractsByStatus['reviewing'] ?? 0),
                    'contracts_approved' => (int) ($contractsByStatus['approved'] ?? 0),
                ],
                'wallets' => [
                    'total_balance' => (int) DB::table('wallets')->sum('balance'),
                    'wallets' => DB::table('wallets')->count(),
                    'deposits_completed' => (int) DB::table('wallet_transactions')->where('type', 'deposit')->where('status', 'completed')->sum('amount'),
                    'purchases_completed' => (int) abs(DB::table('wallet_transactions')->where('type', 'purchase')->where('status', 'completed')->sum('amount')),
                    'pending_transactions' => DB::table('wallet_transactions')->where('status', 'pending')->count(),
                ],
                'recent_orders' => Order::query()->with('user:id,name,email')->latest()->limit(8)->get()->map(fn (Order $order) => [
                    'id' => $order->id,
                    'customer' => $order->user?->name ?? 'کاربر حذف‌شده',
                    'amount' => (int) $order->total_amount - (int) $order->discount_amount,
                    'final_amount' => (int) $order->total_amount - (int) $order->discount_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at?->toISOString(),
                ])->values(),
                'attention' => [
                    'reviews' => Review::query()->pending()->with(['user:id,name', 'product:id,title'])->latest()->limit(5)->get()->map(fn (Review $review) => [
                        'id' => $review->id,
                        'type' => $review->parent_id ? 'reply' : $review->type,
                        'body' => mb_substr($review->body, 0, 120),
                        'author' => $review->user?->name ?? 'کاربر',
                        'product' => $review->product?->title,
                        'created_at' => $review->created_at?->toISOString(),
                    ])->values(),
                    'tickets' => Ticket::query()->whereIn('status', ['open', 'in_progress'])->with('user:id,name')->latest()->limit(5)->get()->map(fn (Ticket $ticket) => [
                        'id' => $ticket->id,
                        'subject' => $ticket->subject,
                        'status' => $ticket->status,
                        'author' => $ticket->user?->name ?? 'کاربر',
                        'created_at' => $ticket->created_at?->toISOString(),
                    ])->values(),
                ],
            ],
        ]);
    }

    private function revenue(): array
    {
        return ['total' => (int) Order::query()->where('status', 'paid')->selectRaw('COALESCE(SUM(total_amount - discount_amount), 0) as revenue')->value('revenue')];
    }

    private function revenueBetween(CarbonImmutable $from, CarbonImmutable $to): int
    {
        return (int) Order::query()->where('status', 'paid')
            ->whereBetween('paid_at', [$from, $to])
            ->selectRaw('COALESCE(SUM(total_amount - discount_amount), 0) as revenue')
            ->value('revenue');
    }

    /** Daily revenue of paid orders (by paid_at), zero-filled for the whole period. */
    private function revenueDaily(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $rows = Order::query()->where('status', 'paid')->where('paid_at', '>=', $from)
            ->get(['paid_at', 'total_amount', 'discount_amount']);

        $buckets = [];
        foreach ($rows as $row) {
            $day = CarbonImmutable::parse($row->paid_at)->toDateString();
            $buckets[$day] = ($buckets[$day] ?? 0) + ((int) $row->total_amount - (int) $row->discount_amount);
        }

        $series = [];
        for ($day = $from; $day <= $to; $day = $day->addDay()) {
            $key = $day->toDateString();
            $series[] = ['day' => $key, 'amount' => (int) ($buckets[$key] ?? 0)];
        }

        return $series;
    }

    /** Zero-filled daily counts for a collection of timestamps. */
    private function dailySeries($timestamps, CarbonImmutable $from, CarbonImmutable $to): array
    {
        $buckets = [];
        foreach ($timestamps as $timestamp) {
            $day = CarbonImmutable::parse($timestamp)->toDateString();
            $buckets[$day] = ($buckets[$day] ?? 0) + 1;
        }

        $series = [];
        for ($day = $from; $day <= $to; $day = $day->addDay()) {
            $key = $day->toDateString();
            $series[] = ['day' => $key, 'count' => (int) ($buckets[$key] ?? 0)];
        }

        return $series;
    }

    private function topProducts(int $limit = 6): array
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'paid')
            ->groupBy('order_items.product_id', 'order_items.product_title')
            ->orderByDesc(DB::raw('SUM(order_items.subtotal)'))
            ->limit($limit)
            ->get([
                'order_items.product_id',
                'order_items.product_title as title',
                DB::raw('SUM(order_items.subtotal) as revenue'),
                DB::raw('SUM(order_items.quantity) as sales'),
            ])
            ->map(fn ($row) => ['id' => (int) $row->product_id, 'title' => $row->title, 'revenue' => (int) $row->revenue, 'sales' => (int) $row->sales])
            ->values()
            ->all();
    }

    private function revenueByCategory(): array
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->where('orders.status', 'paid')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc(DB::raw('SUM(order_items.subtotal)'))
            ->get([
                DB::raw("COALESCE(categories.name, 'بدون دسته') as name"),
                DB::raw('SUM(order_items.subtotal) as revenue'),
            ])
            ->map(fn ($row) => ['name' => $row->name, 'revenue' => (int) $row->revenue])
            ->values()
            ->all();
    }

    /** Ensures every expected status appears in the output, even with a zero count. */
    private function statusMap($counts, array $statuses): array
    {
        return collect($statuses)->map(fn (string $status) => ['status' => $status, 'count' => (int) ($counts[$status] ?? 0)])->values()->all();
    }
}
