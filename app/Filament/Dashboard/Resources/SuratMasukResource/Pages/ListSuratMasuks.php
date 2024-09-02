<?php

namespace App\Filament\Dashboard\Resources\SuratMasukResource\Pages;

use App\Filament\Dashboard\Resources\SuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratMasuks extends ListRecords
{
    protected static string $resource = SuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
