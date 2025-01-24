<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'pelanggan_id',
        'ongkir_id',
        'pembayaran_id',
        'kode_pemesanan',
        'tanggal',
        'kecamatan',
        'kelurahan',
        'kode_pos',
        'status',
        'total_pemesanan',
        'biaya',
        'subtotal',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kode_pemesanan = self::generatePemesananNumber();
        });
    }

    public static function generatePemesananNumber()
    {
        $latestPemesanan = self::orderBy('id', 'desc')->first();
        $nextId = $latestPemesanan ? $latestPemesanan->id + 1 : 1;
        return 'PMSN' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }

    public function ongkir(): BelongsTo
    {
        return $this->belongsTo(Ongkir::class, 'ongkir_id', 'id');
    }

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id', 'id');
    }
}
