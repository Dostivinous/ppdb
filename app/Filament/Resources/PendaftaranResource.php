<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Filament\Resources\PendaftaranResource\RelationManagers;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Wizard;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Form PPDB';

    protected static ?string $pluralLabel = 'Pendaftaran';
    protected static ?string $recordTitleAttribute = 'nama_peserta_didik';


    public static function getNavigationBadge(): ?String
    {
        $query = Pendaftaran::query();

        if ($activeFilter = request()->query('active', null)) {
            $query->where('is_active', $activeFilter);
        }

        return $query->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Peserta Didik')
                        ->schema([
                            TextInput::make('nama_peserta_didik')
                            ->required()->label('Nama Calon Peserta Didik')->placeholder('Nama Calon Peserta Didik')->maxLength(40),

                            Select::make('jurusan')
                            ->required()->label('Pilih Jurusan')
                            ->options([
                                'PPLG' => "Pengembangan Perangkat Lunak dan Gim",
                                'TJKT' => "Teknik Jaringan Komputer dan Telekomunikasi ",
                                'DKV' => "Desain Komunikasi Visual",
                                'BCP' => "Broadcasting dan Perfilman",
                            ])
                            ->validationAttribute('jurusan')
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if ($errors = session('errors')) {
                                    $component->setError($errors->get('jurusan'));
                                }
                            }),

                            Radio::make('jenis_kelamin')
                            ->required()->label('Jenis Kelamin')
                            ->options([
                                'Laki - Laki' => 'Laki - Laki',
                                'Perempuan' => 'Perempuan',
                            ]),

                            TextInput::make('nomor_telp_peserta')
                            ->required()->label('No.Telp Peserta')->placeholder('No.Telp Peserta')->numeric()->prefix('+62')->minValue(10),
                        ]),

                    Wizard\Step::make('Data Orang Tua')
                        ->schema([
                            TextInput::make('nama_ayah')
                            ->required()->label('Nama Ayah')->placeholder('Nama Ayah')->maxLength(30),

                            TextInput::make('nama_ibu')
                            ->required()->label('Nama Ibu')->placeholder('Nama Ibu')->maxLength(30),

                             TextInput::make('nomor_telp_ayah')
                            ->required()->label('No.Telp Ayah')->placeholder('No.Telp Ayah')->numeric()->prefix('+62')->minValue(10),

                            TextInput::make('nomor_telp_ibu')
                            ->required()->label('No.Telp Ibu')->placeholder('No.Telp Ibu')->numeric()->prefix('+62')->minValue(10),
                        ]),

                    Wizard\Step::make('Kelengkapan')
                        ->schema([
                            TextInput::make('asal_sekolah')
                            ->required()->label('Asal Sekolah')->placeholder('Asal Sekolah')->maxLength(20),

                            TextInput::make('alamat_rumah')
                            ->required()->label('Alamat Rumah')->placeholder('Alamat Rumah')->maxLength(255),

                            DatePicker::make('tanggal_pendaftaran')
                            ->required()->label('Tanggal Pendaftaran')->placeholder('Tanggal Pendaftaran')->default(now())->maxDate(now()),
                        ]),
                ])->columnSpanFull()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_form')->label('Nomor Form')->sortable(),
                TextColumn::make('nama_peserta_didik')->label('Nama Peserta Didik'),
                TextColumn::make('jurusan')->label('Jurusan'),
                TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
                TextColumn::make('nomor_telp_peserta')->label('No.Telp Peserta')->toggleable(isToggledHiddenByDefault: true)->sortable(),
                TextColumn::make('asal_sekolah')->label('Asal Sekolah'),
                TextColumn::make('alamat_rumah')->label('Alamat Rumah')->wrap()->toggleable(isToggledHiddenByDefault: true)->sortable(),
                TextColumn::make('tanggal_pendaftaran')->label('Tanggal Pendaftaran'),
                BooleanColumn::make('is_validated')->label('Status Validasi')
                ->boolean(),
            ])
            ->defaultSort('nomor_form', 'asc')
            ->filters([
                Filter::make('Belum Divalidasi')
                    ->query(fn ($query) => $query->withoutGlobalScopes()->where('is_validated', false))
                    ->label('Belum Divalidasi'),
                Filter::make('Divalidasi')
                    ->query(fn ($query) => $query->withoutGlobalScopes()->where('is_validated', true))
                    ->label('Divalidasi'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('is_validated', false);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Tabs')
            ->tabs([
                Tabs\Tab::make('Data Siswa')
                    ->schema([
                        TextEntry::make('nama_peserta_didik')->label('Nama Peserta Didik'),
                        TextEntry::make('nomor_telp_peserta')->label('No.Telp Peserta'),
                        TextEntry::make('asal_sekolah')->label('Asal Sekolah'),
                        TextEntry::make('alamat_rumah')->label('Alamat Rumah'),
                    ]),
                Tabs\Tab::make('Data Orang Tua')
                    ->schema([
                        TextEntry::make('nama_ayah')->label('Nama Ayah'),
                        TextEntry::make('nama_ibu')->label('Nama Ibu'),
                        TextEntry::make('nomor_telp_ayah')->label('No.Telp Ayah'),
                        TextEntry::make('nomor_telp_ibu')->label('No. Telp Ibu'),
                    ]),
                Tabs\Tab::make('Form')
                    ->schema([
                        TextEntry::make('nomor_form')->label('Nomor Form'),
                        TextEntry::make('tanggal_pendaftaran')->label('Tanggal Pendaftaran'),
                    ]),
                ])
            ]);
    }

}
