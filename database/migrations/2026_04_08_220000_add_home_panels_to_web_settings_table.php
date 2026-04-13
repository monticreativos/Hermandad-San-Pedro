<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->json('chapel_card_title')->nullable()->after('hero_slides');
            $table->json('chapel_blocks')->nullable();
            $table->json('chapel_footer')->nullable();
            $table->json('donation_card_title')->nullable();
            $table->json('donation_blocks')->nullable();
            $table->json('donation_footer')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('web_settings', function (Blueprint $table) {
            $table->dropColumn([
                'chapel_card_title',
                'chapel_blocks',
                'chapel_footer',
                'donation_card_title',
                'donation_blocks',
                'donation_footer',
            ]);
        });
    }
};
