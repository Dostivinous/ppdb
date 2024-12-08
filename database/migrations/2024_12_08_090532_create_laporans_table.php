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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_penerimaan')->unique();
            $table->string('nama_peserta_didik');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('nomor_telp_peserta');
            $table->string('nomor_telp_ayah');
            $table->string('nomor_telp_ibu');
            $table->string('asal_sekolah');
            $table->text('alamat_rumah');
            $table->date('tanggal_pendaftaran');
            $table->json('dokumen');
            $table->enum('ukuran_baju', ['S', 'M', 'L', 'XL', '2XL', '3XL']);
            $table->enum('pembayaran', ['Lunas', 'Cicil']);
            $table->boolean('is_validated')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
