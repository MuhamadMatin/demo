<?php

namespace App\Filament\Resources\AsetSuratMasukResource\Pages;

use App\Filament\Resources\AsetSuratMasukResource;
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
