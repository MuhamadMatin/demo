<?php

namespace App\Filament\Resources\AsetSuratKeluarResource\Pages;

use ZipArchive;
use Filament\Actions;
use App\Models\AsetSuratKeluar;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AsetSuratKeluarResource;

class ListAsetSuratKeluars extends ListRecords
{
    protected static string $resource = AsetSuratKeluarResource::class;

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
        $files = AsetSuratKeluar::all()->pluck('file')->toArray();
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
