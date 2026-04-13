<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brotherhood_pages', function (Blueprint $table) {
            $table->json('government_board')->nullable()->after('legal_documents');
        });
    }

    public function down(): void
    {
        Schema::table('brotherhood_pages', function (Blueprint $table) {
            $table->dropColumn('government_board');
        });
    }
};
