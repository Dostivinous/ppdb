<?php

namespace App\Observers;

use App\Models\Penerimaan;
use App\Models\Laporan;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Log;

class LaporanObserver
{
    // /**
    //  * Handle the Penerimaan "created" event.
    //  */
    public function created(Penerimaan $penerimaan): void
    {
        $this->createOrUpdateLaporan($penerimaan);
    }

    // /**
    //  * Handle the Penerimaan "updated" event.
    //  */
    public function updated(Penerimaan $penerimaan): void
    {
        $this->createOrUpdateLaporan($penerimaan);
    }

    /**
     * Handle the Penerimaan "deleted" event.
     */
    public function deleted(Penerimaan $penerimaan): void
    {
        //
    }

    /**
     * Handle the Penerimaan "restored" event.
     */
    public function restored(Penerimaan $penerimaan): void
    {
        //
    }

    /**
     * Handle the Penerimaan "force deleted" event.
     */
    public function forceDeleted(Penerimaan $penerimaan): void
    {
        //
    }

    private function createOrUpdateLaporan(Penerimaan $penerimaan)
    {
        // Ambil data pendaftaran berdasarkan nomor_form
        $pendaftaran = Pendaftaran::where('nomor_form', $penerimaan->nomor_penerimaan)->first();

        if ($pendaftaran) {
            // Cek apakah laporan sudah ada
            $laporan = Laporan::where('no_penerimaan', $penerimaan->no_penerimaan)->first();

            if ($laporan) {
                // Jika laporan sudah ada, update data laporan
                $laporan->update([
                    'nama' => $pendaftaran->nama,
                    'nama_ortu' => $pendaftaran->nama_ortu,
                    'telp_anak' => $pendaftaran->telp_anak,
                    'telp_ortu' => $pendaftaran->telp_ortu,
                    'asal_sekolah' => $pendaftaran->asal_sekolah,
                    'alamat' => $pendaftaran->alamat,
                    'tgl_pendaftaran' => $pendaftaran->tgl_pendaftaran,
                    'kelengkapan_doc' => json_encode($penerimaan->kelengkapan_doc), // Menyimpan data dokumen dalam format JSON
                    'ukuran_baju' => $penerimaan->ukuran_baju,
                    'status_pembayaran' => $penerimaan->status_pembayaran,
                ]);
            } else {
                // Jika laporan belum ada, buat laporan baru
                Laporan::create([
                    'no_penerimaan' => $penerimaan->no_penerimaan,
                    'nama' => $pendaftaran->nama,
                    'nama_ortu' => $pendaftaran->nama_ortu,
                    'telp_anak' => $pendaftaran->telp_anak,
                    'telp_ortu' => $pendaftaran->telp_ortu,
                    'asal_sekolah' => $pendaftaran->asal_sekolah,
                    'alamat' => $pendaftaran->alamat,
                    'tgl_pendaftaran' => $pendaftaran->tgl_pendaftaran,
                    'kelengkapan_doc' => json_encode($penerimaan->kelengkapan_doc),
                    'ukuran_baju' => $penerimaan->ukuran_baju,
                    'status_pembayaran' => $penerimaan->status_pembayaran,
                ]);
            }
        }
    }
}
