<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenerimaanResource\Pages;
use App\Models\Penerimaan;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Infolist;    
use Filament\Infolists\Components\Tabs;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;
use Filament\Tables\Actions\BulkAction;


class PenerimaanResource extends Resource
{
    protected static ?string $model = Penerimaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';

    protected static ?string $navigationGroup = 'Form PPDB';

    protected static ?string $pluralLabel = 'Penerimaan';

    protected static ?string $recordTitleAttribute = 'pendaftaran.nomor_form';

    protected static ?string $singularLabel = 'Penerimaan';

    public static function getNavigationBadge(): ?String
    {
        return Penerimaan::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([

                Wizard\Step::make('Pendaftaran')
                    ->schema([
                        Select::make('pendaftaran_id')
                            ->label('Pendaftaran')
                            ->options(
                                Pendaftaran::whereDoesntHave('penerimaan', ) // Tampilkan hanya data yang belum divalidasi
                                    ->pluck('nomor_form', 'id')
                            )
                            ->searchable()
                            ->reactive()
                            ->default(fn ($get) => $get('record.pendaftaran_id')) // Memastikan data yang sudah ada terisi saat edit
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    $pendaftaran = Pendaftaran::find($state);

                                    if ($pendaftaran) {
                                        $set('nama_peserta_didik', $pendaftaran->nama_peserta_didik);
                                        $set('jurusan', $pendaftaran->jurusan);
                                        $set('jenis_kelamin', $pendaftaran->jenis_kelamin);
                                        $set('nama_ayah', $pendaftaran->nama_ayah);
                                        $set('nama_ibu', $pendaftaran->nama_ibu);
                                        $set('nomor_telp_peserta', $pendaftaran->nomor_telp_peserta);
                                        $set('nomor_telp_ayah', $pendaftaran->nomor_telp_ayah);
                                        $set('nomor_telp_ibu', $pendaftaran->nomor_telp_ibu);
                                        $set('asal_sekolah', $pendaftaran->asal_sekolah);
                                        $set('alamat_rumah', $pendaftaran->alamat_rumah);
                                        $set('tanggal_pendaftaran', $pendaftaran->tanggal_pendaftaran);
                                    }
                                }
                            })
                            ->required(),

                        TextInput::make('nama_peserta_didik')
                            ->label('Nama Peserta Didik')
                            ->disabled()
                            ->default(fn ($get) => $get('record.nama_peserta_didik')),

                        TextInput::make('jurusan')
                            ->label('Jurusan')
                            ->disabled()
                            ->default(fn ($get) => $get('record.jurusan')),

                        TextInput::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->disabled()
                            ->default(fn ($get) => $get('record.jenis_kelamin')),

                        TextInput::make('nama_ayah')
                            ->label('Nama Ayah')
                            ->disabled()
                            ->default(fn ($get) => $get('record.nama_ayah')),

                        TextInput::make('nama_ibu')
                            ->label('Nama Ibu')
                            ->disabled()
                            ->default(fn ($get) => $get('record.nama_ibu')),

                        TextInput::make('nomor_telp_peserta')
                            ->label('Nomor Telepon Peserta')
                            ->disabled()
                            ->default(fn ($get) => $get('record.nomor_telp_peserta')),

                        TextInput::make('nomor_telp_ayah')
                            ->label('Nomor Telepon Ayah')
                            ->disabled()
                            ->default(fn ($get) => $get('record.nomor_telp_ayah')),

                        TextInput::make('nomor_telp_ibu')
                            ->label('Nomor Telepon Ibu')
                            ->disabled()
                            ->default(fn ($get) => $get('record.nomor_telp_ibu')),

                        TextInput::make('asal_sekolah')
                            ->label('Asal Sekolah')
                            ->disabled()
                            ->default(fn ($get) => $get('record.asal_sekolah')),

                        TextInput::make('alamat_rumah')
                            ->label('Alamat Rumah')
                            ->disabled()
                            ->default(fn ($get) => $get('record.alamat_rumah')),

                        TextInput::make('tanggal_pendaftaran')
                            ->label('Tanggal Pendaftaran')
                            ->disabled()
                            ->default(fn ($get) => $get('record.tanggal_pendaftaran')),
                    ]),

                Wizard\Step::make('Dokumen')
                    ->schema([
                        CheckboxList::make('dokumen')
                            ->label('Dokumen')
                            ->options([
                                'KK' => 'KK',
                                'Akte' => 'Akte',
                                'Ijazah' => 'Ijazah',
                                'Pas Foto' => 'Pas Foto',
                                'Tidak Membawa Dokumen' => 'Tidak Membawa Dokumen',
                            ])
                            ->helperText('Pilih dokumen yang dibawa.')
                            ->required()
                            ->reactive()
                            ->default(fn ($get) => json_decode($get('record.dokumen'), true)) // Default nilai dokumen berdasarkan data yang ada
                            ->afterStateUpdated(function (callable $set, $state) {
                                if (in_array('Tidak Membawa Dokumen', $state)) {
                                    $set('dokumen', ['Tidak Membawa Dokumen']);
                                }
                            }),
                    ]),

                Wizard\Step::make('Kelengkapan')
                    ->schema([
                        Select::make('ukuran_baju')
                            ->label('Ukuran Baju')
                            ->options([
                                'S' => 'S',
                                'M' => 'M',
                                'L' => 'L',
                                'XL' => 'XL',
                                '2XL' => '2XL',
                                '3XL' => '3XL',
                            ])
                            ->required()
                            ->default(fn ($get) => $get('record.ukuran_baju')),

                        Select::make('pembayaran')
                            ->label('Pembayaran')
                            ->options([
                                'Lunas' => 'Lunas',
                                'Cicil' => 'Cicil',
                            ])
                            ->required()
                            ->default(fn ($get) => $get('record.pembayaran')),

                        Toggle::make('is_validated')
                            ->label('Validasi')
                            ->default(fn ($get) => $get('record.is_validated'))
                            ->required(),
                    ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pendaftaran.nomor_form')->label('Nomor Form')->sortable(),
                TextColumn::make('nomor_penerimaan')->label('Nomor Penerimaan')->sortable(),
                TextColumn::make('pendaftaran.nama_peserta_didik')->label('Nama Calon Peserta Didik')->toggleable(isToggledHiddenByDefault: true)->sortable(),
                TextColumn::make('pendaftaran.jurusan')->label('Jurusan')->toggleable(isToggledHiddenByDefault: true)->sortable(),
                TextColumn::make('pendaftaran.asal_sekolah')->label('Asal Sekolah')->toggleable(isToggledHiddenByDefault: true)->sortable(),
                TextColumn::make('dokumen')->label('Dokumen')->sortable(),
                TextColumn::make('pembayaran')->label('Pembayaran')->sortable(),
                TextColumn::make('pendaftaran.tanggal_pendaftaran')->label('Tanggal Pendaftaran')->toggleable(isToggledHiddenByDefault: true)->sortable(),
                BooleanColumn::make('is_validated')->label('Tervalidasi')->sortable(),
            ])
            ->filters([
                Filter::make('Belum Divalidasi')
                    ->query(fn ($query) => $query->where('is_validated', false))
                    ->label('Belum Divalidasi'),
                Filter::make('Divalidasi')
                    ->query(fn ($query) => $query->where('is_validated', true))
                    ->label('Divalidasi'),
            ])
            ->actions([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    // Action::make('Export')
                    //     ->label('Export ke Excel')
                    //     ->action(function () {
                    //         $data = Penerimaan::with('pendaftaran')
                    //             ->get()
                    //             ->map(function ($item) {
                    //                 return [
                    //                     'Nomor Form' => $item->pendaftaran->nomor_form ?? '-',
                    //                     'Nama Peserta Didik' => $item->pendaftaran->nama_peserta_didik ?? '-',
                    //                     'Nama Ayah' => $item->pendaftaran->nama_ayah ?? '-',
                    //                     'Nama Ibu' => $item->pendaftaran->nama_ibu ?? '-',
                    //                     'Nomor Telepon Peserta' => $item->pendaftaran->nomor_telp_peserta ?? '-',
                    //                     'Nomor Telepon Ayah' => $item->pendaftaran->nomor_telp_ayah ?? '-',
                    //                     'Nomor Telepon Ibu' => $item->pendaftaran->nomor_telp_ibu ?? '-',
                    //                     'Asal Sekolah' => $item->pendaftaran->asal_sekolah ?? '-',
                    //                     'Alamat Rumah' => $item->pendaftaran->alamat_rumah ?? '-',
                    //                     'Tanggal Pendaftaran' => $item->pendaftaran->tanggal_pendaftaran ?? '-',
                    //                     'Dokumen' => $item->dokumen,
                    //                     'Pembayaran' => $item->pembayaran,
                    //                     'Validasi' => $item->is_validated ? 'Ya' : 'Tidak',
                    //                 ];
                    //             });

                    //         return (new FastExcel($data))->download('penerimaan.xlsx');
                    //     })
                        // ->icon('heroicon-o-download')
            ])
            ->searchable()
            ->headerActions([
                Action::make('export')
                ->label('Export ke Excel')
                // ->icon('heroicon-o-download')
                ->action(function () {
                    // Ambil semua data yang ingin diekspor
                    $data = Penerimaan::with('pendaftaran')
                        ->get()
                        ->map(function ($item) {
                            return [
                                'Nomor Form' => $item->pendaftaran->nomor_form ?? '-',
                                'Nama Peserta Didik' => $item->pendaftaran->nama_peserta_didik ?? '-',
                                'Jurusan' => $item->pendaftaran->jurusan ?? '-',
                                'Jenis Kelamin' => $item->pendaftaran->jenis_kelamin ?? '-',
                                'Nama Ayah' => $item->pendaftaran->nama_ayah ?? '-',
                                'Nama Ibu' => $item->pendaftaran->nama_ibu ?? '-',
                                'Nomor Telepon Peserta' => $item->pendaftaran->nomor_telp_peserta ?? '-',
                                'Nomor Telepon Ayah' => $item->pendaftaran->nomor_telp_ayah ?? '-',
                                'Nomor Telepon Ibu' => $item->pendaftaran->nomor_telp_ibu ?? '-',
                                'Asal Sekolah' => $item->pendaftaran->asal_sekolah ?? '-',
                                'Alamat Rumah' => $item->pendaftaran->alamat_rumah ?? '-',
                                'Tanggal Pendaftaran' => $item->pendaftaran->tanggal_pendaftaran ?? '-',
                                'Dokumen' => $item->dokumen,
                                'Pembayaran' => $item->pembayaran,
                                'Validasi' => $item->is_validated ? 'Ya' : 'Tidak',
                            ];
                        });

                    // Unduh file Excel
                    return (new FastExcel($data))->download('penerimaan.xlsx');
                })
                ->requiresConfirmation() // Opsional: Tambahkan konfirmasi sebelum aksi
                ->color('primary') // Warna tombol
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                BulkAction::make('export')
                ->label('Export ke Excel')
                ->action(function (Collection $records) {
                    // Ambil data dari record yang dipilih
                    $data = $records->map(function ($item) {
                        return [
                            'Nomor Form' => $item->pendaftaran->nomor_form ?? '-',
                            'Nama Peserta Didik' => $item->pendaftaran->nama_peserta_didik ?? '-',
                            'Jurusan' => $item->pendaftaran->jurusan ?? '-',
                            'Jenis Kelamin' => $item->pendaftaran->jenis_kelamin ?? '-',
                            'Nama Ayah' => $item->pendaftaran->nama_ayah ?? '-',
                            'Nama Ibu' => $item->pendaftaran->nama_ibu ?? '-',
                            'Nomor Telepon Peserta' => $item->pendaftaran->nomor_telp_peserta ?? '-',
                            'Nomor Telepon Ayah' => $item->pendaftaran->nomor_telp_ayah ?? '-',
                            'Nomor Telepon Ibu' => $item->pendaftaran->nomor_telp_ibu ?? '-',
                            'Asal Sekolah' => $item->pendaftaran->asal_sekolah ?? '-',
                            'Alamat Rumah' => $item->pendaftaran->alamat_rumah ?? '-',
                            'Tanggal Pendaftaran' => $item->pendaftaran->tanggal_pendaftaran ?? '-',
                            'Dokumen' => $item->dokumen,
                            'Pembayaran' => $item->pembayaran,
                            'Validasi' => $item->is_validated ? 'Ya' : 'Tidak',
                        ];
                    });

                    // Unduh file Excel
                    return (new FastExcel($data))->download('penerimaan.xlsx');
                })
                ->requiresConfirmation() 
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenerimaans::route('/'),
            'create' => Pages\CreatePenerimaan::route('/create'),
            'edit' => Pages\EditPenerimaan::route('/{record}/edit'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['dokumen']) && is_array($data['dokumen'])) {
            $data['dokumen'] = json_encode($data['dokumen']); // Konversi array menjadi JSON sebelum simpan
        }

        return $data;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Tabs')
            ->tabs([
                Tabs\Tab::make('Pendaftaran')
                    ->schema([
                        TextEntry::make('pendaftaran.nama_peserta_didik')->label('Nama Calon Peserta Didik'),
                        TextEntry::make('pendaftaran.jurusan')->label('Nama Calon Peserta Didik'),
                        TextEntry::make('pendaftaran.jenis_kelamin')->label('Nama Calon Peserta Didik'),
                        TextEntry::make('pendaftaran.nama_ayah')->label('Nama Ayah'),
                        TextEntry::make('pendaftaran.nama_ibu')->label('Nama Ibu'),
                        TextEntry::make('pendaftaran.nomor_telp_peserta')->label('No.Telp Calon Peserta Didik'),
                        TextEntry::make('pendaftaran.nomor_telp_ayah')->label('No.Telp Ayah'),
                        TextEntry::make('pendaftaran.nomor_telp_ibu')->label('No.Telp Ibu'),
                        TextEntry::make('pendaftaran.asal_sekolah')->label('Asal Sekolah'),
                        TextEntry::make('pendaftaran.alamat_rumah')->label('Alamat Rumah'),
                        TextEntry::make('pendaftaran.tanggal_pendaftaran')->label('Tanggal Pendaftaran'),
                    ]),
                Tabs\Tab::make('Kelengkapan')
                    ->schema([
                        TextEntry::make('dokumen')->label('Dokumen'),
                        TextEntry::make('ukuran_baju')->label('Ukuran Baju'),
                        TextEntry::make('pembayaran')->label('Pembayaran'),
                    ]),
                Tabs\Tab::make('Form')
                    ->schema([
                        TextEntry::make('pendaftaran.nomor_form')->label('Nomor Form'),
                        TextEntry::make('nomor_penerimaan')->label('Nomor Penerimaan'),
                        TextEntry::make('pendaftaran.tanggal_pendaftaran')->label('Tanggal Pendaftaran'),
                    ]),
                ])
            ]);
    }

    // public static function getGlobalSearchEloquentQuery(): Builder
    // {
    //     return parent::getGlobalSearchEloquentQuery()->with(['pendaftaran']);
    // }

    // public static function getGloballySearchableAttributes(): array
    // {
    //     return ['pendaftaran.asal_sekolah', 'pendaftaran.alamat_rumah', 'pendaftaran.tanggal_pendaftaran'];
    // }

    // public static function getGlobalSearchResultDetails(Model $record): array
    // {
    //     /** @var Penerimaan $record */
    //     $details = [];

    //     if ($record->pendaftaran) {
    //         $details['Author'] = $record->author->name;
    //     }
    //     return $details;
    // }

}
