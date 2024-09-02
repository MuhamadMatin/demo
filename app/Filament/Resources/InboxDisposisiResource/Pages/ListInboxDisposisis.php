<?php

namespace App\Filament\Resources\InboxDisposisiResource\Pages;

use App\Filament\Resources\InboxDisposisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInboxDisposisis extends ListRecords
{
    protected static string $resource = InboxDisposisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
