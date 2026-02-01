<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Participant::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('middle_name', 'LIKE', "%{$search}%")
                  ->orWhere('id_no', 'LIKE', "%{$search}%")
                  ->orWhere('agency_organization', 'LIKE', "%{$search}%")
                  ->orWhere('position_designation', 'LIKE', "%{$search}%");
            });
        }

        // Apply sorting
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');

        // Toggle direction for the same column
        $nextDirection = $direction === 'asc' ? 'desc' : 'asc';

        if ($sort === 'name') {
            $query->orderBy('last_name', $direction)
                  ->orderBy('first_name', $direction);
        } else {
            $allowedSorts = ['id_no', 'agency_organization', 'position_designation', 'sex', 'created_at'];
            if (in_array($sort, $allowedSorts)) {
                $query->orderBy($sort, $direction);
            } else {
                $query->orderBy('last_name', 'asc')
                      ->orderBy('first_name', 'asc');
            }
        }

        $participants = $query->paginate(15)->withQueryString();

        return view('participants.index', compact('participants', 'sort', 'direction', 'nextDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('participants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Normalize participant type before validation
        if ($request->has('participant_type')) {
            $request->merge([
                'participant_type' => \App\Models\Participant::normalizeParticipantType($request->input('participant_type'))
            ]);
        }

        $validated = $request->validate([
            'id_no' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'agency_organization' => 'nullable|string|max:255',
            'position_designation' => 'nullable|string|max:255',
            'sex' => 'required|in:male,female',
            'participant_type' => 'nullable|string|in:' . implode(',', \App\Models\Participant::PARTICIPANT_TYPES),
            'vulnerable_groups' => 'nullable|array',
            'vulnerable_groups.*' => 'string|in:Persons with Disabilities (PWDs),Senior Citizens,Pregnant',
        ]);

        Participant::create($validated);

        return redirect()->route('participants.index')
            ->with('success', 'Participant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant, Request $request)
    {
        // Paginate trainings with pivot data
        $trainings = $participant->trainings()
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->appends($request->query());

        // Get all trainings for statistics (without pagination)
        $allTrainings = $participant->trainings;
        
        // Get training statistics
        $trainingStats = [
            'total_trainings' => $allTrainings->count(),
            'completed' => $allTrainings->where('pivot.completion_status', 'completed')->count(),
            'enrolled' => $allTrainings->where('pivot.completion_status', 'enrolled')->count(),
            'did_not_complete' => $allTrainings->where('pivot.completion_status', 'did_not_complete')->count(),
            'certificates' => $allTrainings->where('pivot.certificate', true)->count(),
        ];

        return view('participants.show', compact('participant', 'trainings', 'trainingStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participant $participant)
    {
        return view('participants.edit', compact('participant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Participant $participant)
    {
        // Normalize participant type before validation
        if ($request->has('participant_type')) {
            $request->merge([
                'participant_type' => \App\Models\Participant::normalizeParticipantType($request->input('participant_type'))
            ]);
        }

        $validated = $request->validate([
            'id_no' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'agency_organization' => 'nullable|string|max:255',
            'position_designation' => 'nullable|string|max:255',
            'sex' => 'required|in:male,female',
            'vulnerable_groups' => 'nullable|array',
            'participant_type' => 'nullable|string|in:' . implode(',', \App\Models\Participant::PARTICIPANT_TYPES),
            'vulnerable_groups.*' => 'string|in:Persons with Disabilities (PWDs),Senior Citizens,Pregnant',
        ]);

        $participant->update($validated);

        return redirect()->route('participants.index')
            ->with('success', 'Participant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        $participant->delete();

        return redirect()->route('participants.index')
            ->with('success', 'Participant deleted successfully.');
    }
}
