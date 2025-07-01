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
            Schema::create('donasi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kamar_id')->constrained('kamar')->onDelete('cascade');
                $table->string('nama_donatur');
                $table->string('asal');
                $table->enum('jenis', ['dana', 'barang']);
                $table->unsignedBigInteger('nominal')->nullable();
                $table->string('nama_barang')->nullable();
                $table->text('keterangan')->nullable();
                $table->string('bukti')->nullable();
                $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donasi');
    }
};
