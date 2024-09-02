<?php

namespace App\Filament\App\Resources\AgendaDisposisiResource\Pages;

use App\Filament\App\Resources\AgendaDisposisiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAgendaDisposisi extends EditRecord
{
    protected static string $resource = AgendaDisposisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
