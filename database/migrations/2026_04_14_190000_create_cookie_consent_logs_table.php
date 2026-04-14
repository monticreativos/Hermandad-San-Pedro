<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cookie_consent_logs', function (Blueprint $table): void {
            $table->id();
            $table->uuid('consent_uuid')->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('locale', 5)->default('es')->index();
            $table->string('consent_version', 32)->index();
            $table->string('source', 32)->default('banner');
            $table->string('action', 32)->index();
            $table->json('preferences');
            $table->boolean('necessary')->default(true);
            $table->boolean('analytics')->default(false)->index();
            $table->boolean('marketing')->default(false)->index();
            $table->boolean('personalization')->default(false)->index();
            $table->string('ip_hash', 64)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('page_url', 1024)->nullable();
            $table->timestamp('consented_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cookie_consent_logs');
    }
};
