<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HandlesAlerts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use HandlesAlerts;

    /**
     * Display a listing of users (admin accounts).
     */
    public function index(Request $request)
    {
        $query = User::with(['roles'])
            ->whereHas('roles', fn($q) => $q->where('name', 'admin'));

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
            $this->alertSuccess("Admin {$user->name} berhasil ditambahkan.");

            return redirect()->route('dashboard.admins.index');
        } catch (\Exception $e) {
            $this->alertException($e);

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
            $this->alertSuccess("Data admin {$admin->name} berhasil diperbarui.");

            return redirect()->route('dashboard.admins.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back()->withInput();
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $admin)
    {
        try {
            if ($admin->id === auth()->id()) {
                $this->alertWarning('Anda tidak dapat menghapus akun Anda sendiri.');

                return back();
            }

            $adminCount = User::role('admin')->count();
            if ($adminCount <= 1) {
                $this->alertWarning('Tidak dapat menghapus admin terakhir.');

                return back();
            }

            $name = $admin->name;
            $admin->delete();
            $this->alertSuccess("Admin {$name} berhasil dihapus.");

            return redirect()->route('dashboard.admins.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }

    /**
     * Reset user password.
     */
    public function resetPassword(User $admin)
    {
        try {
            $newPassword = 'password';
            $admin->update(['password' => Hash::make($newPassword)]);
            $this->alertSuccess("Password {$admin->name} berhasil direset ke: {$newPassword}");

            return back();
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }
}
