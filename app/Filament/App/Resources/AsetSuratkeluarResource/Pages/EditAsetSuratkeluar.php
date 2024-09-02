<?php

namespace App\Filament\App\Resources\AsetSuratkeluarResource\Pages;

use App\Filament\App\Resources\AsetSuratkeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsetSuratkeluar extends EditRecord
{
    protected static string $resource = AsetSuratkeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
