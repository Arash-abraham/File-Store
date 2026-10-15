<?php

namespace App\Services;

use App\Models\PrintHouse;

/**
 * Single source of truth for physical print pricing.
 *
 * Both the "quote" endpoint (what the customer sees in the order form) and the
 * order creation use this service, so the charged amount can never drift from
 * the amount shown before confirmation.
 */
class PrintPricingService
{
    public const COLOR_SURCHARGE = 45000;

    /** Loyalty discount for readers who already own the digital edition. */
    public const DIGITAL_OWNER_DISCOUNT = 0.15;

    public const PAPER_SURCHARGES = [
        'standard' => 0,
        'cream' => 35000,
        'premium' => 60000,
    ];

    public const SPIRAL_SURCHARGE = 25000;

    public const PAPER_LABELS = [
        'standard' => 'کاغذ تحریر معمولی',
        'cream' => 'کاغذ کرافت/کرم',
        'premium' => 'کاغذ گلاسه‌ی درجه‌یک',
    ];

    public const BINDING_LABELS = [
        'paperback' => 'شومیز',
        'hardcover' => 'سخت (گالینگور)',
        'spiral' => 'فنر',
    ];

    /**
     * @param  array{quantity?:int,paper_type?:string,binding?:string,color_print?:bool}  $options
     * @return array{unit_price:int,subtotal:int,delivery_fee:int,discount_amount:int,total_price:int,delivery_days:int,min_quantity:int,max_quantity:int,breakdown:array<int,array{label:string,amount:int}>}
     */
    public function quote(PrintHouse $house, array $options, bool $ownsDigital): array
    {
        $quantity = $this->clampQuantity($house, (int) ($options['quantity'] ?? 1));
        $paper = array_key_exists((string) ($options['paper_type'] ?? ''), self::PAPER_SURCHARGES) ? (string) $options['paper_type'] : 'standard';
        $binding = array_key_exists((string) ($options['binding'] ?? ''), self::BINDING_LABELS) ? (string) $options['binding'] : 'paperback';
        $colorPrint = (bool) ($options['color_print'] ?? false);

        $paperSurcharge = self::PAPER_SURCHARGES[$paper];
        $bindingSurcharge = match ($binding) {
            'hardcover' => (int) $house->hardcover_surcharge,
            'spiral' => self::SPIRAL_SURCHARGE,
            default => 0,
        };
        $colorSurcharge = $colorPrint ? self::COLOR_SURCHARGE : 0;

        $unitPrice = (int) $house->unit_price + $paperSurcharge + $bindingSurcharge + $colorSurcharge;
        $subtotal = $unitPrice * $quantity;
        $deliveryFee = (int) $house->delivery_fee;
        $discount = $ownsDigital ? (int) round($subtotal * self::DIGITAL_OWNER_DISCOUNT) : 0;
        $total = max(0, $subtotal - $discount) + $deliveryFee;

        $breakdown = [
            ['label' => 'چاپ هر نسخه', 'amount' => (int) $house->unit_price],
        ];

        if ($paperSurcharge > 0) {
            $breakdown[] = ['label' => self::PAPER_LABELS[$paper], 'amount' => $paperSurcharge];
        }

        if ($bindingSurcharge > 0) {
            $breakdown[] = ['label' => 'صحافی '.self::BINDING_LABELS[$binding], 'amount' => $bindingSurcharge];
        }

        if ($colorSurcharge > 0) {
            $breakdown[] = ['label' => 'چاپ رنگی', 'amount' => $colorSurcharge];
        }

        $breakdown[] = ['label' => 'تعداد نسخه', 'amount' => $subtotal];

        if ($deliveryFee > 0) {
            $breakdown[] = ['label' => 'هزینه‌ی ارسال', 'amount' => $deliveryFee];
        }

        if ($discount > 0) {
            $breakdown[] = ['label' => 'تخفیف دارندگان نسخه‌ی دیجیتال (۱۵٪)', 'amount' => -$discount];
        }

        return [
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'discount_amount' => $discount,
            'total_price' => $total,
            'delivery_days' => (int) $house->delivery_days,
            'min_quantity' => (int) $house->min_quantity,
            'max_quantity' => (int) $house->max_quantity,
            'breakdown' => $breakdown,
        ];
    }

    public function clampQuantity(PrintHouse $house, int $quantity): int
    {
        $minimum = max(1, (int) $house->min_quantity);
        $maximum = max($minimum, (int) $house->max_quantity);

        return max($minimum, min($maximum, $quantity));
    }
}
