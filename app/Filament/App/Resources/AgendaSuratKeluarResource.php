<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\SuratKeluar;
use Filament\Resources\Resource;
use App\Models\AgendaSuratKeluar;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use App\Filament\App\Resources\AgendaSuratKeluarResource\Pages;
use App\Filament\App\Resources\AgendaSuratKeluarResource\RelationManagers;

class AgendaSuratKeluarResource extends Resource
{
    protected static ?string $model = AgendaSuratKeluar::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-minus';

    protected static ?string $navigationLabel = 'Agenda surat keluar';

    protected static ?string $pluralModelLabel = 'Agenda surat keluar';

    protected static ?string $slug = 'agenda-keluar';

    protected static ?string $navigationGroup = 'Agenda';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_agenda')->sortable()->searchable(),
                TextColumn::make('no_surat')->sortable()->searchable(),
                TextColumn::make('nama_pemohon')->searchable(),
                TextColumn::make('perihal')->searchable(),
                TextColumn::make('alamat_pemohon')->searchable(),
                TextColumn::make('tanggal_surat')->dateTime('d-M-Y')->sortable()->searchable(),
                TextColumn::make('tujuan_surat')->searchable(),
                TextColumn::make('sifat')->sortable()->searchable()->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'terbuka' => 'gray',
                        'segera' => 'warning',
                        'rahasia' => 'danger',
                        'biasa' => 'gray',
                    }),
                TextColumn::make('jenis_surat')->searchable(),
                TextColumn::make('asal_surat')->searchable(),
                TextColumn::make('isi_file')->limit(10)->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }
                    return $state;
                }),
                TextColumn::make('isi_surat')->markdown(),
                TextColumn::make('tanda_tangan')
                    ->formatStateUsing(fn($state) => $state ? "<img src='{$state}' alt='Tanda Tangan'>" : 'Tidak ada tanda tangan')
                    ->html(),
                TextColumn::make('tanggal_diterima')->dateTime('d-F-Y H:i:s')->sortable()->searchable(),
            ])
            ->query(function () {
                return SuratKeluar::query()->with('surat_keluar');
            })
            ->filters([
                //
            ])
            ->actions([
                ExportAction::make()->exports([
                    ExcelExport::make('table')->fromTable(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgendaSuratKeluars::route('/'),
        ];
    }
}
