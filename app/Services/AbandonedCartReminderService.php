<?php

namespace App\Services;

use App\Models\Cart;
use App\Notifications\AbandonedCartNotification;
use Illuminate\Support\Facades\Log;
use Throwable;

class AbandonedCartReminderService
{
    public function sendEligibleReminders(): int
    {
        $sent = 0;
        $cutoff = now()->subHours(24);

        Cart::query()
            ->where('status', 'active')
            ->whereNotNull('user_id')
            ->where('updated_at', '<=', $cutoff)
            ->whereHas('items')
            ->whereHas('user', fn ($query) => $query->where('marketing_opt_in', true)->whereNotNull('email_verified_at'))
            ->where(fn ($query) => $query->whereNull('abandoned_reminder_sent_at')->orWhereColumn('abandoned_reminder_sent_at', '<', 'updated_at'))
            ->with(['user', 'items.product'])
            ->orderBy('id')
            ->chunkById(100, function ($carts) use (&$sent): void {
                foreach ($carts as $cart) {
                    $activityAt = $cart->updated_at;
                    $claimAt = now();

                    // Claim the reminder atomically so overlapping scheduler runs cannot email twice.
                    $claimed = Cart::query()
                        ->whereKey($cart->id)
                        ->where('status', 'active')
                        ->where('updated_at', $activityAt)
                        ->whereHas('items')
                        ->where(fn ($query) => $query->whereNull('abandoned_reminder_sent_at')->orWhereColumn('abandoned_reminder_sent_at', '<', 'updated_at'))
                        ->update(['abandoned_reminder_sent_at' => $claimAt]);

                    if (!$claimed || !$cart->user) {
                        continue;
                    }

                    $bookTitles = $cart->items
                        ->map(fn ($item) => $item->product?->title)
                        ->filter()
                        ->unique()
                        ->take(5)
                        ->values()
                        ->all();

                    if (!$bookTitles) {
                        Cart::query()->whereKey($cart->id)->where('abandoned_reminder_sent_at', $claimAt)->update(['abandoned_reminder_sent_at' => null]);
                        continue;
                    }

                    try {
                        $cart->user->notify(new AbandonedCartNotification($bookTitles, $cart->items->count()));
                        $sent++;
                    } catch (Throwable $exception) {
                        // Leave the cart eligible so a later scheduler run can retry the failed email.
                        Cart::query()
                            ->whereKey($cart->id)
                            ->where('abandoned_reminder_sent_at', $claimAt)
                            ->update(['abandoned_reminder_sent_at' => null]);
                        Log::warning('Could not send abandoned-cart reminder.', [
                            'cart_id' => $cart->id,
                            'user_id' => $cart->user_id,
                            'exception' => $exception->getMessage(),
                        ]);
                    }
                }
            });

        return $sent;
    }
}
