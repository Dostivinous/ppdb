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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\ActionGroup;

class PenerimaanResource extends Resource
{
    protected static ?string $model = Penerimaan::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';
    protected static ?string $navigationGroup = 'Form';
    protected static ?string $pluralLabel = 'Penerimaan';

    public static function form(Form $form): Form
{
    return $form->schema([
        Wizard::make([
            
            Wizard\Step::make('Pendaftaran')
                ->schema([
                    Select::make('pendaftaran_id')
                        ->label('Pendaftaran')
                        ->options(
                            Pendaftaran::whereDoesntHave('penerimaan') // Tampilkan hanya data yang belum divalidasi
                                ->pluck('nomor_form', 'id')
                        )
                        ->searchable()
                        ->reactive() // Untuk memuat data terkait secara dinamis
                        ->afterStateUpdated(function (callable $set, $state) {
                            if ($state) {
                                // Muat data dari pendaftaran berdasarkan ID
                                $pendaftaran = Pendaftaran::find($state);

                                if ($pendaftaran) {
                                    $set('nama_peserta_didik', $pendaftaran->nama_peserta_didik);
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
                        ->required(),

                    TextInput::make('nama_ayah')
                        ->label('Nama Ayah')
                        ->disabled()
                        ->required(),

                    TextInput::make('nama_ibu')
                        ->label('Nama Ibu')
                        ->disabled()
                        ->required(),

                    TextInput::make('nomor_telp_peserta')
                        ->label('Nomor Telepon Peserta')
                        ->disabled()
                        ->required(),

                    TextInput::make('nomor_telp_ayah')
                        ->label('Nomor Telepon Ayah')
                        ->disabled()
                        ->required(),

                    TextInput::make('nomor_telp_ibu')
                        ->label('Nomor Telepon Ibu')
                        ->disabled()
                        ->required(),

                    TextInput::make('asal_sekolah')
                        ->label('Asal Sekolah')
                        ->disabled()
                        ->required(),

                    TextInput::make('alamat_rumah')
                        ->label('Alamat Rumah')
                        ->disabled()
                        ->required(),

                    TextInput::make('tanggal_pendaftaran')
                        ->label('Tanggal Pendaftaran')
                        ->disabled()
                        ->required(),
                ]),

            Wizard\Step::make('Dokumen')
                ->schema([
                    CheckboxList::make('dokumen')
                ->label('Pilih Dokumen yang Dibawa')
                ->options([
                    'Tidak Membawa Dokumen' => 'Tidak Membawa Dokumen',
                    'KK' => 'KK',
                    'Akte' => 'Akte',
                    'Ijazah' => 'Ijazah',
                    'Pas Foto' => 'Pas Foto',
                ])
                ->reactive()
                ->afterStateUpdated(function (callable $set, $state) {
                    if (in_array('Tidak Membawa Dokumen', $state ?? [])) {
                        $set('dokumen', 'Tidak Membawa Dokumen');
                    }
                    $set('dokumen', json_encode($state)); // Convert array to JSON
                })
                ->required()
                ->helperText('Centang "Tidak Membawa Dokumen" jika tidak ada dokumen yang dibawa.')
                ->label('')
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
                        ->required(),

                    Select::make('pembayaran')
                        ->label('Pembayaran')
                        ->options([
                            'Lunas' => 'Lunas',
                            'Tidak Lunas' => 'Tidak Lunas',
                        ])
                        ->required(),

                    // TextInput::make('nomor_penerimaan')
                    //     ->label('Nomor Penerimaan')
                    //     ->required()
                    //     ->unique(),

                    Toggle::make('is_validated')
                        ->label('Validasi'),
                ]),
        ]),
    ]);
}





    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pendaftaran.nomor_form')->label('Nomor Form')->sortable(),
                TextColumn::make('nomor_penerimaan')->label('Nomor Penerimaan'),
                TextColumn::make('dokumen')->label('Dokumen'),
                TextColumn::make('pembayaran')->label('Pembayaran'),
                BooleanColumn::make('is_validated')->label('Tervalidasi'),
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
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
}
