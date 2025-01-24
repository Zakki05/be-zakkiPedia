<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerifyEmail;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $user = User::create([
            'role_id' => $request->role_id,
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $data['record'] = new UserResource($user);
        return $this->success($data, 'Registrasi Berhasil, silakan melakukan login.');
    }

    public function login(Request $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(
                [
                    'status' => false,
                    'message' => "Email Atau Pasword Tidak Ditemukan !"
                ],
                400
            );
        }

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('authToken')->accessToken;
        $data['user'] = $user;
        $data['token'] = $token;

        $roleName = $user->role->nama_role ?? 'Guest';

        // Kembalikan respon dengan pesan yang diinginkan
        return response()->json([
            'status' => true,
            'message' => "Login berhasil, Anda login sebagai $roleName",
            'data' => $data
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->token()->delete();

        return $this->success(null, 'Berhasil Logout');
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $user->id;
        if (Auth::attempt(['id' => $user->id])) {

            $token = $user->createToken('authToken')->accessToken;
            $data['user'] = $user;
            $data['token'] = $token;

            return $this->success($data, 'Login Berhasil');
        }
    }

    public function validateToken(Request $request)
    {
        $user = $request->user();
        $data['record'] = new UserResource($user);

        return $this->success($data, 'Data Berhasil Tampil');
    }

    /*     public function verify($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->email_verified_at = now();
            $user->status = 'active'; // Ubah status menjadi 'active'
            $user->save();

            return redirect()->to('http://localhost:3000/login?verified=true');
        }

        return redirect()->to('http://localhost:3000/login?verified=false');
    } */
}
