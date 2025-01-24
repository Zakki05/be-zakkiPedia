<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProdukResource;
use App\Http\Resources\PagingResource;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $produks = Produk::where(function ($f) use ($q) {
            if ($q){
                $f->where('kode_produk', 'LIKE', '%' . $q . '%');
            }
        })
        ->orderBy('created_at','desc')
        ->paginate(10);

        $data['records'] = ProdukResource::collection($produks);
        $data['paging'] = new PagingResource($produks);
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
        $produk = new Produk();
        $produk->kategori_id = $request->kategori_id;
        $produk->nama_produk = $request->nama_produk;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = 'img-' . time() . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $fileName, 'public');
            $url = url('storage/' . $path);
            $produk->gambar = $url;
        }
        $produk->save();

        $data['record'] = new ProdukResource($produk);
        return $this->success($data, 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk:: findOrFail($id);
        $data['record'] = new ProdukResource($produk);
        return $this -> success($data,'Data Berhasil Ditampilkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->kategori_id = $request->kategori_id;
        $produk->nama_produk = $request->nama_produk;
        $produk->gambar = $request->gambar;
        $produk->save();
        $data['record'] = new ProdukResource($produk);
        return $this->success($data, 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Produk::findOrFail($id)->delete();
        return $this->success(null, 'Data Berhasil Dihapus');
    }
}
