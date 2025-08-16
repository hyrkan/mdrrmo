<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Exports\EnrolledParticipantsExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Training::query();
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('organized_by', 'LIKE', "%{$search}%")
                  ->orWhere('requesting_party', 'LIKE', "%{$search}%")
                  ->orWhere('venue', 'LIKE', "%{$search}%")
                  ->orWhere('course_facilitator', 'LIKE', "%{$search}%")
                  ->orWhere('instructor', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply date filter
        if ($request->filled('date_filter')) {
            $dateFilter = $request->get('date_filter');
            $dateFilterType = $request->get('date_filter_type', 'exact');
            
            $query->where(function($q) use ($dateFilter, $dateFilterType) {
                // Get all trainings and filter by dates in PHP since JSON queries can be tricky
                $q->whereRaw('1=1'); // This will be filtered in the collection later
            });
        }
        
        // Get initial results
        $trainingsQuery = $query->latest();
        
        // Apply date filtering after getting results (for more reliable JSON array filtering)
        if ($request->filled('date_filter')) {
            $dateFilter = $request->get('date_filter');
            $dateFilterType = $request->get('date_filter_type', 'exact');
            
            $allTrainings = $trainingsQuery->get();
            $filteredTrainings = $allTrainings->filter(function($training) use ($dateFilter, $dateFilterType) {
                if (!$training->dates || !is_array($training->dates)) {
                    return false;
                }
                
                foreach ($training->dates as $trainingDate) {
                    switch ($dateFilterType) {
                        case 'exact':
                            if ($trainingDate === $dateFilter) {
                                return true;
                            }
                            break;
                        case 'from':
                            if ($trainingDate >= $dateFilter) {
                                return true;
                            }
                            break;
                        case 'to':
                            if ($trainingDate <= $dateFilter) {
                                return true;
                            }
                            break;
                    }
                }
                return false;
            });
            
            // Convert back to paginated result
            $currentPage = request()->get('page', 1);
            $perPage = 10;
            $trainings = new \Illuminate\Pagination\LengthAwarePaginator(
                $filteredTrainings->forPage($currentPage, $perPage),
                $filteredTrainings->count(),
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
        } else {
            $trainings = $trainingsQuery->paginate(10);
        }
        
        // Preserve query parameters in pagination links
        $trainings->appends($request->query());
        
        return view('trainings.index', compact('trainings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trainings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dates' => 'required|array|min:1',
            'dates.*' => 'required|date|after_or_equal:today',
            'organized_by' => 'required|string|max:255',
            'requesting_party' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'course_facilitator' => 'nullable|string|max:255',
            'instructor' => 'nullable|string|max:255',
        ]);

        // Sort dates to ensure chronological order
        $validated['dates'] = collect($validated['dates'])->sort()->values()->toArray();

        Training::create($validated);

        return redirect()->route('trainings.index')
            ->with('success', 'Training created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Training $training)
    {
        return view('trainings.show', compact('training'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Training $training)
    {
        return view('trainings.edit', compact('training'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dates' => 'required|array|min:1',
            'dates.*' => 'required|date|after_or_equal:today',
            'organized_by' => 'required|string|max:255',
            'requesting_party' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'course_facilitator' => 'nullable|string|max:255',
            'instructor' => 'nullable|string|max:255',
        ]);

        // Sort dates to ensure chronological order
        $validated['dates'] = collect($validated['dates'])->sort()->values()->toArray();

        $training->update($validated);

        return redirect()->route('trainings.index')
            ->with('success', 'Training updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Training $training)
    {
        $training->delete();

        return redirect()->route('trainings.index')
            ->with('success', 'Training deleted successfully.');
    }

    /**
     * Show participants management page for a training
     */
    public function participants(Training $training)
    {
        // Get all unique organizations from participants
        $organizations = \App\Models\Participant::whereNotNull('agency_organization')
            ->where('agency_organization', '!=', '')
            ->distinct()
            ->pluck('agency_organization')
            ->sort();

        // Get currently enrolled participants
        $enrolledParticipants = $training->participants()->pluck('participant_id')->toArray();

        return view('trainings.participants', compact('training', 'organizations', 'enrolledParticipants'));
    }

    /**
     * Show enrolled participants for a training (view only)
     */
    public function enrolledParticipants(Request $request, Training $training)
    {
        $query = $training->participants();
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('middle_name', 'LIKE', "%{$search}%")
                  ->orWhere('agency_organization', 'LIKE', "%{$search}%")
                  ->orWhere('position_designation', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($request->filled('status_filter')) {
            $statusFilter = $request->get('status_filter');
            if ($statusFilter === 'pending') {
                $query->wherePivot('completion_status', 'enrolled');
            } elseif ($statusFilter === 'completed') {
                $query->wherePivot('completion_status', 'completed');
            } elseif ($statusFilter === 'ready_for_certificate') {
                $query->wherePivot('completion_status', 'completed')
                     ->wherePivot('certificate', false);
            } elseif ($statusFilter === 'certificate_issued') {
                $query->wherePivot('certificate', true);
            } elseif ($statusFilter === 'did_not_complete') {
                $query->wherePivot('completion_status', 'did_not_complete');
            }
        }

        // Determine if any filters are applied
        $hasFilters = $request->filled('search') || $request->filled('status_filter');
        
        if ($hasFilters) {
            // If filters are applied, use pagination to handle potentially large result sets
            $enrolledParticipants = $query
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->paginate(10);
                
            // Preserve query parameters in pagination links
            $enrolledParticipants->appends(request()->query());
        } else {
            // If no filters, show all enrolled participants without pagination
            $enrolledParticipants = $query
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
        }

        return view('trainings.enrolled-participants', compact('training', 'enrolledParticipants'));
    }

    /**
     * Export enrolled participants to Excel
     */
    public function exportEnrolledParticipants(Request $request, Training $training)
    {
        $search = $request->get('search');
        $statusFilter = $request->get('status_filter');

        $filename = 'enrolled-participants-' . $training->slug . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new EnrolledParticipantsExport($training, $search, $statusFilter),
            $filename
        );
    }

    /**
     * Get participants by organization (AJAX)
     */
    public function getParticipantsByOrganization(Request $request)
    {
        $organization = $request->get('organization');
        $trainingId = $request->get('training_id');

        $query = \App\Models\Participant::query();

        if ($organization && $organization !== 'all') {
            $query->where('agency_organization', $organization);
        }

        $participants = $query->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Get currently enrolled participants for this training
        $enrolledIds = [];
        if ($trainingId) {
            $training = Training::find($trainingId);
            if ($training) {
                $enrolledIds = $training->participants()->pluck('participant_id')->toArray();
            }
        }

        return response()->json([
            'participants' => $participants->map(function ($participant) use ($enrolledIds) {
                return [
                    'id' => $participant->id,
                    'full_name' => $participant->full_name,
                    'agency_organization' => $participant->agency_organization,
                    'position_designation' => $participant->position_designation,
                    'is_enrolled' => in_array($participant->id, $enrolledIds),
                ];
            })
        ]);
    }

    /**
     * Update participants for a training
     */
    public function updateParticipants(Request $request, Training $training)
    {
        $request->validate([
            'participant_ids' => 'nullable|array',
            'participant_ids.*' => 'exists:participants,id',
        ]);

        $participantIds = $request->input('participant_ids', []);

        // Sync participants (this will add new ones and remove unchecked ones)
        $training->participants()->sync($participantIds);

        return redirect()->route('trainings.participants', $training)
            ->with('success', 'Training participants updated successfully.');
    }

    /**
     * Update completion status and certificate for a participant
     */
    public function updateParticipantStatus(Request $request, Training $training, $participantId)
    {
        $request->validate([
            'completion_status' => 'required|in:enrolled,completed,did_not_complete',
            'certificate' => 'nullable|boolean',
            'certificate_serial' => 'nullable|string|max:255',
            'issued_by' => 'nullable|string|max:255',
        ]);

        $completionStatus = $request->input('completion_status');
        $certificate = $request->input('certificate');
        $certificateSerial = $request->input('certificate_serial');
        $issuedBy = $request->input('issued_by');

        // Get current participant data to preserve existing certificate info
        $currentParticipant = $training->participants()->wherePivot('participant_id', $participantId)->first();
        $currentCertificate = $currentParticipant ? $currentParticipant->pivot->certificate : false;
        $currentSerial = $currentParticipant ? $currentParticipant->pivot->certificate_serial : null;
        $currentIssuedBy = $currentParticipant ? $currentParticipant->pivot->issued_by : null;

        // Use provided values or fall back to current values
        $certificate = $certificate !== null ? $certificate : $currentCertificate;
        $certificateSerial = $certificateSerial !== null ? $certificateSerial : $currentSerial;
        $issuedBy = $issuedBy !== null ? $issuedBy : $currentIssuedBy;

        // Only award certificate if certificate serial and issuer are provided
        if ($completionStatus === 'completed' && $certificateSerial && $issuedBy) {
            $certificate = true;
        }
        // If status is did_not_complete, remove certificate
        elseif ($completionStatus === 'did_not_complete') {
            $certificate = false;
            $certificateSerial = null;
            $issuedBy = null;
        }
        // For other cases, preserve existing certificate data

        $pivotData = [
            'completion_status' => $completionStatus,
            'certificate' => $certificate,
            'certificate_serial' => $certificateSerial,
            'issued_by' => $issuedBy,
        ];

        // Set completed_at timestamp if status is completed
        if ($completionStatus === 'completed') {
            $pivotData['completed_at'] = now();
            // Set certificate issued timestamp if certificate is awarded
            if ($certificate) {
                $pivotData['certificate_issued_at'] = now();
            }
        } else {
            $pivotData['completed_at'] = null;
            $pivotData['certificate_issued_at'] = null;
        }

        $training->participants()->updateExistingPivot($participantId, $pivotData);

        return response()->json([
            'success' => true,
            'message' => 'Participant status updated successfully.',
        ]);
    }

    /**
     * Bulk assign certificate serials to completed participants
     */
    public function bulkAssignCertificateSerials(Request $request, Training $training)
    {
        $request->validate([
            'certificate_serials' => 'required|array|min:1',
            'issued_by' => 'required|string|max:255',
        ]);

        $certificateSerials = $request->input('certificate_serials');
        $issuedBy = $request->input('issued_by');
        $issuedAt = now();

        $updatedCount = 0;
        $errors = [];

        // Check for duplicate serials in the request
        $serialCounts = array_count_values(array_filter($certificateSerials));
        $duplicateSerials = array_filter($serialCounts, function($count) { return $count > 1; });
        
        if (!empty($duplicateSerials)) {
            return response()->json([
                'success' => false,
                'message' => 'Duplicate certificate serials detected: ' . implode(', ', array_keys($duplicateSerials)),
                'errors' => ['Duplicate serials are not allowed.'],
            ]);
        }

        // Check for existing serials in the database
        foreach ($certificateSerials as $participantId => $serial) {
            if (empty($serial)) {
                continue;
            }

            // Check if this serial already exists in any training
            $existingSerial = DB::table('training_participant')
                ->where('certificate_serial', $serial)
                ->first();
            
            if ($existingSerial) {
                return response()->json([
                    'success' => false,
                    'message' => "Certificate serial '{$serial}' is already assigned to another participant.",
                    'errors' => ['Duplicate serial number detected.'],
                ]);
            }
        }

        foreach ($certificateSerials as $participantId => $serial) {
            if (empty($serial)) {
                continue;
            }

            // Check if participant is enrolled in this training and completed
            $participant = $training->participants()->wherePivot('participant_id', $participantId)->first();
            
            if (!$participant) {
                $errors[] = "Participant ID {$participantId} is not enrolled in this training.";
                continue;
            }

            if ($participant->pivot->completion_status !== 'completed') {
                $errors[] = "Participant ID {$participantId} has not completed the training.";
                continue;
            }

            // Update the pivot record
            $training->participants()->updateExistingPivot($participantId, [
                'certificate' => true,
                'certificate_serial' => $serial,
                'issued_by' => $issuedBy,
                'certificate_issued_at' => $issuedAt,
            ]);

            $updatedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully assigned {$updatedCount} certificate serial(s).",
            'assigned' => $updatedCount,
            'errors' => $errors,
        ]);
    }

    /**
     * Bulk update completion status for multiple participants
     */
    public function bulkUpdateParticipantStatus(Request $request, Training $training)
    {
        $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'exists:participants,id',
            'completion_status' => 'required|in:enrolled,completed,did_not_complete',
        ]);

        $participantIds = $request->input('participant_ids');
        $completionStatus = $request->input('completion_status');

        $pivotData = [
            'completion_status' => $completionStatus,
        ];

        // Clear certificate data if status is did_not_complete
        if ($completionStatus === 'did_not_complete') {
            $pivotData['certificate'] = false;
            $pivotData['certificate_serial'] = null;
            $pivotData['issued_by'] = null;
            $pivotData['certificate_issued_at'] = null;
        }
        // Don't automatically set certificate status for other statuses
        // Leave existing certificate data unchanged unless explicitly clearing it

        // Set completed_at timestamp if status is completed
        if ($completionStatus === 'completed') {
            $pivotData['completed_at'] = now();
        } else {
            $pivotData['completed_at'] = null;
        }

        // Update each participant's pivot record
        foreach ($participantIds as $participantId) {
            $training->participants()->updateExistingPivot($participantId, $pivotData);
        }

        $count = count($participantIds);
        $statusLabel = ucfirst(str_replace('_', ' ', $completionStatus));

        return response()->json([
            'success' => true,
            'message' => "Updated {$count} participant(s) to {$statusLabel} status.",
        ]);
    }
}
