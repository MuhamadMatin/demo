<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\SuratMasuk;
use Filament\Tables\Table;
use App\Models\AgendaSuratMasuk;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\AgendaSuratMasukResource\Pages;
use App\Filament\App\Resources\AgendaSuratMasukResource\RelationManagers;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class AgendaSuratMasukResource extends Resource
{
    protected static ?string $model = AgendaSuratMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-plus';

    protected static ?string $navigationLabel = 'Agenda surat masuk';

    protected static ?string $pluralModelLabel = 'Agenda surat masuk';

    protected static ?string $slug = 'agenda-masuk';

    protected static ?string $navigationGroup = 'Agenda';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_agenda')->sortable()->searchable(),
                TextColumn::make('no_surat')->sortable()->searchable(),
                TextColumn::make('nama_pengirim')->searchable(),
                TextColumn::make('perihal')->searchable(),
                TextColumn::make('alamat_pengirim')->searchable(),
                TextColumn::make('tanggal_surat')->dateTime('d-F-Y')->sortable()->searchable(),
                TextColumn::make('user.name')->label('tujuan surat')->searchable(),
                TextColumn::make('sifat')->sortable()->searchable()->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'terbuka' => 'gray',
                        'segera' => 'warning',
                        'rahasia' => 'danger',
                        'biasa' => 'gray',
                    }),
                TextColumn::make('asal_surat')->searchable(),
                TextColumn::make('isi_file')->limit(10)->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }
                    return $state;
                }),
                TextColumn::make('isi_surat')->markdown(),
                TextColumn::make('tanggal_diterima')->dateTime('d-F-Y H:i:s')->sortable()->searchable(),
            ])
            ->query(function () {
                return SuratMasuk::query()->with('surat_masuk');
            })
            ->filters([
                //
            ])
            ->actions([
                ExportAction::make()->exports([
                    ExcelExport::make('table')->fromTable(),
                ])
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListAgendaSuratMasuks::route('/'),
        ];
    }
}
