<?php

namespace App\Http\Controllers;

use App\Http\Resources\PemesananDetailResource;
use App\Http\Resources\PagingResource;
use App\Models\Pemesanan;
use App\Models\PemesananDetail;
use App\Models\ProdukDetail;
use Illuminate\Http\Request;

class PemesananDetailController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $pemesanan_id = $request->pemesanan_id;

        $pemesananDetailsQuery = PemesananDetail::with(['pemesanan', 'produkDetail']);

        if ($pemesanan_id) {
            $pemesananDetailsQuery->where('pemesanan_id', $pemesanan_id);
        }

        if ($q) {
            $pemesananDetailsQuery->where('pemesanan_id', 'LIKE', '%' . $q . '%');
        }

        $pemesananDetails = $pemesananDetailsQuery->paginate(5);

        $data['records'] = PemesananDetailResource::collection($pemesananDetails);
        $data['paging'] = new PagingResource($pemesananDetails);
        return $this->success($data, 'Data Berhasil Diambil');
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
        $pemesanandetail = new PemesananDetail;
        $pemesanandetail->pemesanan_id = $request->pemesanan_id;
        $pemesanandetail->produk_detail_id = $request->produk_detail_id;

        $produkDetail = ProdukDetail::findOrFail($request->produk_detail_id);
        $pemesanandetail->harga_produk = $produkDetail->harga;
        $pemesanandetail->jumlah = $request->jumlah;
        $pemesanandetail->total_harga = $pemesanandetail->jumlah * $pemesanandetail->harga_produk;

        $produkDetail->decrement('stok', $pemesanandetail->jumlah);
        $pemesanandetail->save();

        $pemesanan = Pemesanan::findOrFail($request->pemesanan_id);
        $pemesanan->total_pemesanan += $pemesanandetail->total_harga;
        $pemesanan->subtotal = $pemesanan->biaya + $pemesanan->total_pemesanan;
        $pemesanan->save();

        $data['record'] = new PemesananDetailResource($pemesanandetail);
        return $this->success($data, 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pemesanandetail = PemesananDetail::findOrFail($id);
        $data['record'] = new PemesananDetailResource($pemesanandetail);
        return $this->success($data, 'Data Berhasil Ditampilkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PemesananDetail $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pemesanandetail = PemesananDetail::findOrFail($id);
        $pemesanandetail->pemesanan_id = $request->pemesanan_id;
        $pemesanandetail->produk_detail_id = $request->produk_detail_id;
        $pemesanandetail->harga_produk = $request->harga_produk;
        $pemesanandetail->jumlah = $request->total_harga;
        $pemesanandetail->save();
        $data['record'] = new PemesananDetailResource($pemesanandetail);
        return $this->success($data, 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemesanandetail = PemesananDetail::findOrFail($id);
        $produkDetail = ProdukDetail::findOrFail($pemesanandetail->produk_detail_id);
        $produkDetail->increment('stok', $pemesanandetail->jumlah);
        $pemesanan = Pemesanan::findOrFail($pemesanandetail->pemesanan_id);
        $pemesanan->total_pemesanan -= $pemesanandetail->total_harga;
        $pemesanan->subtotal = $pemesanan->biaya + $pemesanan->total_pemesanan;
        $pemesanan->save();
        $pemesanandetail->delete();

        return $this->success(null, 'Data Berhasil Dihapus');
    }
}
