<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'description',
        'type',
        'is_recurring',
    ];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
    ];

    // Scopes
    public function scopeNational($query)
    {
        return $query->where('type', 'national');
    }

    public function scopeSchool($query)
    {
        return $query->where('type', 'school');
    }

    public function scopeRegional($query)
    {
        return $query->where('type', 'regional');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', today())->orderBy('date');
    }

    public function scopeInMonth($query, $month, $year = null)
    {
        $year = $year ?? date('Y');

        return $query->whereMonth('date', $month)
            ->whereYear('date', $year);
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // Helper Methods
    public static function isHoliday($date): bool
    {
        return self::whereDate('date', $date)->exists();
    }

    public static function getTodayHoliday()
    {
        return self::whereDate('date', today())->first();
    }
}
