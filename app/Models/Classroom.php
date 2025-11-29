<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'grade_level',
        'major',
        'class_number',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
        'class_number' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($classroom) {
            if (empty($classroom->slug)) {
                $classroom->slug = Str::slug($classroom->name);
            }
        });

        static::updating(function ($classroom) {
            if ($classroom->isDirty('name')) {
                $classroom->slug = Str::slug($classroom->name);
            }
        });
    }

    // Relationships
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function homeroom()
    {
        return $this->hasOne(HomeroomTeacher::class);
    }

    public function activeHomeroom()
    {
        return $this->hasOne(HomeroomTeacher::class)->where('is_active', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGradeLevel($query, $level)
    {
        return $query->where('grade_level', $level);
    }

    public function scopeByMajor($query, $major)
    {
        return $query->where('major', $major);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }

    // Route Key
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
