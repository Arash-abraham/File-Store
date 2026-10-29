<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_favorites', function (Blueprint $table) {
            $table->unsignedBigInteger('price_drop_notified_price')->nullable()->after('saved_price');
        });
    }

    public function down(): void
    {
        Schema::table('product_favorites', function (Blueprint $table) {
            $table->dropColumn('price_drop_notified_price');
        });
    }
};
