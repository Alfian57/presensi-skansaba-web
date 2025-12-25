<?php

namespace App\Services;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherService
{
    /**
     * Create a new teacher with user account.
     */
    public function create(array $data): Teacher
    {
        return DB::transaction(function () use ($data) {
            // Handle profile picture upload
            $profilePicturePath = null;
            if (isset($data['profile_picture'])) {
                $profilePicturePath = $data['profile_picture']->store('profile-pictures', 'public');
            }

            // Create user account
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'] ?? $data['nip'],
                'password' => Hash::make($data['password'] ?? 'password'),
                'profile_picture' => $profilePicturePath,
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Assign teacher role
            $user->assignRole('teacher');

            // Create teacher record
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'nip' => $data['nip'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
            ]);

            return $teacher->fresh(['user']);
        });
    }

    /**
     * Update teacher data.
     */
    public function update(Teacher $teacher, array $data): Teacher
    {
        return DB::transaction(function () use ($teacher, $data) {
            // Handle profile picture deletion
            if (isset($data['delete_profile_picture']) && $data['delete_profile_picture']) {
                if ($teacher->user->profile_picture) {
                    Storage::disk('public')->delete($teacher->user->profile_picture);
                    $teacher->user->update(['profile_picture' => null]);
                }
            }

            // Handle new profile picture upload
            if (isset($data['profile_picture'])) {
                // Delete old profile picture if exists
                if ($teacher->user->profile_picture) {
                    Storage::disk('public')->delete($teacher->user->profile_picture);
                }
                // Store new profile picture
                $profilePicturePath = $data['profile_picture']->store('profile-pictures', 'public');
                $teacher->user->update(['profile_picture' => $profilePicturePath]);
            }

            // Update user data if provided
            if (isset($data['name']) || isset($data['email']) || isset($data['username'])) {
                $teacher->user->update([
                    'name' => $data['name'] ?? $teacher->user->name,
                    'email' => $data['email'] ?? $teacher->user->email,
                    'username' => $data['username'] ?? $teacher->user->username,
                ]);
            }

            // Update password if provided
            if (isset($data['password'])) {
                $teacher->user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }

            // Update is_active if provided
            if (isset($data['is_active'])) {
                $teacher->user->update([
                    'is_active' => $data['is_active'],
                ]);
            }

            // Update teacher data
            $teacher->update([
                'nip' => $data['nip'] ?? $teacher->nip,
                'date_of_birth' => $data['date_of_birth'] ?? $teacher->date_of_birth,
                'gender' => $data['gender'],
                'phone' => $data['phone'] ?? $teacher->phone,
                'address' => $data['address'] ?? $teacher->address,
            ]);

            return $teacher->fresh(['user']);
        });
    }

    /**
     * Delete teacher and user account.
     */
    public function delete(Teacher $teacher): bool
    {
        return DB::transaction(function () use ($teacher) {
            $user = $teacher->user;
            $teacher->delete();

            return $user->delete();
        });
    }

    /**
     * Reset teacher password.
     */
    public function resetPassword(Teacher $teacher, string $newPassword): bool
    {
        return $teacher->user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Search teachers.
     */
    public function search(string $query)
    {
        return Teacher::with(['user'])
            ->where('nip', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->get();
    }
}
