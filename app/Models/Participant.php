<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Participant Model
 * 
 * Database Structure:
 * - id: INTEGER PRIMARY KEY AUTOINCREMENT
 * - id_no: TEXT (nullable) - Government/employee ID
 * - first_name: TEXT NOT NULL
 * - middle_name: TEXT (nullable)
 * - last_name: TEXT NOT NULL
 * - agency_organization: TEXT (nullable) - Employer/affiliation
 * - position_designation: TEXT (nullable) - Job title
 * - sex: ENUM('male', 'female')
 * - vulnerable_groups: JSON (nullable) - Array of groups e.g. ["PWD", "Senior"]
 * - created_at: TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 * - updated_at: TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 */
class Participant extends Model
{
    protected $fillable = [
        'id_no',
        'first_name',
        'middle_name',
        'last_name',
        'agency_organization',
        'position_designation',
        'sex',
        'vulnerable_groups',
    ];

    protected $casts = [
        'vulnerable_groups' => 'array', // Cast JSON field to array
    ];

    /**
     * Get the participant's full name
     */
    public function getFullNameAttribute()
    {
        $parts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name
        ]);
        
        return implode(' ', $parts);
    }

    /**
     * Get formatted vulnerable groups as a comma-separated string
     */
    public function getVulnerableGroupsFormattedAttribute()
    {
        if (empty($this->vulnerable_groups) || !is_array($this->vulnerable_groups)) {
            return 'None';
        }

        return implode(', ', $this->vulnerable_groups);
    }

    /**
     * Many-to-many relationship with trainings
     */
    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'training_participant')
                    ->withPivot(['certificate', 'completion_status', 'completed_at', 'certificate_serial', 'issued_by', 'certificate_issued_at'])
                    ->withTimestamps();
    }
}
