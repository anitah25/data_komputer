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
        Schema::create('riwayat_perbaikan_komputers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('komputers')->onDelete('cascade');
            $table->string('jenis_maintenance');
            $table->string('keterangan');
            $table->string('teknisi');
            $table->string('komponen_diganti');
            $table->string('biaya_maintenance');
            $table->string('hasil_maintenance');
            $table->string('rekomendasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_perbaikan_komputers');
    }
};
