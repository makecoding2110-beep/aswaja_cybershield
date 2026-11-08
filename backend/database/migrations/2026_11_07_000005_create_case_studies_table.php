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
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('scenario');
            $table->string('sender', 150)->nullable();
            $table->text('message')->nullable();
            $table->string('risk_level', 50)->default('Sedang');
            $table->string('fiqih_principle', 150);
            $table->text('question');
            $table->json('options'); // Format: [{"key":"A","text":"...","correct":false}]
            $table->text('discussion');
            $table->timestamps();

            $table->index('risk_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_studies');
    }
};

