<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }

    public function down()
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->integer('sort_order')->default(0);
        });
    }
};