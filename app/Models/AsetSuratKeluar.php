<?php

namespace App\Models;

use App\Models\SuratKeluar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsetSuratKeluar extends Model
{
    use HasFactory;
    protected $fillable = [
        'asal_surat',
        'no_surat',
        'file'
    ];

    public function surat_keluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'no_surat', 'asal_surat', 'isi_file');
    }
}
