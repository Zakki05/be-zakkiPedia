<?php

namespace App\Http\Controllers;

use App\Http\Resources\OngkirResource;
use App\Http\Resources\PagingResource;
use App\Models\Ongkir;
use Illuminate\Http\Request;

class OngkirController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $ongkirs = Ongkir::where(function ($f) use ($q) {
            if ($q){
                $f->where('kode_ongkir', 'LIKE', '%' . $q . '%');
            }
        })
        ->orderBy('created_at','desc')
        ->paginate(10);

        $data['records'] = OngkirResource::collection($ongkirs);
        $data['paging'] = new PagingResource($ongkirs);
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
        $ongkir = new Ongkir;
        $ongkir->kota = $request->kota;
        $ongkir->biaya = $request->biaya;
        $ongkir->save();
        $data['record'] = new OngkirResource($ongkir);
        return $this->success($data, 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ongkir = Ongkir:: findOrFail($id);
        $data['record'] = new OngkirResource($ongkir);
        return $this -> success($data,'Data Berhasil Ditampilkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ongkir $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ongkir = Ongkir::findOrFail($id);
        $ongkir->kota = $request->kota;
        $ongkir->biaya = $request->biaya;
        $ongkir->save();
        $data['record'] = new OngkirResource($ongkir);
        return $this->success($data, 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Ongkir::findOrFail($id)->delete();
        return $this->success(null, 'Data Berhasil Dihapus');
    }
}
