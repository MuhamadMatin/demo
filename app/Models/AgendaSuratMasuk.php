<?php

namespace App\Models;

use App\Models\SuratMasuk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaSuratMasuk extends Model
{
    use HasFactory;

    public function surat_masuk()
    {
        return $this->hasMany(SuratMasuk::class);
    }
}
