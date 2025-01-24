<?php

namespace App\Http\Controllers;

use App\Http\Resources\PagingResource;
use App\Http\Resources\UserResource;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->q;

        $role_id = $request->role;

        $q = $request->q;

        $users = User::with('role:id,nama_role')->where(function ($f) use ($q, $role_id) {
            if ($q){
                $f->where('username', 'LIKE', '%' . $q . '%');
            }
            if ($role_id) {
                $f->where('role_id', $role_id);
            }
        })
        ->orderBy('created_at','desc')
        ->paginate(10);

        $users->getCollection()->transform(function ($user) {
            if ($user->role_id == 3 && $user->pelanggan) {
                // Mengambil data dari relasi 'sf' untuk role 2
                $user->nama_panggilan = $user->pelanggan->nama_panggilan;
                $user->nama_lengkap = $user->pelanggan->nama_lengkap;
                $user->alamatPelanggan = $user->pelanggan->alamatPelanggan;
                $user->telepon = $user->pelanggan->telepon;
            }

            return $user;
        });

        $data['records'] = UserResource::collection($users);
        $data['paging'] = new PagingResource($users);
        return $this->success($data, 'data berhasil di ambil');
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
        $user = new User;
        $user->role_id = $request->role_id;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = $request->password;
        $user->nama = $request->nama;
        $user->alamat = $request->alamat;
        $user->save();

        if ($user->role_id == 3) {
            $pelanggan = new Pelanggan;
            $pelanggan->user_id = $user->id;
            $pelanggan->nama_lengkap = $request->nama_lengkap;
            $pelanggan->nama_panggilan = $request->nama_panggilan;
            $pelanggan->alamatPelanggan = $request->alamatPelanggan;
            $pelanggan->telepon = $request->telepon;
            $pelanggan->save();
        }

        $data['record'] = new UserResource($user);
        return $this->success($data, 'Data Berhasil Disimpan');
    }

    public function storePelanggan(Request $request)
    {
        $user = new User;
        $user->role_id = $request->role_id;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = $request->password;
        $user->save();

        if ($user->role_id == 3) {
            $pelanggan = new Pelanggan;
            $pelanggan->user_id = $user->id;
            $pelanggan->nama_lengkap = $request->nama_lengkap;
            $pelanggan->nama_panggilan = $request->nama_panggilan;
            $pelanggan->alamatPelanggan = $request->alamatPelanggan;
            $pelanggan->telepon = $request->telepon;
            $pelanggan->save();
        }

        $data['record'] = new UserResource($user);
        return $this->success($data, 'Data Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['role:id,nama_role', 'pelanggan']) // Memuat relasi sf, sbp, network
                ->findOrFail($id);

        // Pastikan untuk mengembalikan nama_panggilan dan nama_lengkap berdasarkan relasi
        if ($user->role_id == 3 && $user->pelanggan) {
            $user->nama_panggilan = $user->pelanggan->nama_panggilan;
            $user->nama_lengkap = $user->pelanggan->nama_lengkap;
            $user->alamatPelanggan = $user->pelanggan->alamatPelanggan;
            $user->telepon = $user->pelanggan->telepon;
        }

        $data['record'] = new UserResource($user);
        return $this->success($data, 'Data Berhasil Ditampilkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->role_id = $request->role_id;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = $request->password;
        $user->nama = $request->nama;
        $user->alamat = $request->alamat;
        $user->save();

        if ($user->role_id == 3) {
            $pelanggan = Pelanggan::where('user_id', $user->id)->first();
            $pelanggan->nama_panggilan = $request->nama_panggilan;
            $pelanggan->nama_lengkap = $request->nama_lengkap;
            $pelanggan->alamatPelanggan = $request->alamatPelanggan;
            $pelanggan->telepon = $request->telepon;
            $pelanggan->save();
        }

        $data['record'] = new UserResource($user);
        return $this->success($data, 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return $this->success(null, 'Data Berhasil Dihapus');
    }

    public function changePassword(Request $request)
    {
        $user = User::findOrFail($request->user_id); //params pada url API

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Current Password not Valid'], 400);
        }

        $user->password = $request->new_password;
        $user->save();

        return $this->success($user, 'Password berhasil di ubah');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password berhasil direset.'])
            : response()->json(['message' => 'Token tidak valid.'], 500);
    }
}
