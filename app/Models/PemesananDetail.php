<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemesananDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'pemesanan_id',
        'produk_detail_id',
        'harga_produk',
        'jumlah',
        'total_harga'
    ];

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id', 'id');
    }

    public function produkDetail(): BelongsTo
    {
        return $this->belongsTo(ProdukDetail::class, 'produk_detail_id', 'id');
    }

}
