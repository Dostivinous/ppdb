<?php

namespace App\Filament\Exports;

use App\Models\Laporan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LaporanExporter extends Exporter
{
    protected static ?string $model = Laporan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nomor_penerimaan')
                ->label('Nomor Penerimaan'),

            ExportColumn::make('nama_peserta_didik')
                ->label('Nama Calon Peserta Didik'),

            ExportColumn::make('nama_ayah')
                ->label('Nama Ayah'),

            ExportColumn::make('nama_ibu')
                ->label('Nama Ibu'),

            ExportColumn::make('nomor_telp_peserta')
                ->label('No Telepon Anak'),

            ExportColumn::make('nomor_telp_ayah')
                ->label('No Telepon Ayah'),

            ExportColumn::make('nomor_telp_ibu')
                ->label('No Telepon Ibu'),

            ExportColumn::make('asal_sekolah')
                ->label('Asal Sekolah'),

            ExportColumn::make('alamat_rumah')
                ->label('Alamat Rumah'),

            ExportColumn::make('tanggal_pendaftaran')
                ->label('Tanggal Pendaftaran')
                ->formatStateUsing(fn ($state) => $state->format('d-m-Y')),

            ExportColumn::make('dokumen')
                ->label('Kelengkapan Dokumen')
                ->formatStateUsing(fn ($state) => $state ? implode(', ', json_decode($state, true)) : 'Tidak Membawa'),

            ExportColumn::make('ukuran_baju')
                ->label('Ukuran Baju'),

            ExportColumn::make('pembayaran')
                ->label('Status Pembayaran')
                ->formatStateUsing(fn ($state) => ucfirst($state)),

            ExportColumn::make('is_validated')
                ->label('Validasi')
                ->formatStateUsing(fn ($state) => $state ? 'Valid' : 'Tidak Valid'),

            ExportColumn::make('created_at')
                ->label('Dibuat Pada')
                ->formatStateUsing(fn ($state) => $state->format('d-m-Y H:i')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your laporan export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
