<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patrimonio_item_categories', function (Blueprint $table) {
            $table->string('section_key', 64)->default('enseres')->after('id');
            $table->index(['section_key', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('patrimonio_item_categories', function (Blueprint $table) {
            $table->dropIndex(['section_key', 'sort_order']);
            $table->dropColumn('section_key');
        });
    }
};
