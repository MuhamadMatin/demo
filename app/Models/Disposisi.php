<?php

namespace App\Models;

use App\Models\User;
use App\Models\SuratMasuk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Disposisi extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_active',
        'no_agenda', 
        'no_surat', 
        'tujuan_surat', 
        'sifat', 
        'asal_surat', 
        'isi_surat', 
        'isi_file',
        'tindakan',
        'tanggal_diterima',
        'tanggal_disposisi',
    ];

    public function disposisi()
    {
        return $this->belongsTo(agendaDisposisi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'tujuan_surat');
    }

    public function no_surat()
    {
        return $this->hasOne(SuratMasuk::class, 'no_surat');
    }
}
