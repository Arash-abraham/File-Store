<?php

use App\Services\AbandonedCartReminderService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('cart:send-abandoned-reminders', function (AbandonedCartReminderService $service) {
    $sent = $service->sendEligibleReminders();
    $this->info("Sent {$sent} abandoned-cart reminder(s).");
})->purpose('Email opted-in customers about inactive carts');

Schedule::command('cart:send-abandoned-reminders')->hourly()->withoutOverlapping();
