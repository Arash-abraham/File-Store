
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'payment_authority')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('payment_authority')
                    ->nullable()
                    ->after('id');
            });
        }
    }

    public function down(): void
    {
        // Do not drop the column here because it may
        // have been created by the original migration.
    }
};
