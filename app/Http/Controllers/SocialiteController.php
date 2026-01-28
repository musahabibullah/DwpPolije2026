<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;
class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Cek apakah email sudah terdaftar
            $existingUser = User::where('email', $socialUser->email)->first();
            
            // Jika user dengan email tersebut belum ada
            if (!$existingUser) {
                // Cek apakah ini adalah user pertama
                $isFirstUser = User::count() === 0;
                
                // Jika bukan user pertama, tolak login
                if (!$isFirstUser) {
                    return redirect()->to('/user/login')
                        ->with('error', 'Akses ditolak. Email Anda tidak terdaftar dalam sistem.');
                }
                
                // Jika ini user pertama, buat sebagai super admin
                $user = User::create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'provider_id' => $socialUser->id,
                    'provider_name' => $provider,
                    'password' => bcrypt(str()->random(16))
                ]);

                // Pastikan role super_admin sudah ada
                $superAdminRole = Role::where('name', 'super_admin')->first();
                if (!$superAdminRole) {
                    $superAdminRole = Role::where('name', 'super_admin')->first();
                }

                // Assign role super_admin
                $user->assignRole($superAdminRole);
                
            } else {
                // Update provider info jika user sudah ada
                $existingUser->update([
                    'provider_id' => $socialUser->id,
                    'provider_name' => $provider
                ]);
                $user = $existingUser;
            }

            Auth::login($user);

            // Redirect berdasarkan role
            if ($user->hasRole('super_admin')) {
                return redirect()->intended(route('filament.user.pages.dashboard'));
            } else {
                return redirect()->to('/user/login')
                    ->with('error', 'Anda tidak memiliki akses yang diperlukan.');
            }
            
        } catch (\Exception $e) {
            return redirect()->to('/user/login')
                ->with('error', 'Autentikasi gagal: ' . $e->getMessage());
        }
    }
}