<?php

namespace App\Filament\App\Resources\SuratKeluarResource\Pages;

use App\Filament\App\Resources\SuratKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratKeluars extends ListRecords
{
    protected static string $resource = SuratKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
