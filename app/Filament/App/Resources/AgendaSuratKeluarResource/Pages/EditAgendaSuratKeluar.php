<?php

namespace App\Filament\App\Resources\AgendaSuratKeluarResource\Pages;

use App\Filament\App\Resources\AgendaSuratKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgendaSuratKeluar extends EditRecord
{
    protected static string $resource = AgendaSuratKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
