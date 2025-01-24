<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'kode_kategori',
        'nama_kategori',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kode_kategori = self::generateKategoriNumber();
        });
    }

    public static function generateKategoriNumber()
    {
        $latestKategori = self::orderBy('id', 'desc')->first();
        $nextId = $latestKategori ? $latestKategori->id + 1 : 1;
        return 'KTGR' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }
}
