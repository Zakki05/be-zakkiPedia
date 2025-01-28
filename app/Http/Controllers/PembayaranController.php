<?php

namespace App\Http\Controllers;

use App\Http\Resources\PembayaranResource;
use App\Http\Resources\PagingResource;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $pembayarans = Pembayaran::where(function ($f) use ($q) {
            if ($q){
                $f->where('kode_pembayaran', 'LIKE', '%' . $q . '%');
            }
        })
        ->orderBy('created_at','desc')
        ->paginate(10);

        $data['records'] = PembayaranResource::collection($pembayarans);
        $data['paging'] = new PagingResource($pembayarans);
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
        $pemesanan = Pemesanan::findOrFail($request->pemesanan_id);

        $pembayaran = new Pembayaran;
        $pembayaran->pelanggan_id = $pemesanan->pelanggan_id;
        $pembayaran->tanggal_pembayaran = now();
        $pembayaran->metode_pembayaran = $request->metode_pembayaran;
        $pembayaran->pembayaran = $pemesanan->total_pemesanan;
        $pembayaran->status = $request->status;
        $pembayaran->save();

        $pemesanan->pembayaran_id = $pembayaran->id;
        $pemesanan->save();

        $data['record'] = new PembayaranResource($pembayaran);
        return $this->success($data, 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pembayaran = Pembayaran:: findOrFail($id);
        $data['record'] = new PembayaranResource($pembayaran);
        return $this -> success($data,'Data Berhasil Ditampilkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->pelanggan_id = $request->pelanggan_id;
        $pembayaran->tanggal_pembayaran = $request->tanggal_pembayaran;
        $pembayaran->metode_pembayaran = $request->metode_pembayaran;
        $pembayaran->pembayaran = $request->pembayaran;
        $pembayaran->status = $request->status;
        $pembayaran->save();
        $data['record'] = new PembayaranResource($pembayaran);
        return $this->success($data, 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pembayaran::findOrFail($id)->delete();
        return $this->success(null, 'Data Berhasil Dihapus');
    }
}
