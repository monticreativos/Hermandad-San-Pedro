<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_applications', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 40)->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->text('message')->nullable();
            $table->string('locale', 5)->default('es');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_applications');
    }
};
