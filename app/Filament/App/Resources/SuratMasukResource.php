<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SuratMasukResource\Pages;
use App\Filament\App\Resources\SuratMasukResource\RelationManagers;
use App\Models\AsetSuratMasuk;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Card;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuratMasukResource extends Resource
{
    protected static ?string $model = Suratmasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Surat masuk';

    protected static ?string $pluralModelLabel = 'Surat masuk';

    protected static ?string $slug = 'surat-masuk';

    protected static ?string $navigationGroup = 'Surat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('no_agenda')->required(),
                        TextInput::make('no_surat')->required(),
                        TextInput::make('nama_pengirim')->required(),
                        TextInput::make('perihal')->required(),
                        TextInput::make('alamat_pengirim')->required(),
                        DatePicker::make('tanggal_surat')->format('d/F/Y')->required()->native(false)->closeOnDateSelection(),
                        Select::make('tujuan_surat')->label('tujuan surat')->relationship(name: 'user', titleAttribute: 'name')
                            ->required()->native(false),
                        Select::make('sifat')->options(['terbuka' => 'terbuka', 'segera' => 'segera', 'rahasia' => 'rahasia', 'biasa' => 'biasa'])->required()->native(false),
                        TextInput::make('asal_surat')->required(),
                        DatePicker::make('tanggal_diterima')->format('d/F/Y')->native(false)->closeOnDateSelection(),
                        FileUpload::make('isi_file')->preserveFilenames(),
                        MarkdownEditor::make('isi_surat'),
                    ])
                    ->columns(2),
            ]);
    }

    protected static function saveToAsetDigital($record)
    {
        AsetSuratMasuk::create([
            'asal_surat' => $record->asal_surat,
            'no_surat' => $record->no_surat,
            'file' => $record->isi_file,
        ]);
    }

    protected static function saveToDisposisi($record)
    {
        Disposisi::create([
            'no_agenda' => $record->no_agenda,
            'no_surat' => $record->no_surat,
            'tujuan_surat' => $record->tujuan_surat,
            'sifat' => 'segera',
            'asal_surat' => $record->asal_surat,
            'isi_surat' => $record->isi_surat,
            'isi_file' => $record->isi_file,
            'tanggal_disposisi',
            'is_active'
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
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('saveToDisposisi')
                        ->hidden(fn($record): bool => $record->sifat === 'rahasia' | auth()->user()->hasRole('USER'))
                        ->label(fn($record) => Disposisi::where('no_surat', $record->no_surat)->exists() ? 'Moved Disposisi' : 'Move Disposisi')
                        ->icon(fn($record) => Disposisi::where('no_surat', $record->no_surat)->exists() ? 'heroicon-o-check' : 'heroicon-o-archive-box-arrow-down')->action(function ($record, $livewire) {
                            if (Disposisi::where('no_surat', $record->no_surat)
                                ->exists()
                            ) {
                                Notification::make()
                                    ->title('Sudah Ada')
                                    ->body('Disposisi is Moved')
                                    ->warning()
                                    ->send();
                            } else {
                                self::saveToDisposisi($record);
                                Notification::make()
                                    ->title('Berhasil')
                                    ->body('Success disposisi 🎉')
                                    ->success()
                                    ->send();
                                $livewire->dispatch('$refresh');
                            }
                        }),
                    Action::make('saveToAsetDigital')
                        ->hidden(fn($record): bool => $record->sifat === 'rahasia' | auth()->user()->hasRole('USER'))
                        ->label(fn($record) => AsetSuratMasuk::where('no_surat', $record->no_surat)->exists() ? 'Moved Aset' : 'Move Aset')
                        ->icon(fn($record) => AsetSuratMasuk::where('no_surat', $record->no_surat)->exists() ? 'heroicon-o-check' : 'heroicon-o-archive-box-arrow-down')
                        ->action(function ($record, $livewire) {
                            if (AsetSuratMasuk::where('no_surat', $record->no_surat)->exists()) {
                                Notification::make()
                                    ->title('Sudah Ada')
                                    ->body('File is Moved')
                                    ->warning()
                                    ->send();
                            } elseif ($record->isi_file == null) {
                                Notification::make()
                                    ->title('Tidak ada isi file')
                                    ->body('File is null')
                                    ->danger()
                                    ->send();
                            } else {
                                self::saveToAsetDigital($record);
                                Notification::make()
                                    ->title('Berhasil')
                                    ->body('Success move 🎉')
                                    ->success()
                                    ->send();
                                $livewire->dispatch('$refresh');
                            }
                        }),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // ExportBulkAction::make(),
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
            'create' => Pages\CreateSuratMasuk::route('/create'),
            'edit' => Pages\EditSuratMasuk::route('/{record}/edit'),
        ];
    }
}
