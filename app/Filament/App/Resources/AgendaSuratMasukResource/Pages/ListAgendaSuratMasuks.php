<?php

namespace App\Filament\App\Resources\AgendaSuratMasukResource\Pages;

use App\Filament\App\Resources\AgendaSuratMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

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
