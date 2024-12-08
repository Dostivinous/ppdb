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
            null => Tab::make('All'),
            'Lunas' => Tab::make()->query(fn ($query) => $query->where('pembayaran', 'Lunas')),
            'Cicil' => Tab::make()->query(fn ($query) => $query->where('pembayaran', 'Cicil')),
        ];
    }
}
