<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom techniques (jsonb/json) untuk mencatat 3 teknik penyusupan judol
     * (Web Defacement, Open Redirect, Typosquatting) pada tabel link_scans.
     */
    public function up(): void
    {
        Schema::table('link_scans', function (Blueprint $table) {
            $table->json('techniques')->nullable()->after('indicators');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_scans', function (Blueprint $table) {
            $table->dropColumn('techniques');
        });
    }
};

