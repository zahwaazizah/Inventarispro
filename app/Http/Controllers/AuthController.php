<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Process login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Process register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,petugas',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    // Process logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // Show profile page
    public function profile()
    {
        $user = Auth::user();
        
        // Hitung statistik untuk user yang login - menggunakan kolom yang ada di tabel transactions
        // Kolom yang tersedia: peminjam, created_by
        $totalTransaksi = Transaksi::where('peminjam', $user->name)
            ->orWhere('created_by', $user->id)
            ->count();
        
        $totalPeminjaman = Transaksi::where(function($query) use ($user) {
                $query->where('peminjam', $user->name)
                      ->orWhere('created_by', $user->id);
            })
            ->where('status', 'dipinjam')
            ->count();
        
        $totalPengembalian = Transaksi::where(function($query) use ($user) {
                $query->where('peminjam', $user->name)
                      ->orWhere('created_by', $user->id);
            })
            ->where('status', 'dikembalikan')
            ->count();
        
        return view('profile.index', compact('user', 'totalTransaksi', 'totalPeminjaman', 'totalPengembalian'));
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil berhasil diupdate!');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah!');
    }
}