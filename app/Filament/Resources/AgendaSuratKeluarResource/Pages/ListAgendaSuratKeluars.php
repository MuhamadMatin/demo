<?php

namespace App\Filament\Resources\AgendaSuratKeluarResource\Pages;

use Filament\Actions;
use pxlrbt\FilamentExcel\Columns\Column;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use App\Filament\Resources\AgendaSuratKeluarResource;

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
