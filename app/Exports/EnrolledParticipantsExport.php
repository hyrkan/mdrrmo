<?php

namespace App\Exports;

use App\Models\Training;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EnrolledParticipantsExport implements FromArray, WithStyles, ShouldAutoSize, WithTitle
{
    protected $training;
    protected $search;
    protected $statusFilter;
    protected $participants;

    public function __construct(Training $training, $search = null, $statusFilter = null)
    {
        $this->training = $training;
        $this->search = $search;
        $this->statusFilter = $statusFilter;
        $this->loadParticipants();
    }

    protected function loadParticipants()
    {
        $query = $this->training->participants();

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('agency_organization', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter) {
            if ($this->statusFilter === 'certificate_issued') {
                $query->wherePivot('certificate', true);
            } elseif ($this->statusFilter === 'ready_for_certificate') {
                $query->wherePivot('completion_status', 'completed')
                      ->wherePivot('certificate', false);
            } else {
                $query->wherePivot('completion_status', $this->statusFilter);
            }
        }

        $this->participants = $query->orderBy('first_name')->get();
    }

    public function array(): array
    {
        $data = [];
        
        // Header section - matching the template format exactly
        $data[] = [strtoupper($this->training->name)]; // Row 1: Training name
        $data[] = ['Batch no. 1']; // Row 2: Batch number
        $data[] = ['VENUE:', $this->training->venue ?: 'Pasay City']; // Row 3: Venue
        $data[] = ['INCLUSIVE DATES:', $this->formatDates()]; // Row 4: Dates
        $data[] = ['COURSE FACILITATOR:', $this->training->course_facilitator ?: 'Marinelle Saldo']; // Row 5: Facilitator
        $data[] = []; // Row 6: Empty row

        // Table headers
        $data[] = [
            'ID NO.',
            'FIRST NAME',
            'MIDDLE NAME',
            'LAST NAME',
            'AGENCY / ORGANIZATION',
            'POSITION / DESIGNATION',
            'CERTIFICATE',
            'MALE',
            'FEMALE',
            'PWD',
            'PREGNANT',
            'SENIOR',
            'DRRMO',
            'DRRMC',
            'CITY HALL',
            'BRGY OFFICE',
            'NATL AGENCY',
            'OTHER LGU',
            'PRIVATE SECTOR',
            'OTHERS'
        ];

        // Participant data
        foreach ($this->participants as $index => $participant) {
            $data[] = $this->mapParticipant($participant, $index + 1);
        }

        // Totals row
        $data[] = $this->calculateTotals();

        return $data;
    }

    protected function formatDates()
    {
        if (empty($this->training->dates) || !is_array($this->training->dates)) {
            return 'Not specified';
        }

        $dates = array_map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M j, Y');
        }, $this->training->dates);

        if (count($dates) === 1) {
            return $dates[0];
        }

        return implode(' - ', [reset($dates), end($dates)]);
    }

    protected function mapParticipant($participant, $index)
    {
        // Gender mapping
        $isMale = isset($participant->sex) && strtolower($participant->sex) === 'male' ? 1 : 0;
        $isFemale = isset($participant->sex) && strtolower($participant->sex) === 'female' ? 1 : 0;
        
        // Vulnerable groups mapping
        $vulnerableGroups = is_array($participant->vulnerable_groups) ? $participant->vulnerable_groups : [];
        $isPwd = $this->hasVulnerableGroup($vulnerableGroups, ['PWD', 'pwd']) ? 1 : 0;
        $isPregnant = $this->hasVulnerableGroup($vulnerableGroups, ['Pregnant', 'pregnant']) ? 1 : 0;
        $isSenior = $this->hasVulnerableGroup($vulnerableGroups, ['Senior', 'senior']) ? 1 : 0;
        
        // Organization type mapping
        $org = strtolower($participant->agency_organization ?? '');
        $isDrrmo = str_contains($org, 'drrmo') ? 1 : 0;
        $isDrrmc = str_contains($org, 'drrmc') ? 1 : 0;
        $isCityHall = (str_contains($org, 'city hall') || str_contains($org, 'city government')) ? 1 : 0;
        $isBrgy = (str_contains($org, 'barangay') || str_contains($org, 'brgy')) ? 1 : 0;
        $isNatlAgency = (str_contains($org, 'national') || str_contains($org, 'department')) ? 1 : 0;
        $isOtherLgu = (str_contains($org, 'lgu') || str_contains($org, 'municipal')) ? 1 : 0;
        $isPrivate = (str_contains($org, 'private') || str_contains($org, 'company') || str_contains($org, 'corp')) ? 1 : 0;
        $isOthers = !($isDrrmo || $isDrrmc || $isCityHall || $isBrgy || $isNatlAgency || $isOtherLgu || $isPrivate) ? 1 : 0;

        // Certificate info
        $certificate = '';
        if ($participant->pivot->certificate) {
            $certificate = $participant->pivot->certificate_serial ?: 'Diving NC II: 241305020200231E';
        }

        return [
            $index,
            strtoupper($participant->first_name ?? ''),
            strtoupper($participant->middle_name ?? ''),
            strtoupper($participant->last_name ?? ''),
            strtoupper($participant->agency_organization ?? ''),
            strtoupper($participant->position_designation ?? ''),
            $certificate,
            $isMale,
            $isFemale,
            $isPwd,
            $isPregnant,
            $isSenior,
            $isDrrmo,
            $isDrrmc,
            $isCityHall,
            $isBrgy,
            $isNatlAgency,
            $isOtherLgu,
            $isPrivate,
            $isOthers
        ];
    }

    protected function hasVulnerableGroup($groups, $searchFor)
    {
        foreach ($searchFor as $search) {
            if (in_array($search, $groups)) {
                return true;
            }
        }
        return false;
    }

    protected function calculateTotals()
    {
        $totals = [
            'TOTAL',
            '', '', '', '', '', '',
            0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
        ];

        foreach ($this->participants as $participant) {
            // Gender totals
            if (isset($participant->sex) && strtolower($participant->sex) === 'male') $totals[7]++;
            if (isset($participant->sex) && strtolower($participant->sex) === 'female') $totals[8]++;
            
            // Vulnerable groups totals
            $vulnerableGroups = is_array($participant->vulnerable_groups) ? $participant->vulnerable_groups : [];
            if ($this->hasVulnerableGroup($vulnerableGroups, ['PWD', 'pwd'])) $totals[9]++;
            if ($this->hasVulnerableGroup($vulnerableGroups, ['Pregnant', 'pregnant'])) $totals[10]++;
            if ($this->hasVulnerableGroup($vulnerableGroups, ['Senior', 'senior'])) $totals[11]++;
            
            // Organization totals
            $org = strtolower($participant->agency_organization ?? '');
            if (str_contains($org, 'drrmo')) $totals[12]++;
            else if (str_contains($org, 'drrmc')) $totals[13]++;
            else if (str_contains($org, 'city hall') || str_contains($org, 'city government')) $totals[14]++;
            else if (str_contains($org, 'barangay') || str_contains($org, 'brgy')) $totals[15]++;
            else if (str_contains($org, 'national') || str_contains($org, 'department')) $totals[16]++;
            else if (str_contains($org, 'lgu') || str_contains($org, 'municipal')) $totals[17]++;
            else if (str_contains($org, 'private') || str_contains($org, 'company') || str_contains($org, 'corp')) $totals[18]++;
            else $totals[19]++;
        }

        return $totals;
    }

    public function styles(Worksheet $sheet)
    {
        $participantCount = $this->participants->count();
        $headerRow = 7; // The row with "ID NO.", "FIRST NAME", etc.
        $dataStartRow = 8; // First participant data row
        $totalRow = $dataStartRow + $participantCount; // TOTAL row
        $lastColumn = 'T'; // We have 20 columns: A(ID NO.) to T(OTHERS)

        return [
            // Title row (row 1) - training name might be long, span multiple columns
            'A1:E1' => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],

            // Batch number row (row 2) - span multiple columns for consistency
            'A2:E2' => [
                'font' => ['size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],

            // Venue row (row 3) - label and value, span multiple columns
            'A3:E3' => [
                'font' => ['size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],

            // Dates row (row 4) - label and value, span multiple columns
            'A4:E4' => [
                'font' => ['size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],

            // Facilitator row (row 5) - label and value, span multiple columns
            'A5:E5' => [
                'font' => ['size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],

            // HEADER ROW (row 7) - exact range A to T
            'A7:' . $lastColumn . '7' => [
                'font' => ['bold' => true, 'size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],

            // Data rows - exact range A to T
            'A' . $dataStartRow . ':' . $lastColumn . ($totalRow - 1) => [
                'font' => ['size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],

            // Total row - exact range A to T
            'A' . $totalRow . ':' . $lastColumn . $totalRow => [
                'font' => ['bold' => true, 'size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ],

            // Left-align text columns in data rows (A-F are text columns)
            'A' . $dataStartRow . ':F' . ($totalRow - 1) => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ],
        ];
    }

    public function title(): string
    {
        return strtoupper($this->training->name);
    }
}
