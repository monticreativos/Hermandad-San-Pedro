<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('obra_social_pages', function (Blueprint $table) {
            $table->json('charity_contact')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('obra_social_pages', function (Blueprint $table) {
            $table->dropColumn('charity_contact');
        });
    }
};
