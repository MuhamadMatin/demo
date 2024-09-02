<?php

namespace App\Filament\App\Resources\InboxSuratMasukResource\Pages;

use App\Filament\App\Resources\InboxSuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInboxSuratMasuk extends EditRecord
{
    protected static string $resource = InboxSuratMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
