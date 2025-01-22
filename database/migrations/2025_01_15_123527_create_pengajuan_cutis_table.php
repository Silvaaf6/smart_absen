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
        Schema::create('pengajuan_cutis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->date('tgl_pengajuan');
            $table->enum('kategori_cuti', ['izin', 'sakit', 'cuti']);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('alasan');
            $table->enum('status', ['diizinkan', 'menunggu konfirmasi', 'tidak diizinkan'])->default('menunggu konfirmasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cutis');
    }
};
