<?php

namespace App\Filament\Resources\AsetSuratMasukResource\Pages;

use ZipArchive;
use Filament\Actions;
use App\Models\AsetSuratMasuk;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AsetSuratMasukResource;

class ListAsetSuratMasuks extends ListRecords
{
    protected static string $resource = AsetSuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Download Aset')
                ->action('downloadAllFiles')
                ->icon('heroicon-o-arrow-down-tray')
        ];
    }

    public function downloadAllFiles()
    {
        $files = AsetSuratMasuk::all()->pluck('file')->toArray();
        $zipFileName = 'aset_masuk.zip';
        $zipFilePath = storage_path($zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($files as $file) {
                $filePath = Storage::disk('public')->path($file);
                $zip->addFile($filePath, basename($file));
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Failed to create zip file'], 500);
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
