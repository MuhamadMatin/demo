<?php

namespace App\Filament\Resources\AgendaSuratMasukResource\Pages;

use App\Filament\Resources\AgendaSuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgendaSuratMasuk extends EditRecord
{
    protected static string $resource = AgendaSuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
