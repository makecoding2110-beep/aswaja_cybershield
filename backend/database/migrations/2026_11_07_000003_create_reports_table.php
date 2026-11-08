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
        Schema::create('reports', function (Blueprint $table) {
            $table->string('id', 50)->primary(); // Format tiket: LPR081, LPR082, dst.
            $table->string('url', 2048);
            $table->string('reporter', 150)->default('Hamba Allah');
            $table->string('category', 255);
            $table->text('description');
            $table->string('evidence_path', 1024)->nullable();
            $table->string('status', 50)->default('Masuk');
            $table->text('verification_notes')->nullable();
            $table->string('handled_by', 150)->default('Belum Ditugaskan');
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

