<?php

namespace App\Filament\Resources\AgendaDisposisiResource\Pages;

use App\Filament\Resources\AgendaDisposisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListAgendaDisposisis extends ListRecords
{
    protected static string $resource = AgendaDisposisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                ]),
        ];
    }
}
