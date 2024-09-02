<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\SuratMasuk;
use Filament\Tables\Table;
use App\Models\InboxSuratMasuk;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InboxSuratMasukResource\Pages;
use App\Filament\Resources\InboxSuratMasukResource\RelationManagers;

class InboxSuratMasukResource extends Resource
{
    protected static ?string $model = InboxSuratMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationLabel = 'Inbox surat masuk';

    protected static ?string $pluralModelLabel = 'Inbox surat masuk';

    protected static ?string $slug = 'inbox-masuk';

    protected static ?string $navigationGroup = 'Inbox';

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
            ->actions([])
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
            'index' => Pages\ListInboxSuratMasuks::route('/'),
        ];
    }
}
