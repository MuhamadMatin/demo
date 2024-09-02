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
        Schema::create('disposisis', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true)->nullable();
            $table->integer('no_agenda');
            $table->char('no_surat');
            $table->foreignId('tujuan_surat')->references('id')->on('users')->constrainewd()->cascadeOnDelete();
            $table->enum('sifat', ['segera']);
            $table->string('asal_surat');
            $table->string('isi_surat')->nullable();
            $table->string('isi_file')->nullable();
            $table->string('tindakan')->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->date('tanggal_disposisi')->default(date("Y-m-d H:i:s"))->nullable();
            $table->timestamps();

            // forent ke no surat
            $table->foreign('no_surat')->references('no_surat')->on('surat_masuks')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};
