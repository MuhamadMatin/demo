<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Disposisi;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\DisposisiResource\Pages;
use App\Filament\App\Resources\DisposisiResource\RelationManagers;

class DisposisiResource extends Resource
{
    protected static ?string $model = Disposisi::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationLabel = 'Disposisi';

    protected static ?string $pluralModelLabel = 'Disposisi';

    protected static ?string $navigationGroup = 'Surat';

    protected static ?string $slug = 'disposisi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('is_active')
                            ->options([
                                1 => 'Active',
                                0 => 'Not Active',
                            ]),
                        TextInput::make('no_agenda')->disabled(),
                        TextInput::make('no_surat')->disabled(),
                        Select::make('tujuan_surat')->relationship(name: 'user', titleAttribute: 'name')->label('tujuan surat')->disabled(),
                        TextInput::make('sifat')->disabled(),
                        TextInput::make('tindakan')->required(),
                        DatePicker::make('tanggal_diterima')->format('d/F/Y')->required()->native(false)->closeOnDateSelection(),
                        MarkdownEditor::make('isi_surat')->maxLength(255)->disabled(),
                        FileUpload::make('isi_file')->image()->disabled(),
                    ])
                    ->columns(2),
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListDisposisis::route('/'),
            'create' => Pages\CreateDisposisi::route('/create'),
            'edit' => Pages\EditDisposisi::route('/{record}/edit'),
        ];
    }
}
