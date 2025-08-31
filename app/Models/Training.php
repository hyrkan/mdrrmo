<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Training Model
 * 
 * Database Structure:
 * - id: INTEGER PRIMARY KEY AUTOINCREMENT
 * - name: TEXT NOT NULL
 * - dates: TEXT NOT NULL CHECK(json_valid(dates)) - stored as JSON array
 * - organized_by: TEXT NOT NULL
 * - requesting_party: TEXT (nullable)
 * - venue: TEXT (nullable)
 * - course_facilitator: TEXT (nullable)
 * - instructor: TEXT (nullable)
 * - created_at: TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 * - updated_at: TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 */
class Training extends Model
{
    protected $fillable = [
        'name',
        'dates',
        'organized_by',
        'requesting_party',
        'venue',
        'course_facilitator',
        'instructor',
        'training_classification'
    ];

    protected $casts = [
        'dates' => 'array', // Cast JSON field to array
    ];

    // Accessor to get formatted date range
    public function getDateRangeAttribute()
    {
        if (empty($this->dates) || !is_array($this->dates)) {
            return null;
        }

        $dates = $this->dates;
        if (count($dates) === 1) {
            return date('M j, Y', strtotime($dates[0]));
        }

        $first = date('M j', strtotime($dates[0]));
        $last = date('M j, Y', strtotime(end($dates)));
        
        return $first . ' - ' . $last;
    }

    /**
     * Many-to-many relationship with participants
     */
    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'training_participant')
                    ->withPivot(['certificate', 'completion_status', 'completed_at', 'certificate_serial', 'issued_by', 'certificate_issued_at'])
                    ->withTimestamps();
    }
}
