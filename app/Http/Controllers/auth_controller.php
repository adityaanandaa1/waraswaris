<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\akun_user;

class auth_controller extends Controller
{
    public function tampilkan_login ()
    {
        if(Auth::check()) {
            return $this->redirectToDashboard();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

         // Ambil credential dari form
        $credentials = $request->only('email', 'password');

        // Attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session (security)
            $request->session()->regenerate();

            // Redirect ke dashboard sesuai role
            return $this->redirectToDashboard();
        }

        // Jika gagal, kembali ke login dengan error
        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->withInput($request->only('email'));
    }
    
    //mengecek register
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:akun_users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            // Kembalikan ke mode register saat validasi gagal
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('auth_mode', 'register');
        }

        // Buat user baru dengan role pasien
        $user = akun_user::firstOrCreate(
            ['email' => $request->email], // kunci unik
            ['password' => Hash::make($request->password), 'role' => 'pasien'] // hanya dipakai saat create
        );

        // Login otomatis setelah register
        Auth::login($user);

        // Redirect ke halaman isi biodata
        return redirect()->route('pasien.tambah_biodata')
            ->with('success', 'Registrasi berhasil! Silakan lengkapi biodata Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout');
    }
    
    private function redirectToDashboard()
    {
        $role = strtolower(trim((string) optional(\Illuminate\Support\Facades\Auth::user())->role));

        return match ($role) {
        'dokter'      => redirect()->route('dokter.dashboard'),
        'pasien'      => redirect()->route('pasien.dashboard'),
        'resepsionis' => redirect()->route('resepsionis.dashboard'),
        default       => redirect()->route('login'),
    };
    }

   

    
    
}
