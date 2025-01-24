<?php

namespace App\Http\Controllers;

use App\Http\Resources\PemesananResource;
use App\Http\Resources\PagingResource;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $pemesanans = Pemesanan::where(function ($f) use ($q) {
            if ($q){
                $f->where('kode_pemesanan', 'LIKE', '%' . $q . '%');
            }
        })
        ->orderBy('created_at','desc')
        ->paginate(10);

        $data['records'] = PemesananResource::collection($pemesanans);
        $data['paging'] = new PagingResource($pemesanans);
        return $this->success($data, 'Data berhasil di ambil');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pemesanan = new Pemesanan;
        $pemesanan->pelanggan_id = $request->pelanggan_id;
        $pemesanan->ongkir_id = $request->ongkir_id;
        $pemesanan->pembayaran_id = $request->pembayaran_id;
        $pemesanan->tanggal = $request->tanggal;
        $pemesanan->kecamatan = $request->kecamatan;
        $pemesanan->kelurahan = $request->kelurahan;
        $pemesanan->alamat = $request->alamat;
        $pemesanan->kode_pos = $request->kode_pos;
        $pemesanan->status = $request->status;
        $pemesanan->total_pemesanan = $request->total_pemesanan;
        $pemesanan->biaya = $request->biaya;
        $pemesanan->subtotal = $request->subtotal;
        $pemesanan->save();
        $data['record'] = new PemesananResource($pemesanan);
        return $this->success($data, 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pemesanan = Pemesanan:: findOrFail($id);
        $data['record'] = new PemesananResource($pemesanan);
        return $this -> success($data,'Data Berhasil Ditampilkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pemesanan $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->pelanggan_id = $request->pelanggan_id;
        $pemesanan->ongkir_id = $request->ongkir_id;
        $pemesanan->pembayaran_id = $request->pembayaran_id;
        $pemesanan->tanggal = $request->tanggal;
        $pemesanan->kecamatan = $request->kecamatan;
        $pemesanan->kelurahan = $request->kelurahan;
        $pemesanan->alamat = $request->alamat;
        $pemesanan->kode_pos = $request->kode_pos;
        $pemesanan->status = $request->status;
        $pemesanan->total_pemesanan = $request->total_pemesanan;
        $pemesanan->biaya = $request->biaya;
        $pemesanan->subtotal = $request->subtotal;
        $pemesanan->save();
        $data['record'] = new PemesananResource($pemesanan);
        return $this->success($data, 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pemesanan::findOrFail($id)->delete();
        return $this->success(null, 'Data Berhasil Dihapus');
    }
}
