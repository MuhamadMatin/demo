<?php

namespace App\Models;

use App\Models\User;
use App\Models\SuratKeluar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratMasuk extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_agenda',
        'no_surat',
        'nama_pengirim',
        'perihal',
        'alamat_pengirim',
        'tanggal_surat',
        'tujuan_surat',
        'sifat',
        'isi_surat',
        'isi_file',
        'asal_surat',
        'tanggal_diterima',
    ];

    // protected static function booted(){
    //     static::created(function ($suratmasuk) {
    //         agenda::create([
    //             'no_surat_masuk' => $suratmasuk->no_surat,
    //         ]);
    //     });
    // }

    // public function agenda()
    // {
    //     return $this->belongsTo(agenda::class);
    // }

    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'tujuan_surat');
    }

    public function surat_masuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'isi_file');
    }

    public function disposisi()
    {
        return $this->belongsTo(SuratMasuk::class, 'no_surat');
    }
}
