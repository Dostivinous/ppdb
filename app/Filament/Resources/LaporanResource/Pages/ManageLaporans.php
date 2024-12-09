<?php

namespace App\Filament\Resources\LaporanResource\Pages;

use App\Filament\Exports\LaporanExporter;
use App\Filament\Resources\LaporanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLaporans extends ManageRecords
{
    protected static string $resource = LaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Export Laporan')
                ->exporter(LaporanExporter::class),


        ];
    }
}
