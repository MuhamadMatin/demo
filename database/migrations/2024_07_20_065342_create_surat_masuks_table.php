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
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->integer('no_agenda');
            $table->char('no_surat')->index()->cascadeOnUpdate();
            $table->string('nama_pengirim');
            $table->string('perihal');
            $table->char('alamat_pengirim');
            $table->date('tanggal_surat');
            $table->foreignId('tujuan_surat')->references('id')->on('users')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('sifat', ['terbuka', 'segera', 'rahasia', 'biasa']);
            $table->string('asal_surat')->index()->cascadeOnUpdate();
            $table->string('isi_surat')->nullable();
            $table->string('isi_file')->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
