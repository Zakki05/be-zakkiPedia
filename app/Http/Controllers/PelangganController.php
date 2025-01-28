<?php

namespace App\Http\Controllers;

use App\Http\Resources\PagingResource;
use App\Http\Resources\PelangganResource;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->q;

        $user_id=$request->user_id;
        if($user_id){
            $pelanggan= Pelanggan::with('user')->where('user_id',$user_id)->first();

            $data['record']=new PelangganResource($pelanggan);
            return $this->success($data,'Data Berhasil Tampil');
        }

        $pelanggans = Pelanggan::whereHas('user', function($f) use ($q) {
            if ($q){
                $f->where('email', 'LIKE', '%' . $q . '%');
            }
        })->orderBy('id', 'desc')->paginate(5);

        $data['records'] = PelangganResource::collection($pelanggans);
        $data['paging'] = new PagingResource($pelanggans);
        return $this->success($data,'Data Berhasil Diambil');
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
        $pelanggan = new Pelanggan();
        $pelanggan->user_id = $request->user_id;
        $pelanggan->nama_lengkap = $request->nama_lengkap;
        $pelanggan->nama_panggilan = $request->nama_panggilan;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->telepon = $request->telepon;
        $pelanggan->save();
        $data['record'] = new PelangganResource($pelanggan);
        return $this->success($data,'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pelanggan = Pelanggan:: findOrFail($id);
        $data['record'] = new PelangganResource($pelanggan);
        return $this -> success($data,'Data Berhasil Ditampilkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pelanggan=Pelanggan::findOrFail($id);
        $pelanggan->user_id = $request->user_id;
        $pelanggan->nama_lengkap = $request->nama_lengkap;
        $pelanggan->nama_panggilan = $request->nama_panggilan;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->telepon = $request->telepon;
        $pelanggan->save();
        $data['record'] = new PelangganResource($pelanggan);
        return $this->success($data,'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pelanggan::findOrFail($id)->delete();
        return $this->success(null,'Data Berhasil Dihapus');
    }

    public function dataPelanggan(Request $request)
    {
        $q = $request->q;

        $pelanggans = Pelanggan::whereHas('user', function($f) use ($q) {
            if ($q){
                $f->where('email', 'LIKE', '%' . $q . '%');
            }
        })->orderBy('id', 'desc')->paginate(1000);

        $data['records'] = PelangganResource::collection($pelanggans);
        $data['paging'] = new PagingResource($pelanggans);
        return $this->success($data,'Data Berhasil Diambil');
    }
}
