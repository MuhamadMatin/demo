<?php

namespace App\Filament\App\Resources\AgendaSuratMasukResource\Pages;

use App\Filament\App\Resources\AgendaSuratMasukResource;
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
