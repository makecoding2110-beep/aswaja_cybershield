<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel Penyimpanan Riwayat Audit Pemeriksaan Tautan (Spesifikasi Bagian 10)
     */
    public function up(): void
    {
        Schema::create('link_scans', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->text('original_url');
            $table->text('normalized_url');
            $table->string('domain', 255)->index();
            $table->timestamp('scanned_at')->index();

            // Hasil masing-masing engine disimpan terpisah untuk transparansi audit
            $table->json('suricata_result')->nullable();
            $table->json('gemini_result')->nullable();
            $table->json('ai_comparison')->nullable();

            // Bukti indikator kompilasi
            $table->json('indicators');

            // Keputusan akhir
            $table->string('final_category', 50)->index();
            $table->string('risk_level', 20)->index();
            $table->integer('risk_score');
            $table->text('classification_reason');
            $table->string('decision_source', 50)->index();
            $table->string('scan_status', 30)->default('completed');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_scans');
    }
};

