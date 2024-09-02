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
        Schema::create('aset_surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->char('no_surat');
            $table->string('asal_surat');
            $table->string('file');
            $table->timestamps();

            // forent ke no surat
            $table->foreign('no_surat')->references('no_surat')->on('surat_keluars')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('asal_surat')->references('asal_surat')->on('surat_keluars')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset_surat_keluars');
    }
};
