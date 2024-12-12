<?php

namespace App\Filament\Widgets;

use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use App\Models\Pendaftaran;
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

        // Perhitungan progress pembayaran dan validasi
        $Pembayaran = Penerimaan::sum('pembayaran');
        $maxValue = 100;
        $progressPembayaran = min(($Pembayaran / $maxValue) * 10, 10);

        $Validasi = Penerimaan::sum('is_validated');
        $progressValidasi = min(($Validasi / $maxValue) * 10, 10);

        // Hitung jumlah siswa laki-laki dan perempuan
        $jumlahLakiLaki = Pendaftaran::where('jenis_kelamin', 'Laki - laki')->count();
        $jumlahPerempuan = Pendaftaran::where('jenis_kelamin', 'Perempuan')->count();

        // Data pendaftaran per tanggal
        $data = Pendaftaran::selectRaw('DATE(tanggal_pendaftaran) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->count];
            });

        $labels = $data->keys()->toArray();
        $values = $data->values()->toArray();

        // Hitung jumlah siswa per jurusan
        $jurusanStats = [
            'PPLG' => ['count' => Pendaftaran::where('jurusan', 'PPLG')->count(), 'max' => 120],
            'TJKT' => ['count' => Pendaftaran::where('jurusan', 'TJKT')->count(), 'max' => 120],
            'DKV'  => ['count' => Pendaftaran::where('jurusan', 'DKV')->count(), 'max' => 120],
            'BCP'  => ['count' => Pendaftaran::where('jurusan', 'BCP')->count(), 'max' => 36],
        ];

        $jurusanStatsWidgets = [];
        foreach ($jurusanStats as $jurusan => $stats) {
            $progress = min(($stats['count'] / $stats['max']) * 100, 100);
            $jurusanStatsWidgets[] = Stat::make("Jurusan $jurusan", "{$stats['count']} / {$stats['max']}")
                ->icon('heroicon-o-academic-cap')
                ->progress($progress)
                ->progressBarColor('primary')
                ->iconBackgroundColor('primary')
                ->chartColor('primary')
                ->iconPosition('start')
                ->description("Jumlah siswa di jurusan $jurusan")
                ->descriptionIcon('heroicon-o-chevron-up', 'before')
                ->descriptionColor('info')
                ->iconColor('info');
        }

        return array_merge([
            Stat::make('Banyaknya Data Pendaftaran', $totalPendaftaran)
                ->icon('heroicon-o-user')
                ->progress(Pendaftaran::count()) // Misalnya progress statis, bisa disesuaikan
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
                ->icon('heroicon-o-user')
                ->progress($progressValidasi)
                ->progressBarColor('success')
                ->iconBackgroundColor('success')
                ->chartColor('success')
                ->iconPosition('start')
                ->description('Data tervalidasi')
                ->descriptionIcon('heroicon-o-chevron-up', 'before')
                ->descriptionColor('success')
                ->iconColor('success'),

            // Tambahkan Stat untuk jenis kelamin Laki-laki dan Perempuan
            Stat::make('Laki-laki', $jumlahLakiLaki)
                ->icon('heroicon-o-user')
                ->progress($jumlahLakiLaki)
                ->progressBarColor('primary')
                ->iconBackgroundColor('primary')
                ->chartColor('primary')
                ->iconPosition('start')
                ->description('Jumlah siswa laki-laki')
                ->descriptionIcon('heroicon-o-chevron-up', 'before')
                ->descriptionColor('primary')
                ->iconColor('primary'),

            Stat::make('Perempuan', $jumlahPerempuan)
                ->icon('heroicon-o-check-circle')
                ->progress($jumlahPerempuan)
                ->progressBarColor('warning')
                ->iconBackgroundColor('warning')
                ->chartColor('warning')
                ->iconPosition('start')
                ->description('Jumlah siswa perempuan')
                ->descriptionIcon('heroicon-o-chevron-up', 'before')
                ->descriptionColor('warning')
                ->iconColor('warning'),

        ], $jurusanStatsWidgets);
    }
}
