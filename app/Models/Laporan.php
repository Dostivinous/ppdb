<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_penerimaan',
        'nama_peserta_didik',
        'nama_ayah',
        'nama_ibu',
        'nomor_telp_peserta',
        'nomor_telp_ayah',
        'nomor_telp_ibu',
        'asal_sekolah',
        'alamat_rumah',
        'tanggal_pendaftaran',
        'dokumen',
        'ukuran_baju',
        'pembayaran',
        'is_validated',
    ];

    protected $casts = [
        'dokumen' => 'array', // Otomatis mengonversi ke array saat diakses
    ];

    public function setDokumenAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['dokumen'] = json_encode($value); // Pastikan JSON dihasilkan hanya sekali
        } else {
            $this->attributes['dokumen'] = $value;
        }
    }
}
