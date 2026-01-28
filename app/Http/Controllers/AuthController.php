<?php

// app/Http/Controllers/Api/AuthController.php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
        {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Cek nama harus 'admin'
                if ($user->name !== 'admin') {
                    Auth::logout();
                    return response()->json([
                        'status' => false,
                        'message' => 'Hanya admin yang bisa login'
                    ], 403);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Login berhasil sebagai admin',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }
}
