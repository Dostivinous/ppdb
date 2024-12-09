<?php

namespace App\Filament\Resources\PendaftaranResource\Pages;

use App\Filament\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Pendaftaran;
use Illuminate\Validation\ValidationException;

class CreatePendaftaran extends CreateRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $jumlahPendaftar = Pendaftaran::where('jurusan', $data['jurusan'])->count();
        $kuotaJurusan = Pendaftaran::KUOTA[$data['jurusan']] ?? 0;

        if ($jumlahPendaftar >= $kuotaJurusan) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'jurusan' => 'Maaf, jurusan ini sudah penuh.', // Key harus sesuai dengan field di form
            ]);
        }

        return $data;
    }

}
