<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brotherhood_pages', function (Blueprint $table) {
            $table->json('legal_documents')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('brotherhood_pages', function (Blueprint $table) {
            $table->dropColumn('legal_documents');
        });
    }
};
