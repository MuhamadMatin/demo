<?php

namespace App\Filament\App\Resources\SuratKeluarResource\Pages;

use App\Filament\App\Resources\SuratKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratKeluar extends CreateRecord
{
    protected static string $resource = SuratKeluarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
