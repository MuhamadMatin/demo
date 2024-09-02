<?php

namespace App\Filament\App\Resources\AsetSuratMasukResource\Pages;

use App\Filament\App\Resources\AsetSuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsetSuratMasuk extends EditRecord
{
    protected static string $resource = AsetSuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
