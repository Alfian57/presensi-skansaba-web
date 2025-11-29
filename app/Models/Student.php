<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'classroom_id',
        'nisn',
        'nis',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'entry_year',
        'parent_name',
        'parent_phone',
        'active_device_id',
        'device_registered_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'entry_year' => 'integer',
        'device_registered_at' => 'datetime',
        'gender' => Gender::class,
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function classAbsences()
    {
        return $this->hasMany(ClassAbsence::class);
    }

    // Scopes
    public function scopeByClassroom($query, $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }

    public function scopeByEntryYear($query, $year)
    {
        return $query->where('entry_year', $year);
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })->orWhere('nisn', 'like', "%{$search}%")
            ->orWhere('nis', 'like', "%{$search}%");
    }

    // Accessors
    public function getNameAttribute()
    {
        return $this->user->name ?? '';
    }

    public function getEmailAttribute()
    {
        return $this->user->email ?? '';
    }

    public function getProfilePictureUrlAttribute()
    {
        return $this->user->profile_picture_url ?? asset('img/default-avatar.png');
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? \Carbon\Carbon::parse($this->date_of_birth)->age : null;
    }

    // Device Management
    public function registerDevice(string $deviceId): bool
    {
        $this->update([
            'active_device_id' => $deviceId,
            'device_registered_at' => now(),
        ]);

        return true;
    }

    public function unregisterDevice(): bool
    {
        $this->update([
            'active_device_id' => null,
            'device_registered_at' => null,
        ]);

        return true;
    }

    public function isDeviceRegistered(string $deviceId): bool
    {
        return $this->active_device_id === $deviceId;
    }

    // Route Key
    public function getRouteKeyName()
    {
        return 'nisn';
    }
}
