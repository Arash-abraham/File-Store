<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Brings the three printing tables up to the shape the storefront needs:
 *
 * - a printing house can be highlighted as a partner of the shop;
 * - a contract request keeps the note of the reviewer and the printing house
 *   that was created from it, so approving a request really publishes it;
 * - a print order keeps the tracking code of the shipment and the moment it
 *   was cancelled.
 *
 * Every change is guarded, so the migration is safe on a fresh database and on
 * one that already ran the original print migrations.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('print_houses') && !Schema::hasColumn('print_houses', 'is_featured')) {
            Schema::table('print_houses', function (Blueprint $table) {
                $table->boolean('is_featured')->default(false)->after('rating');
            });
        }

        if (Schema::hasTable('print_contract_requests')) {
            if (!Schema::hasColumn('print_contract_requests', 'print_house_id')) {
                Schema::table('print_contract_requests', function (Blueprint $table) {
                    $table->foreignId('print_house_id')->nullable()->constrained('print_houses')->nullOnDelete();
                });
            }

            if (!Schema::hasColumn('print_contract_requests', 'admin_note')) {
                Schema::table('print_contract_requests', function (Blueprint $table) {
                    $table->text('admin_note')->nullable()->after('message');
                });
            }

            if (!Schema::hasColumn('print_contract_requests', 'reviewed_at')) {
                Schema::table('print_contract_requests', function (Blueprint $table) {
                    $table->timestamp('reviewed_at')->nullable()->after('status');
                });
            }
        }

        if (Schema::hasTable('print_orders')) {
            if (!Schema::hasColumn('print_orders', 'tracking_code')) {
                Schema::table('print_orders', function (Blueprint $table) {
                    $table->string('tracking_code', 60)->nullable()->after('delivery_days');
                });
            }

            if (!Schema::hasColumn('print_orders', 'cancelled_at')) {
                Schema::table('print_orders', function (Blueprint $table) {
                    $table->timestamp('cancelled_at')->nullable()->after('status');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('print_houses') && Schema::hasColumn('print_houses', 'is_featured')) {
            Schema::table('print_houses', function (Blueprint $table) {
                $table->dropColumn('is_featured');
            });
        }

        if (Schema::hasTable('print_contract_requests')) {
            Schema::table('print_contract_requests', function (Blueprint $table) {
                if (Schema::hasColumn('print_contract_requests', 'print_house_id')) {
                    $table->dropForeign(['print_house_id']);
                    $table->dropColumn('print_house_id');
                }

                if (Schema::hasColumn('print_contract_requests', 'admin_note')) {
                    $table->dropColumn('admin_note');
                }

                if (Schema::hasColumn('print_contract_requests', 'reviewed_at')) {
                    $table->dropColumn('reviewed_at');
                }
            });
        }

        if (Schema::hasTable('print_orders')) {
            Schema::table('print_orders', function (Blueprint $table) {
                if (Schema::hasColumn('print_orders', 'tracking_code')) {
                    $table->dropColumn('tracking_code');
                }

                if (Schema::hasColumn('print_orders', 'cancelled_at')) {
                    $table->dropColumn('cancelled_at');
                }
            });
        }
    }
};
