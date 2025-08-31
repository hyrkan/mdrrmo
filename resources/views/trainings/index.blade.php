<x-layouts.app :title="__('Trainings')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ __('Trainings') }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Manage MDRRMO training sessions and programs') }}</p>
            </div>
            <a href="{{ route('trainings.create') }}" 
               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-500 dark:bg-blue-600 dark:text-white dark:hover:bg-blue-500">
                <svg class="size-4 fill-white stroke-white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-white dark:text-white">{{ __('Add New Training') }}</span>
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300">
                <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="rounded-lg border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="flex-1 w-full">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               id="searchTraining" 
                               placeholder="{{ __('Search by training name or organizer...') }}"
                               value="{{ request('search') }}"
                               class="block w-full pl-10 pr-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md leading-5 bg-white dark:bg-neutral-700 placeholder-neutral-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
                <div class="flex gap-3">
                    <div>
                        <select id="classificationFilter" class="rounded border-0 bg-neutral-50 px-3 py-2 text-sm ring-1 ring-inset ring-neutral-300 dark:bg-neutral-800 dark:ring-neutral-600 focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('All Classifications') }}</option>
                            <option value="external" {{ request('classification_filter') === 'external' ? 'selected' : '' }}>{{ __('External') }}</option>
                            <option value="organized" {{ request('classification_filter') === 'organized' ? 'selected' : '' }}>{{ __('Organized') }}</option>
                            <option value="drills" {{ request('classification_filter') === 'drills' ? 'selected' : '' }}>{{ __('Drills') }}</option>
                        </select>
                    </div>
                    <div>
                        <input type="date" 
                               id="dateFilter" 
                               value="{{ request('date_filter') }}"
                               class="rounded border-0 bg-neutral-50 px-3 py-2 text-sm ring-1 ring-inset ring-neutral-300 dark:bg-neutral-800 dark:ring-neutral-600 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <select id="dateFilterType" class="rounded border-0 bg-neutral-50 px-3 py-2 text-sm ring-1 ring-inset ring-neutral-300 dark:bg-neutral-800 dark:ring-neutral-600">
                            <option value="exact" {{ request('date_filter_type') === 'exact' ? 'selected' : '' }}>{{ __('Exact Date') }}</option>
                            <option value="from" {{ request('date_filter_type') === 'from' ? 'selected' : '' }}>{{ __('From Date') }}</option>
                            <option value="to" {{ request('date_filter_type') === 'to' ? 'selected' : '' }}>{{ __('To Date') }}</option>
                        </select>
                    </div>
                    <div>
                        <button type="button" id="clearFilters" class="px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100 border border-neutral-300 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700">
                            {{ __('Clear') }}
                        </button>
                    </div>
                </div>
            </div>
            
            @if(request('search') || request('date_filter') || request('classification_filter'))
                <div class="mt-3 text-sm text-neutral-500 dark:text-neutral-400">
                    {{ __('Showing :count results', ['count' => $trainings->total()]) }}
                    @if(request('search'))
                        {{ __('matching ":search"', ['search' => request('search')]) }}
                    @endif
                    @if(request('classification_filter'))
                        {{ __('for :classification trainings', ['classification' => ucfirst(request('classification_filter'))]) }}
                    @endif
                    @if(request('date_filter'))
                        @php
                            $dateType = request('date_filter_type', 'exact');
                            $dateFormatted = \Carbon\Carbon::parse(request('date_filter'))->format('M d, Y');
                        @endphp
                        {{ __('with training date :type :date', [
                            'type' => $dateType === 'exact' ? 'on' : ($dateType === 'from' ? 'from' : 'to'),
                            'date' => $dateFormatted
                        ]) }}
                    @endif
                </div>
            @endif
        </div>

        <!-- Trainings Table -->
        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-neutral-50 dark:bg-neutral-800">
                        <tr>
                            <th class="border-b border-neutral-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:border-neutral-700 dark:text-neutral-400">
                                {{ __('Training Name') }}
                            </th>
                            <th class="border-b border-neutral-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:border-neutral-700 dark:text-neutral-400">
                                {{ __('Classification') }}
                            </th>
                            <th class="border-b border-neutral-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:border-neutral-700 dark:text-neutral-400">
                                {{ __('Organized By') }}
                            </th>
                            <th class="border-b border-neutral-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:border-neutral-700 dark:text-neutral-400">
                                {{ __('Dates') }}
                            </th>
                            <th class="border-b border-neutral-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:border-neutral-700 dark:text-neutral-400">
                                {{ __('Venue') }}
                            </th>
                            <th class="border-b border-neutral-200 px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:border-neutral-700 dark:text-neutral-400">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                        @forelse ($trainings as $training)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700">
                                <td class="px-6 py-4 text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                    {{ $training->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                    @if($training->training_classification)
                                        @php
                                            $classificationColors = [
                                                'external' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                                                'organized' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                                'drills' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400'
                                            ];
                                            $colorClass = $classificationColors[$training->training_classification] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colorClass }}">
                                            {{ ucfirst($training->training_classification) }}
                                        </span>
                                    @else
                                        <span class="text-neutral-500 text-xs">{{ __('Not specified') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $training->organized_by }}
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                    @if ($training->dates && count($training->dates) > 0)
                                        <div class="space-y-1">
                                            @foreach (array_slice($training->dates, 0, 2) as $date)
                                                <div class="flex items-center gap-1">
                                                    <svg class="size-3 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ date('M j, Y', strtotime($date)) }}
                                                </div>
                                            @endforeach
                                            @if (count($training->dates) > 2)
                                                <div class="text-xs text-neutral-500">
                                                    +{{ count($training->dates) - 2 }} more
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-neutral-500">{{ __('No dates set') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $training->venue ?? __('TBD') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('trainings.show', $training) }}" 
                                           class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40">
                                            <svg class="mr-1 size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ __('View') }}
                                        </a>
                                        <a href="{{ route('trainings.edit', $training) }}" 
                                           class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:hover:bg-yellow-900/40">
                                            <svg class="mr-1 size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            {{ __('Edit') }}
                                        </a>
                                        <form action="{{ route('trainings.destroy', $training) }}" method="POST" class="inline-block" 
                                              onsubmit="return confirm('{{ __('Are you sure you want to delete this training?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">
                                                <svg class="mr-1 size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        @if(request('search') || request('date_filter') || request('classification_filter'))
                                            <svg class="mx-auto size-12 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <h3 class="mt-4 text-lg font-medium text-neutral-900 dark:text-neutral-100">{{ __('No trainings found') }}</h3>
                                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">{{ __('No trainings match your search criteria.') }}</p>
                                            <div class="mt-4">
                                                <button type="button" onclick="document.getElementById('searchTraining').value=''; document.getElementById('dateFilter').value=''; document.getElementById('dateFilterType').value='exact'; document.getElementById('classificationFilter').value=''; location.reload();" 
                                                       class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-medium text-white transition-colors">
                                                    {{ __('Clear Filters') }}
                                                </button>
                                            </div>
                                        @else
                                            <h3 class="mt-4 text-lg font-medium text-neutral-900 dark:text-neutral-100">{{ __('No trainings registered') }}</h3>
                                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">{{ __('Start by creating your first training session.') }}</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if ($trainings->hasPages())
                <div class="border-t border-neutral-200 bg-white px-6 py-3 dark:border-neutral-700 dark:bg-neutral-800">
                    {{ $trainings->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchTraining');
            const dateFilter = document.getElementById('dateFilter');
            const dateFilterType = document.getElementById('dateFilterType');
            const classificationFilter = document.getElementById('classificationFilter');
            const clearFiltersBtn = document.getElementById('clearFilters');

            let searchTimeout;
            
            // Search functionality with debounce
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        applyFilters();
                    }, 500);
                });
            }
            
            // Classification filter functionality
            if (classificationFilter) {
                classificationFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }
            
            // Date filter functionality
            if (dateFilter) {
                dateFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }
            
            if (dateFilterType) {
                dateFilterType.addEventListener('change', function() {
                    applyFilters();
                });
            }
            
            // Clear filters functionality
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    if (dateFilter) dateFilter.value = '';
                    if (dateFilterType) dateFilterType.value = 'exact';
                    if (classificationFilter) classificationFilter.value = '';
                    applyFilters();
                });
            }
            
            function applyFilters() {
                const search = searchInput ? searchInput.value : '';
                const dateValue = dateFilter ? dateFilter.value : '';
                const dateType = dateFilterType ? dateFilterType.value : 'exact';
                const classification = classificationFilter ? classificationFilter.value : '';
                
                const url = new URL(window.location.href);
                
                if (search) {
                    url.searchParams.set('search', search);
                } else {
                    url.searchParams.delete('search');
                }
                
                if (classification) {
                    url.searchParams.set('classification_filter', classification);
                } else {
                    url.searchParams.delete('classification_filter');
                }
                
                if (dateValue) {
                    url.searchParams.set('date_filter', dateValue);
                    url.searchParams.set('date_filter_type', dateType);
                } else {
                    url.searchParams.delete('date_filter');
                    url.searchParams.delete('date_filter_type');
                }
                
                // Reset to first page when applying filters
                url.searchParams.delete('page');
                
                window.location.href = url.toString();
            }
        });
    </script>
</x-layouts.app>
