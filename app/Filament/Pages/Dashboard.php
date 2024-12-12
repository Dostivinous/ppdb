<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard\Concerns\HasFilters;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    Use HasFiltersForm;

    protected static ?string $title = 'Home';

    public $is_validated; // Variabel untuk menampung nilai filter
    public $startDate;   // Variabel untuk menampung nilai tanggal mulai
    public $endDate;

    // public function filtersForm(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Section::make('Filter Pencarian')
    //                 ->schema([
    //                     Select::make('is_validate')
    //                         ->label('Status Validasi')
    //                         ->options([
    //                             true => 'Tervalidasi',
    //                             false => 'Belum Tervalidasi',
    //                         ])
    //                         ->placeholder('Pilih status validasi')
    //                         ->nullable()
    //                         ->reactive(), // Menambahkan reaktivitas untuk mengupdate filter

    //                     DatePicker::make('startDate')
    //                         ->label('Tanggal Mulai')
    //                         ->maxDate(fn (Get $get) => $get('endDate') ?: now())
    //                         ->nullable()
    //                         ->reactive(), // Menambahkan reaktivitas untuk mengupdate filter

    //                     DatePicker::make('endDate')
    //                         ->label('Tanggal Akhir')
    //                         ->minDate(fn (Get $get) => $get('startDate') ?: now())
    //                         ->maxDate(now())
    //                         ->nullable()
    //                         ->reactive(), // Menambahkan reaktivitas untuk mengupdate filter
    //                 ])
    //                 ->columns(3),
    //         ]);
    // }

    // public function getTableQuery()
    // {
    //     return Laporan::query()
    //         // Filter berdasarkan validasi
    //         ->when($this->is_validate !== null, function ($query) {
    //             return $query->where('is_validated', $this->is_validate);
    //         })
    //         // Filter berdasarkan rentang tanggal
    //         ->when($this->startDate, function ($query) {
    //             return $query->where('tanggal_pendaftaran', '>=', $this->startDate);
    //         })
    //         ->when($this->endDate, function ($query) {
    //             return $query->where('tanggal_pendaftaran', '<=', $this->endDate);
    //         });
    // }

    // public function mount()
    // {
    //     // Inisialisasi filter atau nilai default
    //     $this->is_validated = null;
    //     $this->startDate = null;
    //     $this->endDate = null;
    // }
}
