<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use ZipArchive;
use Filament\Actions;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class Backup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.backup';

    protected static ?string $navigationLabel = 'Backup';

    protected static ?string $pluralModelLabel = 'Backup';

    protected static ?string $slug = 'backup';

    protected static ?string $navigationGroup = 'Settings';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Export Backup')
            ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $tempDir = storage_path('app/temp_export');
                    if (!file_exists($tempDir)) {
                        mkdir($tempDir, 0755, true);
                    }

                    // Export Excel
                    $suratMasukFileName = 'surat_masuk.xlsx';
                    Excel::store(new class implements FromCollection, WithHeadings
                    {
                        public function collection()
                        {
                            return SuratMasuk::all();
                        }

                        public function headings(): array
                        {
                            return [
                                'id',
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
                        }
                    }, $suratMasukFileName, 'public', \Maatwebsite\Excel\Excel::XLSX);

                    // Export Excel
                    $suratKeluarFileName = 'surat_keluar.xlsx';
                    Excel::store(new class implements FromCollection, WithHeadings
                    {
                        public function collection()
                        {
                            return SuratKeluar::all();
                        }

                        public function headings(): array
                        {
                            return [
                                'id',
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
                                'tanda_tangan',
                                'tanggal_diterima',
                            ];
                        }
                    }, $suratKeluarFileName, 'public', \Maatwebsite\Excel\Excel::XLSX);

                    // Buat ZIP 
                    $zipFileName = 'surat_masuk_keluar.zip';
                    $zip = new ZipArchive();
                    $zipPath = $tempDir . '/' . $zipFileName;

                    if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                        $zip->addFile(storage_path('app/public/' . $suratMasukFileName), $suratMasukFileName);
                        $zip->addFile(storage_path('app/public/' . $suratKeluarFileName), $suratKeluarFileName);

                        // Masukan file ke ZIP
                        $suratMasukFiles = SuratMasuk::pluck('isi_file')->toArray();
                        foreach ($suratMasukFiles as $file) {
                            if ($file && Storage::exists('public/' . $file)) {
                                $zip->addFile(storage_path('app/public/' . $file), basename($file));
                            }
                        }

                        // Masukan file ke ZIP
                        $suratKeluarFiles = SuratKeluar::pluck('isi_file')->toArray();
                        foreach ($suratKeluarFiles as $file) {
                            if ($file && Storage::exists('public/' . $file)) {
                                $zip->addFile(storage_path('app/public/' . $file), basename($file));
                            }
                        }

                        $zip->close();
                    }

                    // Serahkan ZIP ke pengguna
                    return response()->download($zipPath)->deleteFileAfterSend(true);
                }),
        ];
    }
}
