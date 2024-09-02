<?php

namespace App\Filament\Resources\AgendaSuratMasukResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use App\Filament\Resources\AgendaSuratMasukResource;

class ListAgendaSuratMasuks extends ListRecords
{
    protected static string $resource = AgendaSuratMasukResource::class;

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
