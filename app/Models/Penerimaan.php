<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Penerimaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'dokumen',
        'ukuran_baju',
        'pembayaran',
        'nomor_penerimaan',
        'is_validated'
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

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            if ($model->is_validated) {
                $pendaftaran = $model->pendaftaran;
                $pendaftaran->update(['is_validated' => true]);
            }
        });

        static::creating(function ($model) {
            // Mengambil nomor terakhir
            $lastNumber = static::max('id') + 1; // Menambahkan 1 untuk auto increment
            $model->nomor_penerimaan = 'NP00' . $lastNumber;
        });
    }


    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
