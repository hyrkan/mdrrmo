<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        
        <!-- Filters Section -->
        <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">{{ __('Filters & Date Range') }}</h3>
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        {{ __('Date Range') }}
                    </label>
                    <select name="date_range" class="rounded border-0 bg-neutral-50 px-3 py-2 text-sm ring-1 ring-inset ring-neutral-300 dark:bg-neutral-800 dark:ring-neutral-600">
                        <option value="7" {{ $dateRange === '7' ? 'selected' : '' }}>{{ __('Last 7 days') }}</option>
                        <option value="30" {{ $dateRange === '30' ? 'selected' : '' }}>{{ __('Last 30 days') }}</option>
                        <option value="90" {{ $dateRange === '90' ? 'selected' : '' }}>{{ __('Last 90 days') }}</option>
                        <option value="365" {{ $dateRange === '365' ? 'selected' : '' }}>{{ __('Last year') }}</option>
                        <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>{{ __('All time') }}</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                        {{ __('Training') }}
                    </label>
                    <select name="training_filter" class="rounded border-0 bg-neutral-50 px-3 py-2 text-sm ring-1 ring-inset ring-neutral-300 dark:bg-neutral-800 dark:ring-neutral-600 focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('All Trainings') }}</option>
                        @foreach($trainings as $training)
                            <option value="{{ $training->id }}" {{ $trainingFilter == $training->id ? 'selected' : '' }}>
                                {{ Str::limit($training->name, 50) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
                    {{ __('Apply Filters') }}
                </button>
                
                <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 text-sm rounded transition-colors">
                    {{ __('Reset') }}
                </a>
            </form>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-4">
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center">
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Total Trainings') }}</h3>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ number_format($totalTrainings) }}</p>
                        @if($performanceSummary['has_large_dataset'])
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">{{ __('Showing top :count in charts', ['count' => $performanceSummary['total_trainings_shown']]) }}</p>
                        @endif
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center">
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Total Participants') }}</h3>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ number_format($totalParticipants) }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center">
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Completed') }}</h3>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ number_format($completedParticipants) }}</p>
                        @if($totalParticipants > 0)
                            <p class="text-sm text-green-600 dark:text-green-400">
                                {{ round(($completedParticipants / $totalParticipants) * 100, 1) }}% completion rate
                            </p>
                        @endif
                    </div>
                    <div class="p-3 rounded-full bg-emerald-100 dark:bg-emerald-900">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center">
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Certificates Issued') }}</h3>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ number_format($certificatesIssued) }}</p>
                        @if($completedParticipants > 0)
                            <p class="text-sm text-purple-600 dark:text-purple-400">
                                {{ round(($certificatesIssued / $completedParticipants) * 100, 1) }}% of completed
                            </p>
                        @endif
                    </div>
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid gap-6 md:grid-cols-2">
            
            <!-- Gender Distribution Chart -->
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">{{ __('Gender Distribution (Completed)') }}</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="genderChart"></canvas>
                </div>
                <div class="mt-4 flex justify-center space-x-4">
                    @foreach($genderStats as $gender => $count)
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-2 {{ $gender === 'Male' ? 'bg-blue-500' : ($gender === 'Female' ? 'bg-pink-500' : 'bg-gray-500') }}"></div>
                            <span class="text-sm text-neutral-600 dark:text-neutral-400">{{ $gender }}: {{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Training Completion Rates -->
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">{{ __('Training Completion Rates') }}</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="trainingCompletionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- More Charts -->
        <div class="grid gap-6 md:grid-cols-2">
            
            <!-- Organization Participation -->
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">{{ __('Top Organizations by Participation') }}</h3>
                    @if($performanceSummary['has_large_dataset'])
                        <span class="text-xs text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900 px-2 py-1 rounded">
                            {{ __('Top :count', ['count' => $performanceSummary['total_organizations_shown']]) }}
                        </span>
                    @endif
                </div>
                <div class="space-y-4">
                    @foreach($organizationStats->take(12) as $org)
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100 truncate" title="{{ $org['full_organization'] ?? $org['organization'] }}">
                                    {{ $org['organization'] }}
                                </p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $org['completed'] }}/{{ $org['total'] }} completed ({{ $org['completion_rate'] }}%)
                                </p>
                            </div>
                            <div class="flex items-center ml-4">
                                <div class="w-20 bg-neutral-200 dark:bg-neutral-700 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($org['completion_rate'], 100) }}%"></div>
                                </div>
                                <span class="ml-2 text-sm font-medium text-neutral-900 dark:text-neutral-100 min-w-[3rem] text-right">
                                    {{ $org['total'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Monthly Trends -->
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">{{ __('Training Creation Trends (Last 12 Months)') }}</h3>
                <div class="relative" style="height: 250px;">
                    <canvas id="monthlyTrendsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Vulnerable Groups & Recent Activities -->
        <div class="grid gap-6 md:grid-cols-2">
            
            <!-- Vulnerable Groups -->
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">{{ __('Vulnerable Groups Participation') }}</h3>
                @if($vulnerableGroupStats->count() > 0)
                    <div class="space-y-3">
                        @foreach($vulnerableGroupStats as $group)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ $group['group'] }}</span>
                                <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ $group['count'] }} participants</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('No vulnerable groups data available') }}</p>
                @endif
            </div>
            
            <!-- Recent Activities -->
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">{{ __('Recent Activities') }}</h3>
                <div class="space-y-4">
                    @foreach($recentActivities->take(8) as $activity)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            </div>
                            <div class="ml-3 min-w-0 flex-1">
                                <p class="text-sm text-neutral-900 dark:text-neutral-100">{{ $activity['title'] }}</p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $activity['date']->diffForHumans() }} • {{ $activity['participants_count'] }} participants
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Performance Summary for Large Datasets -->
        @if($performanceSummary['has_large_dataset'])
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-700 dark:bg-amber-900/20">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">{{ __('Large Dataset Detected') }}</h3>
                    <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                        <p>{{ __('You have :total trainings in your system. For optimal performance, charts show top performers only.', ['total' => number_format($totalTrainings)]) }}</p>
                        <ul class="mt-2 list-disc list-inside space-y-1 text-xs">
                            <li>{{ __('Training charts: Top :count by participation', ['count' => $performanceSummary['total_trainings_shown']]) }}</li>
                            <li>{{ __('Organization charts: Top :count by participation', ['count' => $performanceSummary['total_organizations_shown']]) }}</li>
                            <li>{{ __('Data range: :range', ['range' => $performanceSummary['data_range']]) }}</li>
                            <li>{{ __('Last updated: :time', ['time' => $performanceSummary['last_updated']]) }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Training Performance Table -->
        <div class="rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">{{ __('Training Performance Summary') }}</h3>
                    <div class="text-xs text-neutral-500 dark:text-neutral-400">
                        @if($performanceSummary['has_training_pagination'])
                            {{ __('Showing :shown of :total trainings', [
                                'shown' => number_format($performanceSummary['total_trainings_shown']), 
                                'total' => number_format($performanceSummary['total_trainings_available'])
                            ]) }}
                        @elseif(!request('date_range') && !request('training_filter') && !request('organization_filter'))
                            {{ __('Showing 5 latest trainings (of :total total)', ['total' => number_format($performanceSummary['total_trainings_available'])]) }}
                        @else
                            {{ __(':total trainings found', ['total' => number_format($performanceSummary['total_trainings_available'])]) }}
                        @endif
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs uppercase text-neutral-500 dark:text-neutral-400 bg-neutral-50 dark:bg-neutral-700">
                            <tr>
                                <th class="px-4 py-3 text-left">{{ __('Training') }}</th>
                                <th class="px-4 py-3 text-center">{{ __('Total Participants') }}</th>
                                <th class="px-4 py-3 text-center">{{ __('Completed') }}</th>
                                <th class="px-4 py-3 text-center">{{ __('Completion Rate') }}</th>
                                <th class="px-4 py-3 text-center">{{ __('Progress') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-600">
                            @php
                                $displayTrainings = $performanceSummary['has_training_pagination'] ? $trainingStatsPaginated : $trainingStats;
                            @endphp
                            @foreach($displayTrainings as $training)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700">
                                    <td class="px-4 py-3 font-medium text-neutral-900 dark:text-neutral-100" title="{{ $training['full_name'] ?? $training['name'] }}">
                                        {{ $training['name'] }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-neutral-600 dark:text-neutral-400">
                                        {{ number_format($training['total_participants']) }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-neutral-600 dark:text-neutral-400">
                                        {{ number_format($training['completed_participants']) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $training['completion_rate'] >= 80 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                               ($training['completion_rate'] >= 60 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                            {{ $training['completion_rate'] }}%
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $training['completion_rate'] >= 80 ? 'bg-green-600' : ($training['completion_rate'] >= 60 ? 'bg-yellow-600' : 'bg-red-600') }}" 
                                                 style="width: {{ min($training['completion_rate'], 100) }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($performanceSummary['has_training_pagination'] && $trainingStatsPaginated->hasPages())
                    <div class="border-t border-neutral-200 dark:border-neutral-700 mt-4 pt-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-neutral-700 dark:text-neutral-300">
                                {{ __('Showing :from to :to of :total results', [
                                    'from' => $trainingStatsPaginated->firstItem() ?? 0,
                                    'to' => $trainingStatsPaginated->lastItem() ?? 0,
                                    'total' => $trainingStatsPaginated->total()
                                ]) }}
                            </div>
                            <div class="flex space-x-1">
                                @if ($trainingStatsPaginated->onFirstPage())
                                    <span class="px-3 py-1 text-sm text-neutral-400 dark:text-neutral-600 cursor-not-allowed">{{ __('Previous') }}</span>
                                @else
                                    <a href="{{ $trainingStatsPaginated->previousPageUrl() }}" 
                                       class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 rounded">
                                        {{ __('Previous') }}
                                    </a>
                                @endif

                                @foreach ($trainingStatsPaginated->getUrlRange(1, $trainingStatsPaginated->lastPage()) as $page => $url)
                                    @if ($page == $trainingStatsPaginated->currentPage())
                                        <span class="px-3 py-1 text-sm bg-blue-600 text-white rounded">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" 
                                           class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 rounded">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                @if ($trainingStatsPaginated->hasMorePages())
                                    <a href="{{ $trainingStatsPaginated->nextPageUrl() }}" 
                                       class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 rounded">
                                        {{ __('Next') }}
                                    </a>
                                @else
                                    <span class="px-3 py-1 text-sm text-neutral-400 dark:text-neutral-600 cursor-not-allowed">{{ __('Next') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Simple refresh mechanism for dashboard chart reliability
        (function() {
            const dashboardKey = 'dashboard_last_visit';
            const refreshInterval = 300000; // 5 minutes
            const lastVisit = localStorage.getItem(dashboardKey);
            const now = Date.now();
            
            // Auto-refresh if coming from another page and it's been a while
            if (document.referrer && !document.referrer.includes('/dashboard')) {
                // Coming from another page, refresh if last visit was more than 5 minutes ago
                if (!lastVisit || (now - parseInt(lastVisit)) > refreshInterval) {
                    localStorage.setItem(dashboardKey, now.toString());
                    if (!window.location.search.includes('refreshed=1')) {
                        const separator = window.location.search ? '&' : '?';
                        window.location.href = window.location.href + separator + 'refreshed=1';
                        return;
                    }
                }
            }
            
            // Update last visit timestamp
            localStorage.setItem(dashboardKey, now.toString());
        })();        // Store chart instances globally
        let genderChart = null;
        let trainingCompletionChart = null;
        let monthlyTrendsChart = null;

        function initializeCharts() {
            try {
                // Clean up any existing charts
                if (genderChart) {
                    genderChart.destroy();
                    genderChart = null;
                }
                if (trainingCompletionChart) {
                    trainingCompletionChart.destroy();
                    trainingCompletionChart = null;
                }
                if (monthlyTrendsChart) {
                    monthlyTrendsChart.destroy();
                    monthlyTrendsChart = null;
                }

                // Check if canvas elements exist
                const genderCanvas = document.getElementById('genderChart');
                const trainingCanvas = document.getElementById('trainingCompletionChart');
                const monthlyCanvas = document.getElementById('monthlyTrendsChart');

                if (!genderCanvas || !trainingCanvas || !monthlyCanvas) {
                    console.warn('Chart canvas elements not found');
                    return;
                }

                const genderData = @json($genderStats);
                const trainingData = @json($trainingStats);
                const monthlyData = @json($monthlyStats);

                // Gender Distribution Chart
                const genderCtx = genderCanvas.getContext('2d');
                genderChart = new Chart(genderCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(genderData),
                        datasets: [{
                            data: Object.values(genderData),
                            backgroundColor: [
                                '#3B82F6', // Blue for Male
                                '#EC4899', // Pink for Female  
                                '#6B7280'  // Gray for Unknown
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                // Training Completion Chart
                const trainingCtx = trainingCanvas.getContext('2d');
                trainingCompletionChart = new Chart(trainingCtx, {
                    type: 'bar',
                    data: {
                        labels: trainingData.map(t => t.name),
                        datasets: [
                            {
                                label: 'Completed',
                                data: trainingData.map(t => t.completed_participants),
                                backgroundColor: '#10B981',
                                borderRadius: 4
                            },
                            {
                                label: 'Remaining',
                                data: trainingData.map(t => t.total_participants - t.completed_participants),
                                backgroundColor: '#E5E7EB',
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            x: {
                                stacked: true,
                                beginAtZero: true
                            },
                            y: {
                                stacked: true
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        const index = context[0].dataIndex;
                                        return trainingData[index].full_name || trainingData[index].name;
                                    }
                                }
                            }
                        }
                    }
                });

                // Monthly Trends Chart
                const monthlyCtx = monthlyCanvas.getContext('2d');
                monthlyTrendsChart = new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: monthlyData.map(m => m.month),
                        datasets: [{
                            label: 'Trainings Created',
                            data: monthlyData.map(m => m.trainings_created),
                            borderColor: '#3B82F6',
                            backgroundColor: '#3B82F6',
                            tension: 0.1,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                console.log('Charts initialized successfully');

            } catch (error) {
                console.error('Error initializing charts:', error);
            }
        }

        // Initialize charts when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        // Force refresh when returning to dashboard to ensure charts load
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Page was loaded from browser cache (back/forward navigation)
                console.log('Refreshing dashboard for proper chart display');
                window.location.reload();
            }
        });
        
    </script>
</x-layouts.app>
