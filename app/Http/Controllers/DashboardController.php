<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $dateRange = $request->get('date_range', '30'); // Default last 30 days
        $trainingFilter = $request->get('training_filter');
        $organizationFilter = $request->get('organization_filter');

        // Calculate date range
        $startDate = match($dateRange) {
            '7' => Carbon::now()->subDays(7),
            '30' => Carbon::now()->subDays(30),
            '90' => Carbon::now()->subDays(90),
            '365' => Carbon::now()->subDays(365),
            'all' => null,
            default => Carbon::now()->subDays(30)
        };

        // Base query for trainings
        $trainingsQuery = Training::query();
        if ($startDate) {
            $trainingsQuery->where('created_at', '>=', $startDate);
        }
        if ($trainingFilter) {
            $trainingsQuery->where('id', $trainingFilter);
        }

        // Base query for participants
        $participantsQuery = DB::table('participants')
            ->join('training_participant', 'participants.id', '=', 'training_participant.participant_id')
            ->join('trainings', 'training_participant.training_id', '=', 'trainings.id');

        if ($startDate) {
            $participantsQuery->where('trainings.created_at', '>=', $startDate);
        }
        if ($trainingFilter) {
            $participantsQuery->where('trainings.id', $trainingFilter);
        }
        if ($organizationFilter) {
            $participantsQuery->where('participants.agency_organization', 'like', "%{$organizationFilter}%");
        }

        // Key metrics
        $totalTrainings = $trainingsQuery->count();
        $totalParticipants = $participantsQuery->count();
        $completedParticipants = (clone $participantsQuery)->where('training_participant.completion_status', 'completed')->count();
        $certificatesIssued = (clone $participantsQuery)->where('training_participant.certificate', true)->count();

        // Gender distribution for completed trainings
        $genderStats = (clone $participantsQuery)
            ->where('training_participant.completion_status', 'completed')
            ->select(
                DB::raw('participants.sex as gender'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('participants.sex')
            ->get()
            ->mapWithKeys(function ($item) {
                return [ucfirst($item->gender ?: 'Unknown') => $item->count];
            });

        // Training completion rates (with pagination for large datasets)
        $trainingStatsQuery = Training::select('trainings.name', 'trainings.id', 'trainings.created_at')
            ->withCount([
                'participants as total_participants',
                'participants as completed_participants' => function ($query) {
                    $query->where('training_participant.completion_status', 'completed');
                }
            ])
            ->when($startDate, function ($query) use ($startDate) {
                return $query->where('created_at', '>=', $startDate);
            })
            ->when($trainingFilter, function ($query) use ($trainingFilter) {
                return $query->where('id', $trainingFilter);
            })
            ->whereHas('participants');

        // If no filters are applied, show the 5 latest trainings by creation date
        // Otherwise, order by total participants
        if (!$startDate && !$trainingFilter && !$organizationFilter) {
            $trainingStatsQuery->orderByDesc('created_at')->limit(5);
        } else {
            $trainingStatsQuery->orderByDesc('total_participants');
        }

        // For large datasets, use pagination; otherwise show all
        $totalTrainingCount = $trainingStatsQuery->count();
        $perPage = 20; // Show 20 trainings per page
        
        // If showing default 5 latest trainings, don't use pagination
        if (!$startDate && !$trainingFilter && !$organizationFilter) {
            $trainingStats = $trainingStatsQuery->get()->map(function ($training) {
                $completion_rate = $training->total_participants > 0 
                    ? round(($training->completed_participants / $training->total_participants) * 100, 1) 
                    : 0;
                
                return [
                    'name' => strlen($training->name) > 30 ? substr($training->name, 0, 27) . '...' : $training->name,
                    'full_name' => $training->name,
                    'total_participants' => $training->total_participants,
                    'completed_participants' => $training->completed_participants,
                    'completion_rate' => $completion_rate
                ];
            });
            $trainingStatsPaginated = null;
        } elseif ($totalTrainingCount > 50) {
            // Use pagination for large datasets
            $trainingStatsPaginated = $trainingStatsQuery->paginate($perPage, ['*'], 'training_page');
            $trainingStats = $trainingStatsPaginated->getCollection()->map(function ($training) {
                $completion_rate = $training->total_participants > 0 
                    ? round(($training->completed_participants / $training->total_participants) * 100, 1) 
                    : 0;
                
                return [
                    'name' => strlen($training->name) > 30 ? substr($training->name, 0, 27) . '...' : $training->name,
                    'full_name' => $training->name,
                    'total_participants' => $training->total_participants,
                    'completed_participants' => $training->completed_participants,
                    'completion_rate' => $completion_rate
                ];
            });
            
            // Replace the collection with mapped data
            $trainingStatsPaginated->setCollection($trainingStats);
        } else {
            // Show all trainings without pagination for smaller datasets
            $trainingStats = $trainingStatsQuery->get()->map(function ($training) {
                $completion_rate = $training->total_participants > 0 
                    ? round(($training->completed_participants / $training->total_participants) * 100, 1) 
                    : 0;
                
                return [
                    'name' => strlen($training->name) > 30 ? substr($training->name, 0, 27) . '...' : $training->name,
                    'full_name' => $training->name,
                    'total_participants' => $training->total_participants,
                    'completed_participants' => $training->completed_participants,
                    'completion_rate' => $completion_rate
                ];
            });
            $trainingStatsPaginated = null;
        }

        // Organization participation (optimized for large datasets)
        $organizationStats = (clone $participantsQuery)
            ->select(
                DB::raw('participants.agency_organization as organization'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN training_participant.completion_status = "completed" THEN 1 ELSE 0 END) as completed')
            )
            ->groupBy('participants.agency_organization')
            ->having('total', '>', 0)
            ->orderByDesc('total')
            ->limit(12) // Show top 12 organizations
            ->get()
            ->map(function ($org) {
                $orgName = $org->organization ?: 'Not specified';
                return [
                    'organization' => strlen($orgName) > 35 ? substr($orgName, 0, 32) . '...' : $orgName,
                    'full_organization' => $orgName,
                    'total' => $org->total,
                    'completed' => $org->completed,
                    'completion_rate' => $org->total > 0 ? round(($org->completed / $org->total) * 100, 1) : 0
                ];
            });

        // Monthly training trends (optimized with better date handling)
        $monthlyStats = DB::table('trainings')
            ->select(
                DB::raw('strftime("%Y-%m", created_at) as month'),
                DB::raw('COUNT(*) as trainings_created')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($stat) {
                return [
                    'month' => Carbon::createFromFormat('Y-m', $stat->month)->format('M Y'),
                    'trainings_created' => (int) $stat->trainings_created
                ];
            });

        // Fill missing months with zero values for complete chart
        $completeMonthlyStats = collect();
        for ($i = 11; $i >= 0; $i--) {
            $monthKey = Carbon::now()->subMonths($i)->format('M Y');
            $existing = $monthlyStats->firstWhere('month', $monthKey);
            $completeMonthlyStats->push([
                'month' => $monthKey,
                'trainings_created' => $existing ? $existing['trainings_created'] : 0
            ]);
        }
        $monthlyStats = $completeMonthlyStats;

        // Vulnerable groups analysis
        $vulnerableGroupStats = Participant::select('vulnerable_groups')
            ->whereNotNull('vulnerable_groups')
            ->where('vulnerable_groups', '!=', '[]')
            ->get()
            ->flatMap(function ($participant) {
                return is_array($participant->vulnerable_groups) ? $participant->vulnerable_groups : [];
            })
            ->countBy()
            ->map(function ($count, $group) {
                return [
                    'group' => $group,
                    'count' => $count
                ];
            })
            ->values();

        // Recent activities (optimized for performance)
        $recentActivities = Training::select('id', 'name', 'created_at')
            ->withCount('participants')
            ->latest()
            ->limit(8) // Reduced for better performance
            ->get()
            ->map(function ($training) {
                return [
                    'type' => 'training_created',
                    'title' => strlen($training->name) > 40 ? substr($training->name, 0, 37) . '...' : $training->name,
                    'full_title' => "New training: {$training->name}",
                    'date' => $training->created_at,
                    'participants_count' => $training->participants_count
                ];
            });

        // Performance summary for large datasets
        $performanceSummary = [
            'total_trainings_shown' => $trainingStatsPaginated ? $trainingStatsPaginated->count() : $trainingStats->count(),
            'total_trainings_available' => $totalTrainingCount,
            'total_organizations_shown' => min($organizationStats->count(), 12),
            'data_range' => $dateRange === 'all' ? 'All time' : "Last {$dateRange} days",
            'last_updated' => Carbon::now()->format('M d, Y H:i'),
            'has_large_dataset' => $totalTrainings > 100,
            'has_training_pagination' => $trainingStatsPaginated !== null,
            'training_per_page' => $perPage
        ];

        // Get filter options (optimized)
        $trainings = Training::select('id', 'name')
            ->orderBy('name')
            ->get(); // Remove limit and date filter for dropdown options
            
        $organizations = Participant::select('agency_organization')
            ->distinct()
            ->whereNotNull('agency_organization')
            ->where('agency_organization', '!=', '')
            ->orderBy('agency_organization')
            ->limit(100) // Limit for performance
            ->pluck('agency_organization');

        return view('dashboard', compact(
            'totalTrainings',
            'totalParticipants', 
            'completedParticipants',
            'certificatesIssued',
            'genderStats',
            'trainingStats',
            'trainingStatsPaginated',
            'organizationStats',
            'monthlyStats',
            'vulnerableGroupStats',
            'recentActivities',
            'trainings',
            'organizations',
            'dateRange',
            'trainingFilter',
            'organizationFilter',
            'performanceSummary'
        ));
    }
}
