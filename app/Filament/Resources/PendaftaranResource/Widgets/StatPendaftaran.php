<?php

namespace App\Filament\Resources\PendaftaranResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pendaftaran;

class StatPendaftaran extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $jurusanLimits = [
            'PPLG' => 120,
            'TJKT' => 120,
            'DKV' => 120,
            'BCP' => 36,
        ];

        $stats = [];

        foreach ($jurusanLimits as $jrs => $limit) {
            $count = Pendaftaran::where('jurusan', $jrs)->count();
            $stats[] = Stat::make($jrs, "$count / $limit")
                ->description("Limit: $limit")
                ->color($count > $limit ? 'danger' : 'success');
        }

        return $stats;
    }

    public static function getWidgets(): array
    {
        return [
            self::class,
        ];
    }
}