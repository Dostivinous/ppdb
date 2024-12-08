<?php

namespace App\Filament\Widgets;

use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use App\Models\Pendaftaran; // Pastikan model sesuai dengan struktur aplikasi Anda
use App\Models\Penerimaan;

class AdvancedStatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        // Hitung jumlah data pendaftaran
        $totalPendaftaran = Pendaftaran::count();

        // Hitung jumlah pembayaran lunas
        $pembayaranLunas = Penerimaan::where('pembayaran', 'Lunas')->count();

        // Hitung jumlah pembayaran cicil
        $pembayaranCicil = Penerimaan::where('pembayaran', 'Cicil')->count();

        // Hitung data yang sudah divalidasi
        $dataTervalidasi = Pendaftaran::where('is_validated', true)->count();

        $Pembayaran = Penerimaan::sum('pembayaran');
        $maxValue = 100;
        $progressPembayaran = min(($Pembayaran / $maxValue) * 10, 10);

        $Validasi = Penerimaan::sum('is_validated');
        $maxValue = 100;
        $progressValidasi = min(($Validasi / $maxValue) * 10, 10);

        return [
            Stat::make('Banyaknya Data Pendaftaran', $totalPendaftaran)
                ->icon('heroicon-o-user')
                ->progress(Pendaftaran::count())// Misalnya progress statis, bisa disesuaikan
                ->progressBarColor('primary')
                ->iconBackgroundColor('primary')
                ->chartColor('primary')
                ->iconPosition('start')
                ->description('Total data pendaftaran')
                ->descriptionIcon('heroicon-o-chevron-up', 'before')
                ->descriptionColor('primary')
                ->iconColor('primary'),

            Stat::make('Pembayaran Lunas', $pembayaranLunas)
                ->icon('heroicon-o-currency-dollar')
                ->progress($progressPembayaran)
                ->progressBarColor('success')
                ->iconBackgroundColor('success')
                ->chartColor('success')
                ->iconPosition('start')
                ->description('Jumlah pembayaran lunas')
                ->descriptionIcon('heroicon-o-chevron-up', 'before')
                ->descriptionColor('success')
                ->iconColor('success'),

            Stat::make('Pembayaran Cicil', $pembayaranCicil)
                ->icon('heroicon-o-credit-card')
                ->progress($progressPembayaran)
                ->progressBarColor('warning')
                ->iconBackgroundColor('warning')
                ->chartColor('warning')
                ->iconPosition('start')
                ->description('Jumlah pembayaran cicilan')
                ->descriptionIcon('heroicon-o-chevron-up', 'before')
                ->descriptionColor('warning')
                ->iconColor('warning'),

            Stat::make('Data yang Sudah Divalidasi', $dataTervalidasi)
                ->icon('heroicon-o-check-circle')
                ->progress($progressValidasi)
                ->progressBarColor('success')
                ->iconBackgroundColor('success')
                ->chartColor('success')
                ->iconPosition('start')
                ->description('Data tervalidasi')
                ->descriptionIcon('heroicon-o-chevron-up', 'before')
                ->descriptionColor('success')
                ->iconColor('success'),
        ];
    }
}
