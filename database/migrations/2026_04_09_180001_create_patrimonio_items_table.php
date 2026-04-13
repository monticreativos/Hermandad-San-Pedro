<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patrimonio_items', function (Blueprint $table) {
            $table->id();
            $table->string('section_key', 64)->default('enseres')->index();
            $table->foreignId('patrimonio_item_category_id')
                ->nullable()
                ->constrained('patrimonio_item_categories')
                ->nullOnDelete();
            $table->json('name');
            $table->json('description');
            $table->string('year', 64)->nullable();
            $table->string('author', 255)->nullable();
            $table->json('gallery')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrimonio_items');
    }
};
