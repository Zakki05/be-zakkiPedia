<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ongkir extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'kode_ongkir',
        'kota',
        'biaya',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kode_ongkir = self::generateOngkirNumber();
        });
    }

    public static function generateOngkirNumber()
    {
        $latestOngkir = self::orderBy('id', 'desc')->first();
        $nextId = $latestOngkir ? $latestOngkir->id + 1 : 1;
        return 'ONGKR' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }
}
