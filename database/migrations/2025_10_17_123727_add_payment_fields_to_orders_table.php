<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('paid_from_wallet', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->string('payment_method')->default('zarinpal');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'paid_from_wallet',
                'remaining_amount', 
                'payment_method',
                'final_amount'
            ]);
        });
    }
};