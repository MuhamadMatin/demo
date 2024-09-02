<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Disposisi;
use Filament\Tables\Table;
use App\Models\AgendaDisposisi;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use App\Filament\App\Resources\AgendaDisposisiResource\Pages;
use App\Filament\App\Resources\AgendaDisposisiResource\RelationManagers;

class AgendaDisposisiResource extends Resource
{
    protected static ?string $model = AgendaDisposisi::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $navigationLabel = 'Agenda disposisi';

    protected static ?string $pluralModelLabel = 'Agenda disposisi';

    protected static ?string $slug = 'agenda-disposisi';

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
                TextColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        return $state == 1 ? 'Active' : 'Not Active';
                    })
                    ->badge()->color(function ($state) {
                        return $state == 1 ? 'warning' : 'gray';
                    }),
                TextColumn::make('no_agenda')->sortable()->searchable(),
                TextColumn::make('no_surat')->sortable()->searchable(),
                TextColumn::make('user.name')->label('tujuan surat')->searchable(),
                TextColumn::make('sifat')->sortable()->searchable()->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'terbuka' => 'gray',
                        'segera' => 'warning',
                        'rahasia' => 'danger',
                        'biasa' => 'gray',
                    }),
                TextColumn::make('asal_surat')->searchable(),
                TextColumn::make('tindakan'),
                TextColumn::make('isi_file')->limit(10)->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }
                    return $state;
                }),
                TextColumn::make('isi_surat')->markdown(),
                TextColumn::make('tanggal_diterima')->dateTime('d-F-Y H:i:s')->sortable()->searchable(),
                TextColumn::make('tanggal_disposisi')->dateTime('d-F-Y H:i:s')->sortable()->searchable(),
            ])
            ->query(function () {
                return Disposisi::query()->with('disposisi');
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
            'index' => Pages\ListAgendaDisposisis::route('/'),
        ];
    }
}
