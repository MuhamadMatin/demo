<?php

namespace App\Filament\App\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\HandlesSettings;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\SuratKeluar;
use PhpOffice\PhpWord\PhpWord;
use App\Models\AsetSuratKeluar;
use Filament\Resources\Resource;
use PhpOffice\PhpWord\IOFactory;
use Filament\Forms\Components\Card;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use PhpOffice\PhpWord\Element\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Shared\Converter;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\SuratKeluarResource\Pages;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use App\Filament\App\Resources\SuratKeluarResource\RelationManagers;
use Saade\FilamentAutograph\Forms\Components\Enums\DownloadableFormat;

class SuratKeluarResource extends Resource
{
    use HandlesSettings;

    protected static ?string $model = SuratKeluar::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';

    protected static ?string $navigationLabel = 'Surat keluar';

    protected static ?string $pluralModelLabel = 'Surat keluar';

    protected static ?string $slug = 'surat-keluar';

    protected static ?string $navigationGroup = 'Surat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        // Toggle::make('Acc')
                        //     ->disabled(!auth()->user()->hasRole(['ADMIN', 'Admin', 'admin', 'WAKIL', 'Wakil', 'wakil']))
                        //     ->onIcon('heroicon-m-check')
                        //     ->offIcon('heroicon-m-x-mark'),
                        TextInput::make('no_agenda')->required(),
                        TextInput::make('no_surat')->required(),
                        TextInput::make('nama_pemohon')->required(),
                        TextInput::make('perihal')->required(),
                        TextInput::make('alamat_pemohon')->required(),
                        DatePicker::make('tanggal_surat')->format('d/F/Y')->required()->native(false)->closeOnDateSelection(),
                        DatePicker::make('created_at')
                            ->format('d/F/Y')
                            ->default(Carbon::now())
                            ->native(false)
                            ->closeOnDateSelection()
                            ->required(),
                        TextInput::make('tujuan_surat')->required(),
                        Select::make('sifat')->options(['terbuka' => 'terbuka', 'segera' => 'segera', 'rahasia' => 'rahasia', 'biasa' => 'biasa'])->required()->native(false),
                        Select::make('jenis_surat')->options(['baru' => 'baru', 'balasan' => 'balasan'])->default('baru')->native(false)->required(fn($context) => $context === 'create')
                            ->disabled(fn($context) => $context === 'create'),
                        TextInput::make('asal_surat')->required(),
                        SignaturePad::make('tanda_tangan_ketua')
                            ->filename('tanda_tangan_ketua')
                            ->downloadable()
                            ->downloadableFormats([
                                DownloadableFormat::PNG,
                                DownloadableFormat::JPG,
                            ])
                            ->backgroundColor('rgba(255, 255, 255, 0)')
                            ->backgroundColorOnDark('#fff')
                            ->exportBackgroundColor('#fff')
                            ->penColor('#000')
                            ->penColorOnDark('#000')
                            ->exportPenColor('#000')
                            ->disabled(fn() => !auth()->user()->hasRole(['KETUA', 'Ketua', 'ketua'])),
                        SignaturePad::make('tanda_tangan_wakil')
                            ->filename('tanda_tangan_wakil')
                            ->downloadable()
                            ->downloadableFormats([
                                DownloadableFormat::PNG,
                                DownloadableFormat::JPG,
                            ])
                            ->backgroundColor('rgba(255, 255, 255, 0)')
                            ->backgroundColorOnDark('#fff')
                            ->exportBackgroundColor('#fff')
                            ->penColor('#000')
                            ->penColorOnDark('#000')
                            ->exportPenColor('#000')
                            ->disabled(fn() => !auth()->user()->hasRole(['WAKIL', 'Wakil', 'wakil']))

                    ])
                    ->columns(2),
                Card::make()
                    ->schema([
                        FileUpload::make('isi_file')->preserveFilenames(),
                        MarkdownEditor::make('isi_surat'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function generateWordDocument($record)
    {
        $instance = new static();
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Set default font
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        // Add a section
        $section = $phpWord->addSection();

        // Add header (Institution details and logo)
        $header = $section->addHeader();
        $table = $header->addTable();
        $header->firstPage();
        $table->addRow();

        // Header logo
        $cell1 = $table->addCell(2000);
        $logo = $instance->getLogo();
        $brand = $instance->getBrand();
        $email = $instance->getEmail();
        $phone = $instance->getPhone();
        $address = $instance->getAddress();
        $tanggalSurat = new \DateTime($record->tanggal_surat);
        $formattedTanggalSurat = $tanggalSurat->format('d F Y');
        $url = __DIR__ . '/../../../../public';
        $logoPath = realpath($url . $logo);
        $cell1->addImage($logoPath, [
            'width' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(2.5),
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        ]);

        // Header text
        $cell2 = $table->addCell(7500);
        $cell2->addText($brand, ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $cell2->addText($address, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $cell2->addText('Telp: ' . $phone, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $cell2->addText('E-mail: ' . $email, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Add a line break
        $section->addTextBreak(1);

        $section->addText(
            'Nomor           : ' . $record->no_surat . "\t" . $record->alamat_pemohon . ', ' . $formattedTanggalSurat,
            [],
            [
                'tabs' => [
                    new \PhpOffice\PhpWord\Style\Tab('right', 9800)
                ]
            ]
        );

        $section->addText('Klasifikasi     : ' . $record->sifat);
        $section->addText('Lampiran       : -');

        $section->addText(
            'Perihal           : ' . $record->perihal . "\t" . 'Yth. ' . $record->tujuan_surat,
            [],
            [
                'tabs' => [
                    new \PhpOffice\PhpWord\Style\Tab('right', 9800)
                ]
            ]
        );
        $section->addText(
            "\t" . 'Ditempat',
            [],
            [
                'tabs' => [
                    new \PhpOffice\PhpWord\Style\Tab('right', 9800)
                ]
            ]
        );

        $section->addTextBreak(2);
        $section->addText($record->isi_surat);

        $section->addTextBreak(1);
        $section->addText('Demikian atas perhatian dan perkenannya diucapkan terima kasih.', [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);

        $ketua = User::role('Ketua')->first();
        if ($record->tanda_tangan_ketua) {
            $data = $record->tanda_tangan_ketua;
            $encoded_image = explode(",", $data)[1];
            $decoded_image = base64_decode($encoded_image);

            $tempImagePath = public_path('signature_ketua.png');
            file_put_contents($tempImagePath, $decoded_image);

            $section->addTextBreak(2);
            $section->addText($brand, ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
            $section->addText('KETUA', [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
            $section->addImage($tempImagePath, [
                'width' => 100,
                'height' => 100,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,
            ]);
            $section->addText($ketua->name, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        }

        if ($record->isi_file !== null) {
            $file = $record->isi_file;
            $storage = Storage::url($file);
            $urlFile = realpath($url . $storage);

            $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            if ($fileExtension !== 'pdf') {
                $section->addPageBreak();

                $section->addImage($urlFile, [
                    'width' => Converter::cmToPixel(15.5),
                    'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
                    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
                    'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
                    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
                ]);
            } else {
                $section->addText("Isi File: " . $record->isi_file);
            }
        }

        $fileName = "Surat_Keluar_{$record->no_surat}.docx";
        $filePath = storage_path("app/public/{$fileName}");

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    protected static function saveToAsetDigital($record)
    {
        AsetSuratKeluar::create([
            'asal_surat' => $record->asal_surat,
            'no_surat' => $record->no_surat,
            'file' => $record->isi_file,
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ToggleColumn::make('Acc')
                    ->disabled(fn($record) => !$record->tanda_tangan_wakil && !$record->tanda_tangan_ketua || !auth()->user()->hasRole(['ADMIN', 'Admin', 'admin', 'WAKIL', 'Wakil', 'wakil']))
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark'),
                TextColumn::make('no_agenda')->sortable()->searchable(),
                TextColumn::make('no_surat')->sortable()->searchable(),
                TextColumn::make('nama_pemohon')->searchable(),
                TextColumn::make('perihal')->searchable(),
                TextColumn::make('alamat_pemohon')->searchable(),
                TextColumn::make('tanggal_surat')->dateTime('d-F-Y')->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime('d-F-Y H:i:s')->label('tanggal_dibuat'),
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
                TextColumn::make('isi_surat')->markdown()->limit(20),
                TextColumn::make('tanda_tangan_ketua')
                    ->formatStateUsing(fn($state) => $state ? "<img src='{$state}' alt='Tanda Tangan ketua'>" : 'Tidak ada tanda tangan')
                    ->html(),
                TextColumn::make('tanda_tangan_wakil')
                    ->formatStateUsing(fn($state) => $state ? "<img src='{$state}' alt='Tanda Tangan wakil'>" : 'Tidak ada tanda tangan')
                    ->html(),
                TextColumn::make('tanggal_diterima')->dateTime('d-F-Y H:i:s')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('generateWordDocument')
                        ->hidden(
                            fn($record): bool =>
                            !$record->Acc
                        )
                        ->label('Generate Word')
                        ->icon('heroicon-o-document-text')
                        ->action(fn($record) => self::generateWordDocument($record)),
                    Action::make('saveToAsetDigital')
                        ->hidden(fn($record): bool => $record->sifat === 'rahasia' | auth()->user()->hasRole('USER'))
                        ->label(fn($record) => AsetSuratKeluar::where('no_surat', $record->no_surat)->exists() ? 'Moved Aset' : 'Move to Aset')
                        ->icon(fn($record) => AsetSuratKeluar::where('no_surat', $record->no_surat)->exists() ? 'heroicon-o-check' : 'heroicon-o-archive-box-arrow-down')
                        ->action(function ($record, $livewire) {
                            if (AsetSuratKeluar::where('no_surat', $record->no_surat)->exists()) {
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
                    Tables\Actions\DeleteAction::make()
                ]),
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
            'index' => Pages\ListSuratKeluars::route('/'),
            'create' => Pages\CreateSuratKeluar::route('/create'),
            'edit' => Pages\EditSuratKeluar::route('/{record}/edit'),
        ];
    }
}
