<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use App\Models\Pendaftaran;
use Filament\Widgets\TableWidget as BaseWidget;

class PendaftaranList extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pendaftaran::Query()
            )
            ->columns([
                TextColumn::make('nomor_form')->label('Nomor Form')->sortable(),
                TextColumn::make('nama_peserta_didik')->label('Nama Peserta Didik'),
                TextColumn::make('nomor_telp_peserta')->label('No.Telp Peserta'),
                TextColumn::make('asal_sekolah')->label('Asal Sekolah'),
                TextColumn::make('alamat_rumah')->label('Alamat Rumah')->wrap(),
                TextColumn::make('tanggal_pendaftaran')->label('Tanggal Pendaftaran'),
                BooleanColumn::make('is_validated')->label('Status Validasi')
                ->boolean()->alignCenter(),
            ]);
    }
}
