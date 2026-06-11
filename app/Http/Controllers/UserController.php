<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $query = User::query();
        
        if ($request->has('search') && $request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%");
        }
        
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        $users = $query->paginate(10);
        
        $totalUsers = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $newThisMonth = User::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count();
        
        return view('users.index', compact('users', 'totalUsers', 'totalAdmin', 'totalPetugas', 'newThisMonth'));
    }
    
    public function create()
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        return view('users.create');
    }
    
    public function store(Request $request)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,petugas',
        ]);
        
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);
        
        return redirect()->route('users.index')->with('success', 'Petugas berhasil ditambahkan');
    }
    
    public function edit(User $user)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        return view('users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,petugas',
        ]);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }
        
        $user->update($validated);
        
        return redirect()->route('users.index')->with('success', 'Petugas berhasil diupdate');
    }
    
    public function destroy(User $user)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }
        
        if ($user->id == auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri');
        }
        
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'Petugas berhasil dihapus');
    }
}