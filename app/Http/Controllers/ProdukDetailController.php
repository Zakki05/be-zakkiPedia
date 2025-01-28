<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProdukDetailResource;
use App\Http\Resources\PagingResource;
use App\Models\ProdukDetail;
use Illuminate\Http\Request;

class ProdukDetailController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $produk_id = $request->produk_id;

        $produkDetailsQuery = ProdukDetail::with('produk:id,kode_produk');

        if ($produk_id) {
            $produkDetailsQuery->where('produk_id', $produk_id);
        }

        if ($q) {
            $produkDetailsQuery->where('produk_id', 'LIKE', '%' . $q . '%');
        }

        if (!$request->page) {
            $produkDetails = $produkDetailsQuery->get();
            $data['records'] = ProdukDetailResource::collection($produkDetails);
            return $this->success($data, 'Data Berhasil Diambil');
        }

        $produkDetails = $produkDetailsQuery->paginate(5);
        $data['records'] = ProdukDetailResource::collection($produkDetails);
        $data['paging'] = new PagingResource($produkDetails);

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
        $produkdetail = new ProdukDetail;
        $produkdetail->produk_id = $request->produk_id;
        $produkdetail->ukuran = $request->ukuran;
        $produkdetail->stok = $request->stok;
        $produkdetail->harga = $request->harga;
        $produkdetail->save();
        $data['record'] = new ProdukDetailResource($produkdetail);
        return $this->success($data, 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produkdetail = ProdukDetail::findOrFail($id);
        $data['record'] = new ProdukDetailResource($produkdetail);
        return $this->success($data, 'Data Berhasil Ditampilkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProdukDetail $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produkdetail = ProdukDetail::findOrFail($id);
        $produkdetail->produk_id = $request->produk_id;
        $produkdetail->ukuran = $request->ukuran;
        $produkdetail->stok = $request->stok;
        $produkdetail->harga = $request->harga;
        $produkdetail->save();
        $data['record'] = new ProdukDetailResource($produkdetail);
        return $this->success($data, 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ProdukDetail::findOrFail($id)->delete();
        return $this->success(null, 'Data Berhasil Dihapus');
    }

    public function dataProduk(Request $request)
    {
        $q = $request->q;
        $produk_id = $request->produk_id;

        $produkDetailsQuery = ProdukDetail::with('produk');

        if ($produk_id) {
            $produkDetailsQuery->where('produk_id', $produk_id);
        }

        if ($q) {
            $produkDetailsQuery->where('produk_id', 'LIKE', '%' . $q . '%');
        }

        if (!$request->page) {
            $produkDetails = $produkDetailsQuery->get();
            $data['records'] = ProdukDetailResource::collection($produkDetails);
            return $this->success($data, 'Data Berhasil Diambil');
        }

        $produkDetails = $produkDetailsQuery->paginate(5);
        $data['records'] = ProdukDetailResource::collection($produkDetails);
        $data['paging'] = new PagingResource($produkDetails);

        return $this->success($data, 'Data Berhasil Diambil');
    }
}
