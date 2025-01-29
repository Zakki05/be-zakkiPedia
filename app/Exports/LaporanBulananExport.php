<?php

namespace App\Exports;

use App\Models\Pemesanan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanBulananExport implements FromQuery, WithHeadings, WithMapping
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        return Pemesanan::query()
            ->with([
                'pelanggan',
                'pemesananDetails.produkDetail.produk',
                'pemesananDetails.produkDetail',
                'ongkir',
                'pembayaran'
            ])
            ->whereMonth('tanggal', $this->month)
            ->whereYear('tanggal', $this->year);
    }

    public function headings(): array
    {
        return [
            'Kode Pemesanan',
            'Nama Lengkap',
            'Alamat Pengiriman',
            'Tanggal',
            'Nama Produk',
            'Ukuran',
            'Harga Produk',
            'Jumlah',
            'Total Pemesanan',
            'Biaya',
            'Subtotal',
            'Status',
            'Status Pembayaran',
        ];
    }

    public function map($pemesanan): array
    {
        $alamat = $pemesanan->alamat . ', ' . $pemesanan->kelurahan . ', ' . $pemesanan->kecamatan . ', ' . $pemesanan->ongkir->kota . ', '  . $pemesanan->kode_pos;

        $produkNames = [];
        $ukuranList = [];
        $hargaList = [];
        $jumlahList = [];

        foreach ($pemesanan->pemesananDetails as $detail) {
            $produkNames[] = $detail->produkDetail->produk->nama_produk;
            $ukuranList[] = $detail->produkDetail->ukuran;
            $hargaList[] = $detail->harga_produk;
            $jumlahList[] = $detail->jumlah;
        }

        $produkString = implode(', ', $produkNames);
        $ukuranString = implode(', ', $ukuranList);
        $hargaString = implode(', ', $hargaList);
        $jumlahString = implode(', ', $jumlahList);

        return [
            $pemesanan->kode_pemesanan,
            $pemesanan->pelanggan->nama_lengkap,
            $alamat,
            $pemesanan->tanggal,
            $produkString,
            $ukuranString,
            $hargaString,
            $jumlahString,
            $pemesanan->total_pemesanan,
            $pemesanan->biaya,
            $pemesanan->subtotal,
            $pemesanan->status,
            $pemesanan->pembayaran ? $pemesanan->pembayaran->status : 'Belum Bayar',
        ];
    }
}
