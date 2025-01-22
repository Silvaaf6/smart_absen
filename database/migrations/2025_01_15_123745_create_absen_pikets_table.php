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
        Schema::create('absen_pikets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jadwal_piket');
            $table->foreign('id_jadwal_piket')->references('id')->on('jadwal_pikets')->onDelete('cascade');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_berakhir');
            $table->string('deskripsi');
            $table->enum('status', ['hadir', 'tidak hadir']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_pikets');
    }
};
