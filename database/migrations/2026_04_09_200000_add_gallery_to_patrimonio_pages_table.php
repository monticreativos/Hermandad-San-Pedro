<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patrimonio_pages', function (Blueprint $table) {
            $table->json('gallery')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('patrimonio_pages', function (Blueprint $table) {
            $table->dropColumn('gallery');
        });
    }
};
