<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
    public const PARTICIPANT_TYPES = [
        'DRRMO',
        'DRRMC',
        'CITY HALL OFFICE',
        'BRGY',
        'NATL. AGENCY',
        'OTHER LGU',
        'PRIVATE SECTOR',
        'OTHER/S (school)',
    ];

    /**
     * Normalize participant type (e.g., mapping "Barangay" to "BRGY")
     */
    public static function normalizeParticipantType($type)
    {
        if (! $type) {
            return $type;
        }

        $typeUpper = strtoupper(trim($type));

        // Robust "Barangay" detection
        $barangayVariations = [
            'BARANGAY',
            'BRGY',
            'BRGY.',
            'BGY',
            'BGY.',
            'BARANGAY OFFICE',
        ];

        if (in_array($typeUpper, $barangayVariations)) {
            return 'BRGY';
        }

        // Catch other common mappings to match PARTICIPANT_TYPES
        $mappings = [
            'CITY HALL' => 'CITY HALL OFFICE',
            'DRRM' => 'DRRMO',
            'NATIONAL' => 'NATL. AGENCY',
            'PRIVATE' => 'PRIVATE SECTOR',
            'SCHOOL' => 'OTHER/S (school)',
        ];

        foreach ($mappings as $key => $value) {
            if (str_contains($typeUpper, $key)) {
                return $value;
            }
        }

        // If it matches exactly one of our constants but in different case, return the constant
        if (in_array($typeUpper, self::PARTICIPANT_TYPES)) {
            return $typeUpper;
        }

        return $type;
    }

    /**
     * Mutator to automatically normalize participant type when set
     */
    protected function participantType(): Attribute
    {
        return Attribute::make(
            null,
            fn ($value) => self::normalizeParticipantType($value)
        );
    }

    protected $fillable = [
        'id_no',
        'first_name',
        'middle_name',
        'last_name',
        'agency_organization',
        'position_designation',
        'sex',
        'participant_type', // Add this line
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
            $this->last_name,
        ]);

        return implode(' ', $parts);
    }

    /**
     * Get the participant's formal name (Surname, Firstname M.)
     */
    public function getFormalNameAttribute()
    {
        $name = "{$this->last_name}, {$this->first_name}";

        if ($this->middle_name) {
            $initial = strtoupper(substr($this->middle_name, 0, 1));
            $name .= " {$initial}.";
        }

        return $name;
    }

    /**
     * Get formatted vulnerable groups as a comma-separated string
     */
    public function getVulnerableGroupsFormattedAttribute()
    {
        if (empty($this->vulnerable_groups) || ! is_array($this->vulnerable_groups)) {
            return 'N/A';
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
