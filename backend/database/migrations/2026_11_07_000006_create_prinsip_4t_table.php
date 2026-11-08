<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prinsip_4t', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // T1, T2, T3, T4
            $table->string('name', 100);
            $table->string('arabic', 100);
            $table->string('tagline', 255);
            $table->text('description');
            $table->string('link', 255);
            $table->string('icon', 100)->default('verified_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prinsip_4t');
    }
};

