<?php

namespace App\Models;

use App\Models\SuratMasuk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetSuratMasuk extends Model
{
    use HasFactory;
    protected $fillable = [
        'asal_surat',
        'no_surat',
        'file'
    ];

    public function surat_masuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'no_surat', 'asal_surat', 'isi_file');
    }
}
