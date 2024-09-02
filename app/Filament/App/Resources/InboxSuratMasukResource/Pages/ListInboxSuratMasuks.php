<?php

namespace App\Filament\App\Resources\InboxSuratMasukResource\Pages;

use App\Filament\App\Resources\InboxSuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInboxSuratMasuks extends ListRecords
{
    protected static string $resource = InboxSuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
