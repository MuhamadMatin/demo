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
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->boolean('Acc');
            $table->integer('no_agenda');
            $table->char('no_surat')->index()->cascadeOnUpdate();
            $table->string('nama_pemohon');
            $table->string('perihal');
            $table->char('alamat_pemohon');
            $table->date('tanggal_surat');
            $table->string('tujuan_surat');
            $table->enum('sifat', ['terbuka', 'segera', 'rahasia', 'biasa']);
            $table->string('asal_surat')->index()->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('jenis_surat', ['baru', 'balasan'])->default('baru');
            $table->longText('isi_surat')->nullable();
            $table->string('isi_file')->nullable();
            $table->text('tanda_tangan_ketua')->nullable();
            $table->text('tanda_tangan_wakil')->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
