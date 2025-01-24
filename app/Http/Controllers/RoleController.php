<?php

namespace App\Http\Controllers;

use App\Http\Resources\PagingResource;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->q;
        $nama_role = $request->nama_role;

        $roles = Role::where(function($f) use ($q, $nama_role) {
            if ($q){
                $f->where('nama_role', 'LIKE', '%' . $q . '%');
            }
            if ($nama_role) {
                $f->where('nama_role', $nama_role);
            }
        })->paginate(5);

        $data['records'] = RoleResource::collection($roles);
        $data['paging'] = new PagingResource($roles);
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
        $role = new Role();
        $role->nama_role = $request->nama_role;
        $role->save();
        $data['record'] = new RoleResource($role);
        return $this->success($data,'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role:: findOrFail($id);
        $data['record'] = new RoleResource($role);
        return $this -> success($data,'Data Berhasil Ditampilkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role=Role::findOrFail($id);
        $role->nama_role = $request->nama_role;
        $role->save();
        $data['record'] = new RoleResource($role);
        return $this->success($data,'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Role::findOrFail($id)->delete();
        return $this->success(null,'Data Berhasil Dihapus');
    }
}
