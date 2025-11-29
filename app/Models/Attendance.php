<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'status',
        'check_in_time',
        'check_out_time',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'status' => AttendanceStatus::class,
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByClassroom($query, $classroomId)
    {
        return $query->whereHas('student', function ($q) use ($classroomId) {
            $q->where('classroom_id', $classroomId);
        });
    }

    public function isCheckedIn(): bool
    {
        return $this->check_in_time !== null;
    }

    public function isCheckedOut(): bool
    {
        return $this->check_out_time !== null;
    }

    public function isCompleted(): bool
    {
        return $this->isCheckedIn() && $this->isCheckedOut();
    }
}
