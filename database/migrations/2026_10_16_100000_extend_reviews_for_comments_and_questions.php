<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Turns the reviews table into the storage for product comments, replies and
 * product questions (پرسش و پاسخ). Every row still needs admin approval
 * (status) before it is shown on the storefront.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Optional star rating: comments may carry one, replies and questions do not.
            $table->unsignedTinyInteger('rating')->nullable()->change();
            $table->string('type', 20)->default('comment')->after('user_id');
            $table->foreignId('parent_id')->nullable()->after('type')->constrained('reviews')->cascadeOnDelete();
            $table->string('image_path')->nullable()->after('body');
            $table->timestamp('edited_at')->nullable()->after('status');
            $table->index(['product_id', 'type', 'status']);
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'type', 'status']);
            $table->dropIndex(['parent_id']);
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['type', 'parent_id', 'image_path', 'edited_at']);
            $table->unsignedTinyInteger('rating')->nullable(false)->change();
        });
    }
};
