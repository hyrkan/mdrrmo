<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $participants = Participant::latest()->paginate(10);
        return view('participants.index', compact('participants'));
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
        $validated = $request->validate([
            'id_no' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'agency_organization' => 'nullable|string|max:255',
            'position_designation' => 'nullable|string|max:255',
            'sex' => 'required|in:male,female',
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
        $validated = $request->validate([
            'id_no' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'agency_organization' => 'nullable|string|max:255',
            'position_designation' => 'nullable|string|max:255',
            'sex' => 'required|in:male,female',
            'vulnerable_groups' => 'nullable|array',
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
