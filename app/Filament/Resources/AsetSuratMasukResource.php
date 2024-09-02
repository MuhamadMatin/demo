<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AsetSuratMasuk;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AsetSuratMasukResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\AsetSuratMasukResource\RelationManagers;

class AsetSuratMasukResource extends Resource
{
    protected static ?string $model = AsetSuratMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-cloud-arrow-down';

    protected static ?string $navigationLabel = 'Aset masuk';

    protected static ?string $pluralModelLabel = 'Aset masuk';

    protected static ?string $slug = 'aset-masuk';

    protected static ?string $navigationGroup = 'Aset';

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
                TextColumn::make('asal_surat')->searchable(),
                TextColumn::make('no_surat')->sortable()->searchable(),
                TextColumn::make('file')->limit(25)->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }
                    return $state;
                }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('download')
                    ->label('Download')
                    ->action(function (AsetSuratMasuk $record) {
                        $filePath = $record->file;
                        return Storage::disk('public')->download($filePath);
                    })
                    ->icon('heroicon-o-arrow-down-tray')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                ]),
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
            'index' => Pages\ListAsetSuratMasuks::route('/'),
        ];
    }
}
