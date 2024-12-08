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
        return Pendaftaran::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Peserta Didik')
                        ->schema([
                            TextInput::make('nama_peserta_didik')
                            ->required()->label('Nama Calon Peserta Didik')->placeholder('Nama Calon Peserta Didik'),

                            TextInput::make('nomor_telp_peserta')
                            ->required()->label('No.Telp Peserta')->placeholder('No.Telp Peserta'),
                        ]),
                        
                    Wizard\Step::make('Data Orang Tua')
                        ->schema([
                            TextInput::make('nama_ayah')
                            ->required()->label('Nama Ayah')->placeholder('Nama Ayah'),

                            TextInput::make('nama_ibu')
                            ->required()->label('Nama Ibu')->placeholder('Nama Ibu'),

                             TextInput::make('nomor_telp_ayah')
                            ->required()->label('No.Telp Ayah')->placeholder('No.Telp Ayah'),

                            TextInput::make('nomor_telp_ibu')
                            ->required()->label('No.Telp Ibu')->placeholder('No.Telp Ibu'),
                        ]),

                    Wizard\Step::make('Kelengkapan')
                        ->schema([
                            TextInput::make('asal_sekolah')
                            ->required()->label('Asal Sekolah')->placeholder('Asal Sekolah'),
                            
                            TextInput::make('alamat_rumah')
                            ->required()->label('Alamat Rumah')->placeholder('Alamat Rumah'),

                            DatePicker::make('tanggal_pendaftaran')
                            ->required()->label('Tanggal Pendaftaran')->placeholder('Tanggal Pendaftaran')->default(now()),
                        ]),
                ])
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_form')->label('Nomor Form')->sortable(),
                TextColumn::make('nama_peserta_didik')->label('Nama Peserta Didik'),
                TextColumn::make('nomor_telp_peserta')->label('No.Telp Peserta'),
                TextColumn::make('asal_sekolah')->label('Asal Sekolah'),
                TextColumn::make('alamat_rumah')->label('Alamat Rumah')->wrap(),
                TextColumn::make('tanggal_pendaftaran')->label('Tanggal Pendaftaran'),
                BooleanColumn::make('is_validated')->label('Status Validasi')
                ->boolean()->alignCenter(),
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

    // public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    // {
    //     $query = parent::getEloquentQuery()->where('is_validated', false);
    //     \Log::info($query->toSql(), $query->getBindings()); // Log untuk memeriksa query
    //     return $query;
    // }

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
