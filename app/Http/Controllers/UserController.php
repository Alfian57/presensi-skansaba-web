<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    /**
     * Display a listing of users (admin accounts).
     */
    public function index(Request $request)
    {
        $query = User::with(['roles'])
            ->whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            });

        // Search by name, email, or username
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('users.admins.index', compact('users'));
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create()
    {
        return view('users.admins.create');
    }

    /**
     * Store a newly created admin user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('admin');

            Alert::success('Berhasil', "Admin {$user->name} berhasil ditambahkan.");

            return redirect()->route('dashboard.admins.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $admin)
    {
        $admin->load(['roles']);

        return view('users.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $admin)
    {
        $admin->load(['roles']);

        return view('users.admins.edit', compact('admin'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'username' => 'required|string|max:50|unique:users,username,' . $admin->id,
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
        ]);

        try {
            $admin->update($request->only(['name', 'email', 'username']));

            Alert::success('Berhasil', "Data admin {$admin->name} berhasil diperbarui.");

            return redirect()->route('dashboard.admins.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $admin)
    {
        try {
            // Prevent deleting yourself
            if ($admin->id === auth()->id()) {
                Alert::warning('Gagal', 'Anda tidak dapat menghapus akun Anda sendiri.');

                return back();
            }

            // Prevent deleting if only one admin left
            $adminCount = User::role('admin')->count();
            if ($adminCount <= 1) {
                Alert::warning('Gagal', 'Tidak dapat menghapus admin terakhir.');

                return back();
            }

            $name = $admin->name;
            $admin->delete();

            Alert::success('Berhasil', "Admin {$name} berhasil dihapus.");

            return redirect()->route('dashboard.admins.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back();
        }
    }

    /**
     * Reset user password.
     */
    public function resetPassword(User $admin)
    {
        try {
            $newPassword = 'password'; // Default password
            $admin->update([
                'password' => Hash::make($newPassword),
            ]);

            Alert::success('Berhasil', "Password {$admin->name} berhasil direset ke: {$newPassword}");

            return back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back();
        }
    }
}
