<?php

namespace App\Filament\Dashboard\Resources\SuratMasukResource\Pages;

use App\Filament\Dashboard\Resources\SuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratMasuk extends EditRecord
{
    protected static string $resource = SuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
