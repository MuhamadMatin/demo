<?php

namespace App\Filament\Resources\AsetSuratKeluarResource\Pages;

use App\Filament\Resources\AsetSuratKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsetSuratKeluar extends EditRecord
{
    protected static string $resource = AsetSuratKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
