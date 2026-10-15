<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PrintHouseResource;
use App\Models\PrintContractRequest;
use App\Models\PrintHouse;
use App\Services\PrintPricingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PrintController extends Controller
{
    /**
     * Public directory of partner printing houses plus the print options the
     * storefront uses to build its order form.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'city' => ['nullable', 'string', 'max:80'],
            'capability' => ['nullable', 'string', 'max:80'],
            'sort' => ['nullable', 'in:rating,price_asc,price_desc,fastest'],
        ]);

        $query = PrintHouse::query()->active()->withCount('printOrders');

        if (!empty($validated['city'])) {
            $city = $validated['city'];
            $query->where('city', $city);
        }

        if (!empty($validated['capability'])) {
            $capability = $validated['capability'];
            $query->whereJsonContains('capabilities', $capability);
        }

        match ($validated['sort'] ?? 'rating') {
            'price_asc' => $query->orderBy('unit_price'),
            'price_desc' => $query->orderByDesc('unit_price'),
            'fastest' => $query->orderBy('delivery_days'),
            default => $query->orderByDesc('rating')->orderBy('name'),
        };

        $houses = $query->get();

        return response()->json([
            'data' => [
                'print_houses' => PrintHouseResource::collection($houses),
                'cities' => PrintHouse::query()->active()->orderBy('city')->distinct()->pluck('city')->values(),
                'capabilities' => PrintHouse::query()
                    ->active()
                    ->whereNotNull('capabilities')
                    ->pluck('capabilities')
                    ->flatten()
                    ->unique()
                    ->values(),
                'options' => [
                    'paper_types' => $this->labelMap(PrintPricingService::PAPER_LABELS),
                    'bindings' => $this->labelMap(PrintPricingService::BINDING_LABELS),
                    'color_surcharge' => PrintPricingService::COLOR_SURCHARGE,
                    'digital_owner_discount' => PrintPricingService::DIGITAL_OWNER_DISCOUNT,
                ],
                'stats' => [
                    'print_houses' => $houses->count(),
                    'cities' => $houses->pluck('city')->unique()->count(),
                    'fastest_delivery_days' => (int) ($houses->min('delivery_days') ?? 0),
                ],
            ],
        ]);
    }

    /**
     * A printing house applies to print books for the storefront. The request is
     * queued for the admin team, who can approve it from the dashboard.
     */
    public function contractRequest(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:150'],
            'contact_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['required', 'string', 'max:80'],
            'monthly_capacity' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'capabilities' => ['nullable', 'array', 'max:10'],
            'capabilities.*' => ['string', 'max:80'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $contractRequest = PrintContractRequest::query()->create($validated);

        return response()->json([
            'message' => 'درخواست همکاری ثبت شد؛ کارشناسان ما برای امضای قرارداد تماس می‌گیرند.',
            'data' => [
                'id' => $contractRequest->id,
                'status' => $contractRequest->status,
                'created_at' => $contractRequest->created_at?->toISOString(),
            ],
        ], 201);
    }

    /**
     * @param  array<string, string>  $labels
     * @return array<int, array{value:string,label:string}>
     */
    private function labelMap(array $labels): array
    {
        return collect($labels)
            ->map(fn (string $label, string $value) => ['value' => $value, 'label' => $label])
            ->values()
            ->all();
    }
}
