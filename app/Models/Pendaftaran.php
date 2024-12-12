<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $casts = [
        'is_validated' => 'boolean',
    ];

    protected $fillable = [
        'nomor_form',
        'nama_peserta_didik',
        'jurusan',
        'jenis_kelamin',
        'nama_ayah',
        'nama_ibu',
        'nomor_telp_peserta',
        'nomor_telp_ayah',
        'nomor_telp_ibu',
        'asal_sekolah',
        'alamat_rumah',
        'tanggal_pendaftaran',
        'is_validated',
    ];

    public const KUOTA = [
        'PPLG' => 2,
        'TJKT' => 108,
        'DKV'  => 108,
        'BCP'  => 108,
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $lastNumber = static::max('id') + 1;
            $model->nomor_form = 'PPDB00' . $lastNumber;
        });
    }

    public function penerimaan()
    {
        return $this->hasOne(Penerimaan::class);
    }

}
