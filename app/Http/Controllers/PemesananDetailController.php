<?php

namespace App\Http\Controllers;

use App\Http\Resources\PemesananDetailResource;
use App\Http\Resources\PagingResource;
use App\Models\PemesananDetail;
use Illuminate\Http\Request;

class PemesananDetailController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $pemesanan_id = $request->pemesanan_id;

        $pemesananDetails = PemesananDetail::with('pemesanan:id,kode_pemesanan')
            ->where('pemesanan_id', $pemesanan_id)
            ->when($q, function ($query) use ($q) {
                $query->where('pemesanan_id', 'LIKE', '%' . $q . '%');
            })->paginate(5);

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
        $pemesanandetail->harga_produk = $request->harga_produk;
        $pemesanandetail->jumlah = $request->total_harga;
        $pemesanandetail->save();
        $data['record'] = new PemesananDetailResource($pemesanandetail);
        return $this->success($data, 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pemesanandetail = PemesananDetail:: findOrFail($id);
        $data['record'] = new PemesananDetailResource($pemesanandetail);
        return $this -> success($data,'Data Berhasil Ditampilkan');
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
        PemesananDetail::findOrFail($id)->delete();
        return $this->success(null, 'Data Berhasil Dihapus');
    }
}
