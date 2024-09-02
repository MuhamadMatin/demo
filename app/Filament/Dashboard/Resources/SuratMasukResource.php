<?php

namespace App\Filament\Dashboard\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\SuratMasuk;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Dashboard\Resources\SuratMasukResource\Pages;
use App\Filament\Dashboard\Resources\SuratMasukResource\RelationManagers;

class SuratMasukResource extends Resource
{
    protected static ?string $model = SuratMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Surat masuk';

    protected static ?string $pluralModelLabel = 'Surat masuk';

    protected static ?string $slug = 'surat-masuk';

    // protected static ?string $navigationGroup = 'Surat';

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
                TextColumn::make('no_agenda')->searchable(),
                TextColumn::make('no_surat')->searchable(),
                TextColumn::make('nama_pengirim')->searchable(),
                TextColumn::make('perihal')->searchable(),
                TextColumn::make('alamat_pengirim')->searchable(),
                TextColumn::make('tanggal_surat'),
                TextColumn::make('user.name')->label('tujuan surat')->searchable(),
                TextColumn::make('sifat')->searchable(),
                TextColumn::make('asal_surat')->searchable(),
                TextColumn::make('isi_surat'),
                TextColumn::make('isi_file')->limit(10)->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }
                    return $state;
                }),
                TextColumn::make('tanggal_diterima'),
            ])
            ->query(function () {
                $user = Auth::user();
                return suratMasuk::query()
                    ->with('surat_masuk')
                    ->where('tujuan_surat', $user->id);
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSuratMasuks::route('/'),
            'edit' => Pages\EditSuratMasuk::route('/{record}/edit'),
        ];
    }
}
