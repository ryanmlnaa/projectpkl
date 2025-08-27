<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PasswordResetCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetCodeMail;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function showResetPassword(Request $request)
    {
        // Pastikan email ada di session atau request
        $email = $request->get('email') ?? session('reset_email');
        
        if (!$email) {
            return redirect()->route('forgot.password')->with('error', 'Sesi expired. Silakan masukkan email Anda lagi.');
        }
        
        return view('auth.reset-password', ['email' => $email]);
    }

    public function sendResetCode(Request $request)
    {
        // Log untuk debugging
        Log::info('Reset code request started', ['email' => $request->email]);

        $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email',
                'regex:/^[^\s]+@gmail\.com$/'
            ]
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com.'
        ]);

        try {
            // Hapus kode lama yang sudah expired
            $deletedCount = PasswordResetCode::where('email', $request->email)
                ->where('expires_at', '<', Carbon::now())
                ->delete();
            Log::info('Deleted expired codes', ['count' => $deletedCount]);

            // Cek apakah masih ada kode aktif
            $existingCode = PasswordResetCode::getActiveCode($request->email);
            
            if ($existingCode) {
                $remainingTime = $existingCode->expires_at->diffInMinutes(Carbon::now());
                Log::info('Active code found', ['remaining_time' => $remainingTime]);
                return back()->with('error', "Kode verifikasi masih aktif. Silakan tunggu {$remainingTime} menit lagi atau cek email Anda.");
            }

            // Generate kode baru
            $code = PasswordResetCode::generateCode();
            $expiresAt = Carbon::now()->addMinutes(15);
            Log::info('Generated new code', ['code' => $code, 'expires_at' => $expiresAt]);

            // Simpan kode ke database
            $resetCode = PasswordResetCode::create([
                'email' => $request->email,
                'code' => $code,
                'expires_at' => $expiresAt
            ]);
            Log::info('Code saved to database', ['id' => $resetCode->id]);

            // Simpan email di session untuk langkah selanjutnya
            session(['reset_email' => $request->email]);

            // Test koneksi email terlebih dahulu
            Log::info('Attempting to send email', ['to' => $request->email]);
            
            // Kirim email dengan error handling yang lebih detail
            try {
                Mail::to($request->email)->send(new PasswordResetCodeMail($code, $request->email));
                Log::info('Email sent successfully');
                
                return redirect()->route('reset.password', ['email' => $request->email])
                    ->with('success', 'Kode verifikasi telah dikirim ke email Anda. Silakan cek inbox atau folder spam.')
                    ->with('email_sent', true);
                    
            } catch (\Exception $mailException) {
                Log::error('Mail sending failed', [
                    'error' => $mailException->getMessage(),
                    'trace' => $mailException->getTraceAsString()
                ]);
                
                // Untuk debugging, tampilkan kode di session (HANYA UNTUK DEVELOPMENT!)
                if (config('app.debug')) {
                    session(['debug_code' => $code]);
                    return redirect()->route('reset.password', ['email' => $request->email])
                        ->with('error', 'Email gagal dikirim. Untuk testing, kode Anda adalah: ' . $code)
                        ->with('email_sent', true);
                }
                
                return back()->with('error', 'Gagal mengirim email. Silakan periksa konfigurasi email atau coba lagi.');
            }

        } catch (\Exception $e) {
            Log::error('General error in sendResetCode', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        Log::info('Reset password attempt', [
            'email' => $request->email,
            'code' => $request->code
        ]);

        $commonPasswords = [
            'password', 'password123', '123456', 'qwerty', 'abc123', 
            'password1', 'admin123', 'welcome', 'letmein', 'monkey',
            'dragon', 'master', 'hello', 'freedom', 'whatever',
            'qazwsx', 'trustno1', 'jordan23', 'harley', 'robert'
        ];

        $request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email'
            ],
            'code' => [
                'required',
                'string',
                'size:6',
                'regex:/^\d{6}$/'
            ],
            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/^\S+$/',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
                function ($attribute, $value, $fail) use ($commonPasswords) {
                    if (in_array(strtolower($value), array_map('strtolower', $commonPasswords))) {
                        $fail('Password yang Anda masukkan terlalu umum dan mudah ditebak. Silakan gunakan password yang lebih unik.');
                    }
                }
            ]
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.exists' => 'Email tidak terdaftar.',
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.size' => 'Kode verifikasi harus 6 digit.',
            'code.regex' => 'Kode verifikasi harus berupa angka.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password harus minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password tidak boleh mengandung spasi dan harus mengandung kombinasi huruf dan angka.'
        ]);

        // Cari kode yang masih aktif
        $resetCode = PasswordResetCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        Log::info('Code verification', [
            'found' => $resetCode ? 'yes' : 'no',
            'all_codes' => PasswordResetCode::where('email', $request->email)->get()->toArray()
        ]);

        if (!$resetCode) {
            return back()->with('error', 'Kode verifikasi tidak valid atau sudah expired.');
        }

        // Update password user
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        Log::info('Password updated successfully', ['user_id' => $user->id]);

        // Hapus semua kode reset untuk email ini
        PasswordResetCode::where('email', $request->email)->delete();

        // Hapus session reset_email
        session()->forget(['reset_email', 'debug_code']);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    public function register(Request $request)
    {
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
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'email' => [
                'required',
                'email',
                'unique:users',
                'regex:/^[^\s]+@gmail\.com$/'
            ],
            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/^\S+$/',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
                function ($attribute, $value, $fail) use ($commonPasswords) {
                    if (in_array(strtolower($value), array_map('strtolower', $commonPasswords))) {
                        $fail('Password yang Anda masukkan terlalu umum dan mudah ditebak. Silakan gunakan password yang lebih unik.');
                    }
                }
            ],
        ], [
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
            'role' => 'user',
        ]);

        Auth::login($user);

        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('login');
    }

    public function login(Request $request)
    {
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
                'regex:/^[^\s]+@gmail\.com$/'
            ],
            'password' => [
                'required',
                'regex:/^\S+$/',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
                function ($attribute, $value, $fail) use ($commonPasswords) {
                    if (in_array(strtolower($value), array_map('strtolower', $commonPasswords))) {
                        $fail('Password yang Anda masukkan terlalu umum dan mudah ditebak.');
                    }
                }
            ],
            'remember' => 'required'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com dan tidak boleh mengandung spasi.',
            
            'password.required' => 'Password wajib diisi.',
            'password.regex' => 'Password tidak boleh mengandung spasi dan harus mengandung kombinasi huruf dan angka.',
            
            'remember.required' => 'Anda harus mencentang "Remember me" untuk dapat login.'
        ]);

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
        ])->with('show_forgot_password', true);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}