<?php

namespace App\Filament\Widgets;

use EightyNine\FilamentAdvancedWidget\AdvancedChartWidget;
use App\Models\Pendaftaran;
use Illuminate\Support\Carbon;

class PendaftaranChart extends AdvancedChartWidget
{
    protected static ?string $heading = 'Pendaftaran Harian';
    protected static string $color = 'info';
    protected static ?string $icon = 'heroicon-o-chart-bar';
    protected static ?string $iconColor = 'info';
    protected static ?string $iconBackgroundColor = 'info';
    protected static ?string $label = 'Jumlah Pendaftaran Harian';

    protected static ?string $badge = 'update';
    protected static ?string $badgeColor = 'primary';
    // protected static ?string $badgeIcon = 'heroicon-o-refresh';
    protected static ?string $badgeIconPosition = 'after';
    protected static ?string $badgeSize = 'xs';

    public ?string $filter = 'today';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari ini',
            'week' => 'Minggu ini',
            'month' => 'Bulan ini',
            'year' => 'Tahun ini',
        ];
    }

    protected function getData(): array
    {
        $query = Pendaftaran::query();
        $labels = [];
        $data = [];

        switch ($this->filter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                $labels = [Carbon::today()->format('d M')];
                $data = [$query->count()];
                break;

            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $labels = collect(range(0, 6))
                    ->map(fn($i) => Carbon::now()->startOfWeek()->addDays($i)->format('d M'))
                    ->toArray();
                $data = collect(range(0, 6))
                    ->map(fn($i) => Pendaftaran::whereDate('created_at', Carbon::now()->startOfWeek()->addDays($i))->count())
                    ->toArray();
                break;

            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month);
                $daysInMonth = Carbon::now()->daysInMonth;
                $labels = collect(range(1, $daysInMonth))
                    ->map(fn($day) => $day)
                    ->toArray();
                $data = collect(range(1, $daysInMonth))
                    ->map(fn($day) => Pendaftaran::whereDay('created_at', $day)->whereMonth('created_at', Carbon::now()->month)->count())
                    ->toArray();
                break;

            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                $labels = collect(range(1, 12))
                    ->map(fn($month) => Carbon::createFromDate(null, $month, 1)->format('M'))
                    ->toArray();
                $data = collect(range(1, 12))
                    ->map(fn($month) => Pendaftaran::whereMonth('created_at', $month)->count())
                    ->toArray();
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pendaftaran',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Anda juga bisa mengganti ke 'bar', 'doughnut', dll.
    }
}
