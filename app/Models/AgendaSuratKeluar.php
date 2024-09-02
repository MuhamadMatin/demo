<?php

namespace App\Models;

use App\Models\SuratKeluar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaSuratKeluar extends Model
{
    use HasFactory;

    public function surat_keluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }
}
