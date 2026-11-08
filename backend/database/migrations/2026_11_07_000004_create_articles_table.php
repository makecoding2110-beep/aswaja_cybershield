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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('category', 100);
            $table->string('excerpt', 500)->nullable();
            $table->text('content');
            $table->string('author', 150)->default('Tim Edukasi Aswaja');
            $table->string('read_time', 50)->default('5 Menit Baca');
            $table->string('status', 50)->default('Published');
            $table->string('cover_image', 1024)->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

