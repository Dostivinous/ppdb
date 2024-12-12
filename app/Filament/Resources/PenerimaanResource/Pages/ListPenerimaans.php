<?php

namespace App\Filament\Resources\PenerimaanResource\Pages;

use App\Filament\Resources\PenerimaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListPenerimaans extends ListRecords
{
    protected static string $resource = PenerimaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make('All')->query(fn ($query) => $query),
            'PPLG' => Tab::make('PPLG')->query(fn ($query) => $query->whereHas('pendaftaran', fn ($q) => $q->where('jurusan', 'PPLG'))),
            'TJKT' => Tab::make('TJKT')->query(fn ($query) => $query->whereHas('pendaftaran', fn ($q) => $q->where('jurusan', 'TJKT'))),
            'DKV' => Tab::make('DKV')->query(fn ($query) => $query->whereHas('pendaftaran', fn ($q) => $q->where('jurusan', 'DKV'))),
            'BCP' => Tab::make('BCP')->query(fn ($query) => $query->whereHas('pendaftaran', fn ($q) => $q->where('jurusan', 'BCP'))),
        ];
    }
}
