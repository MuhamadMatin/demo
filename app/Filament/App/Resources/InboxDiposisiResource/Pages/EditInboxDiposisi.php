<?php

namespace App\Filament\App\Resources\InboxDiposisiResource\Pages;

use App\Filament\App\Resources\InboxDiposisiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInboxDiposisi extends EditRecord
{
    protected static string $resource = InboxDiposisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
