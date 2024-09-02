<?php

namespace App\Filament\App\Resources\AgendaSuratKeluarResource\Pages;

use App\Filament\App\Resources\AgendaSuratKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListAgendaSuratKeluars extends ListRecords
{
    protected static string $resource = AgendaSuratKeluarResource::class;

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
