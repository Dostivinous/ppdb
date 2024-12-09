<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Filament\Resources\LaporanResource\RelationManagers;
use App\Models\Laporan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use App\Filament\Exports\LaporanExporter;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $sort = '2';

    protected static ?string $navigationGroup = 'Manajemen Data';

    protected static ?string $label = 'Laporan';

    protected static ?string $pluralLabel = 'Laporan';

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
                TextColumn::make('nomor_penerimaan')
                    ->label('Nomor Penerimaan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_peserta_didik')
                    ->label('Nama Peserta Didik')
                    ->searchable(),

                TextColumn::make('nama_ayah')
                    ->label('Nama Ayah'),

                TextColumn::make('nama_ibu')
                    ->label('Nama Ibu'),

                TextColumn::make('nomor_telp_peserta')
                    ->label('Nomor Telepon Peserta'),

                TextColumn::make('nomor_telp_ayah')
                    ->label('Nomor Telepon Ayah'),

                TextColumn::make('nomor_telp_ibu')
                    ->label('Nomor Telepon Ibu'),

                TextColumn::make('asal_sekolah')
                    ->label('Asal Sekolah'),

                TextColumn::make('alamat_rumah')
                    ->label('Alamat Rumah'),

                TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal Pendaftaran')
                    ->date(),

                TextColumn::make('dokumen')
                    ->label('Dokumen')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),

                TextColumn::make('ukuran_baju')
                    ->label('Ukuran Baju'),

                TextColumn::make('pembayaran')
                    ->label('Pembayaran'),

                BooleanColumn::make('is_validated')
                    ->label('Validasi'),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportAction::make()
                        ->exporter(LaporanExporter::class),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLaporans::route('/'),
        ];
    }
}
