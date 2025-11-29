<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_number',
        'date_of_birth',
        'gender',
        'phone',
        'address',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'gender' => Gender::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function homerooms()
    {
        return $this->hasMany(HomeroomTeacher::class);
    }

    public function activeHomeroom()
    {
        return $this->hasOne(HomeroomTeacher::class)->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })->orWhere('employee_number', 'like', "%{$search}%");
    }

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

    public function getRouteKeyName()
    {
        return 'nip';
    }
}
