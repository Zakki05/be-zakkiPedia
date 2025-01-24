<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'pelanggan_id',
        'kode_pembayaran',
        'tanggal_pembayaran',
        'metode_pembayaran',
        'pembayaran',
        'status',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kode_pembayaran = self::generatePembayaranNumber();
        });
    }

    public static function generatePembayaranNumber()
    {
        $latestPembayaran = self::orderBy('id', 'desc')->first();
        $nextId = $latestPembayaran ? $latestPembayaran->id + 1 : 1;
        return 'BYR' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }
}
