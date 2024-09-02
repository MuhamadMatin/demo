<?php

namespace App\Models;

use App\Models\User;
use App\Models\SuratMasuk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratKeluar extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_agenda',
        'no_surat',
        'nama_pemohon',
        'perihal',
        'alamat_pemohon',
        'tanggal_surat',
        'tujuan_surat',
        'sifat',
        'isi_surat',
        'isi_file',
        'jenis_surat',
        'asal_surat',
        'tanda_tangan_ketua',
        'tanda_tangan_wakil',
        'tanggal_diterima',
    ];

    // public function agenda()
    // {
    //     return $this->belongsTo(agenda::class);
    // }

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'tujuan_surat');
    }

    public function surat_keluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'isi_file');
    }
}
