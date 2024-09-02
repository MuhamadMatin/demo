<?php

namespace App\Filament\App\Resources\DisposisiResource\Pages;

use App\Filament\App\Resources\DisposisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisposisis extends ListRecords
{
    protected static string $resource = DisposisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
