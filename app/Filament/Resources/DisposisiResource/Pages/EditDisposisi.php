<?php

namespace App\Filament\Resources\DisposisiResource\Pages;

use App\Filament\Resources\DisposisiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisposisi extends EditRecord
{
    protected static string $resource = DisposisiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
