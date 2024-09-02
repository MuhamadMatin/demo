<?php

namespace App\Filament\App\Resources\SuratMasukResource\Pages;

use App\Filament\App\Resources\SuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratMasuk extends CreateRecord
{
    protected static string $resource = SuratMasukResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
