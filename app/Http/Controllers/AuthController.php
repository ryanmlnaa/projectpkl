<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Daftar password yang familiar dan tidak boleh digunakan
        $commonPasswords = [
            'password', 'password123', '123456', 'qwerty', 'abc123', 
            'password1', 'admin123', 'welcome', 'letmein', 'monkey',
            'dragon', 'master', 'hello', 'freedom', 'whatever',
            'qazwsx', 'trustno1', 'jordan23', 'harley', 'robert'
        ];

        $request->validate([
            'name' => [
                'required',
                'string',
                'min:8',
                'regex:/^[a-zA-Z\s]+$/' // Hanya huruf dan spasi
            ],
            'email' => [
                'required',
                'email',
                'unique:users',
                'regex:/^[^\s]+@gmail\.com$/' // Wajib @gmail.com dan tidak boleh ada spasi
            ],
            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/^\S+$/', // Tidak boleh ada spasi
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/', // Harus ada huruf dan angka
                function ($attribute, $value, $fail) use ($commonPasswords) {
                    if (in_array(strtolower($value), array_map('strtolower', $commonPasswords))) {
                        $fail('Password yang Anda masukkan terlalu umum dan mudah ditebak. Silakan gunakan password yang lebih unik.');
                    }
                }
            ],
        ], [
            // Custom error messages dalam bahasa Indonesia
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap harus minimal 8 karakter.',
            'name.regex' => 'Nama lengkap hanya boleh mengandung huruf dan spasi, tidak boleh menggunakan angka.',
            
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com dan tidak boleh mengandung spasi.',
            
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password tidak boleh mengandung spasi dan harus mengandung kombinasi huruf dan angka.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // default role user
        ]);

        Auth::login($user);

        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('login');
    }

    public function login(Request $request)
    {
        // Daftar password yang familiar dan tidak boleh digunakan (sama dengan register)
        $commonPasswords = [
            'password', 'password123', '123456', 'qwerty', 'abc123', 
            'password1', 'admin123', 'welcome', 'letmein', 'monkey',
            'dragon', 'master', 'hello', 'freedom', 'whatever',
            'qazwsx', 'trustno1', 'jordan23', 'harley', 'robert'
        ];

        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[^\s]+@gmail\.com$/' // Wajib @gmail.com dan tidak boleh ada spasi
            ],
            'password' => [
                'required',
                'regex:/^\S+$/', // Tidak boleh ada spasi
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/', // Harus ada huruf dan angka
                function ($attribute, $value, $fail) use ($commonPasswords) {
                    if (in_array(strtolower($value), array_map('strtolower', $commonPasswords))) {
                        $fail('Password yang Anda masukkan terlalu umum dan mudah ditebak.');
                    }
                }
            ],
            'remember' => 'required' // Remember me wajib dicentang
        ], [
            // Custom error messages untuk login
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com dan tidak boleh mengandung spasi.',
            
            'password.required' => 'Password wajib diisi.',
            'password.regex' => 'Password tidak boleh mengandung spasi dan harus mengandung kombinasi huruf dan angka.',
            
            'remember.required' => 'Anda harus mencentang "Remember me" untuk dapat login.'
        ]);

        // Hapus remember dari credentials untuk authentication
        $loginCredentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($loginCredentials, $request->remember)) {
            $request->session()->regenerate();

            return Auth::user()->role === 'admin'
                ? redirect()->route('dashboard')
                : redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}