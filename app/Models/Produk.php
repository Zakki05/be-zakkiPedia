<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'kategori_id',
        'kode_produk',
        'nama_produk',
        'gambar',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kode_produk = self::generateProdukNumber();
        });
    }

    public static function generateProdukNumber()
    {
        $latestProduk = self::orderBy('id', 'desc')->first();
        $nextId = $latestProduk ? $latestProduk->id + 1 : 1;
        return 'PRDK' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
}
