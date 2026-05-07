<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    // instantiate authservice
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService; 
    }

    public function showLoginForm(){
        return view('auth.login');
    }

    public function showRegisterForm(){
        return view('auth.register');
    }

    // Registrasi
    public function register(Request $request){
        // validasi hasil input form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|',
            'phone' => 'nullable|string|max:20',
        ]);

        try {

            // panggil service create user
            $user = $this->authService->registerUser($validated);
            // auto login setelah registrasi
            Auth::login($user);
            return redirect()->route('home')->with('success', 'Akun berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Registrasi: ' . $e->getMessage())->withInput();
        }
    }

    // Login (email)
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // panggil service login
        if ($this->authService->attemptLogin($credentials, $request->filled('remember'))){
            $request->session()->regenerate();

            // redirect sesuai role, kalo admin ke dasbor
            if (Auth::user()->role === 'admin'){
                return redirect()->route('admin.dashboard');
            }
            return redirect()->intended(route('home'))->with('success', 'Login berhasil!');
            }
        // gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // logout
    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // Handle Profile Information Update
        if ($request->action === 'profile') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);

            $user->name = $validated['name'];
            $user->phone = $validated['phone'];
            $user->email = $validated['email'];
            $user->save();

            return back()->with('success', 'Informasi profil berhasil diperbarui!');
        }

        // Handle Password Update
        if ($request->action === 'password') {
            $request->validate([
                'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                    if (!\Illuminate\Support\Facades\Hash::check($value, $user->password)) {
                        $fail('Password saat ini tidak cocok.');
                    }
                }],
                'password' => 'required|min:6|confirmed',
            ]);

            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $user->save();

            return back()->with('success', 'Password berhasil diperbarui!');
        }

        return back();
    }

}
