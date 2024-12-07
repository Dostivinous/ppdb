<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function create()
    {
        // Mendapatkan nomor form terbaru (menggunakan logika auto increment)
        $newNumber = Pendaftaran::max('id') + 1;
        return view('pendaftaran.form', compact('newNumber'));
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima dari form
        $request->validate([
            'nama_peserta_didik' => 'required|string|max:255',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'nomor_telp_peserta' => 'required|numeric',
            'nomor_telp_ayah' => 'required|numeric',
            'nomor_telp_ibu' => 'required|numeric',
            'asal_sekolah' => 'required|string|max:255',
            'alamat_rumah' => 'required|string',
        ]);

        // Menyimpan data ke database
        Pendaftaran::create([
            'nomor_form' => 'PPDB202400' . ($request->nomor_form ?? 1), // Pastikan nomor form sudah ada
            'nama_peserta_didik' => $request->nama_peserta_didik,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'nomor_telp_peserta' => $request->nomor_telp_peserta,
            'nomor_telp_ayah' => $request->nomor_telp_ayah,
            'nomor_telp_ibu' => $request->nomor_telp_ibu,
            'asal_sekolah' => $request->asal_sekolah,
            'alamat_rumah' => $request->alamat_rumah,
            'tanggal_pendaftaran' => now(),
            'is_validated' => false, // Status validasi defaultnya false
        ]);

        // Kembali ke form dengan pesan sukses
        return redirect()->route('pendaftaran.form')->with('success', 'Pendaftaran berhasil!');
    }
}
