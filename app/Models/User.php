<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, HasRoles, InteractsWithMedia, Notifiable;

    /**
     * The guard name for Spatie Permission
     */
    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone',
        'profile_picture',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAdmins($query)
    {
        return $query->role('admin');
    }

    public function scopeTeachers($query)
    {
        return $query->role('teacher');
    }

    public function scopeStudents($query)
    {
        return $query->role('student');
    }

    // Accessors & Mutators
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }

        return $this->getFirstMediaUrl('profile-pictures') ?: asset('img/default-avatar.png');
    }

    // Media Collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile-pictures')
            ->singleFile()
            ->useFallbackUrl(asset('img/default-avatar.png'))
            ->useFallbackPath(public_path('img/default-avatar.png'));
    }

    // Helper Methods
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }
}
