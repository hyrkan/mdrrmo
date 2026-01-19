<x-layouts.app :title="__('Training Participants')">

    <style>
        .dt-search { display: none; }
        .dt-length { margin-bottom: 1rem; }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 1rem;
            margin-left: 0.5rem;
            border-radius: 0.375rem;
            border: 1px solid #e5e7eb;
            background: white;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #2563eb !important;
            color: white !important;
            border-color: #2563eb;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: #1f2937;
            border-color: #374151;
            color: #d1d5db;
        }
        table.dataTable thead th {
            border-bottom: 1px solid #e5e7eb !important;
        }
        .dark table.dataTable thead th {
            border-bottom: 1px solid #374151 !important;
        }
    </style>

    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ __('Training Participants') }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Participants enrolled in: :training', ['training' => $training->name]) }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('trainings.participants', $training) }}" 
                   class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-medium text-white transition-colors">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('Manage Participants') }}
                </a>
                <a href="#" id="exportBtn"
                   class="inline-flex items-center gap-2 rounded-lg bg-green-600 hover:bg-green-700 px-4 py-2 text-sm font-medium text-white transition-colors">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('Export Excel') }}
                </a>
                <a href="{{ route('trainings.show', $training) }}" 
                   class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Training') }}
                </a>
            </div>
        </div>

        <!-- Training Info Card -->
        <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium text-neutral-700 dark:text-neutral-300">{{ __('Training Dates:') }}</span>
                    <div class="mt-1 text-neutral-600 dark:text-neutral-400">
                        @foreach($training->dates as $date)
                            <div>{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <span class="font-medium text-neutral-700 dark:text-neutral-300">{{ __('Organized by:') }}</span>
                    <p class="mt-1 text-neutral-600 dark:text-neutral-400">{{ $training->organized_by }}</p>
                </div>
                <div>
                    <span class="font-medium text-neutral-700 dark:text-neutral-300">{{ __('Total Participants:') }}</span>
                    <p class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $enrolledParticipants->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Participants List -->
        <div class="rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            @if($training->participants()->count() > 0)
                <!-- Summary Stats -->
                <div class="border-b border-neutral-200 p-6 dark:border-neutral-700">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">
                                {{ __('Enrolled Participants') }}
                            </h3>
                            @if(request('search') || request('status_filter'))
                                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                                    {{ __('Showing :count of :total participants', ['count' => $enrolledParticipants->count(), 'total' => $training->participants()->count()]) }}
                                    @if(request('search'))
                                        {{ __('matching ":search"', ['search' => request('search')]) }}
                                    @endif
                                </p>
                            @endif
                        </div>
                        <div class="flex gap-6 text-sm">
                            @php
                                // Calculate stats from all participants, not just filtered ones
                                $allParticipants = $training->participants;
                                $orgStats = $allParticipants->groupBy('agency_organization')->map->count()->sortDesc();
                                $statusStats = $allParticipants->groupBy('pivot.completion_status');
                                $completedCount = $statusStats->get('completed', collect())->count();
                                $enrolledCount = $statusStats->get('enrolled', collect())->count();
                                $didNotCompleteCount = $statusStats->get('did_not_complete', collect())->count();
                                $totalWithCertificates = $allParticipants->where('pivot.certificate', true)->count();
                            @endphp
                            <div class="text-center">
                                <div class="font-semibold text-neutral-900 dark:text-neutral-100">{{ $orgStats->count() }}</div>
                                <div class="text-neutral-500 dark:text-neutral-400">{{ __('Organizations') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="font-semibold text-green-600 dark:text-green-400">{{ $completedCount }}</div>
                                <div class="text-neutral-500 dark:text-neutral-400">{{ __('Completed') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="font-semibold text-blue-600 dark:text-blue-400">{{ $enrolledCount }}</div>
                                <div class="text-neutral-500 dark:text-neutral-400">{{ __('Enrolled') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="font-semibold text-red-600 dark:text-red-400">{{ $didNotCompleteCount }}</div>
                                <div class="text-neutral-500 dark:text-neutral-400">{{ __('Did Not Complete') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="font-semibold text-purple-600 dark:text-purple-400">{{ $totalWithCertificates }}</div>
                                <div class="text-neutral-500 dark:text-neutral-400">{{ __('Certificates') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search and Filter -->
                    <div class="space-y-3 mb-4">
                        <!-- Second row: Participant search and status filter -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                            <div class="flex-1 w-full">
                                <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                    {{ __('Participant Search') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           id="searchParticipants" 
                                           placeholder="{{ __('Search participants by name or organization...') }}"
                                           value="{{ request('search') }}"
                                           class="block w-full pl-9 pr-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md leading-5 bg-white dark:bg-neutral-700 placeholder-neutral-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                            </div>
                            <div class="flex gap-2 items-end">
                                <div>
                                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                                        {{ __('Status') }}
                                    </label>
                                    <select id="statusFilter" class="rounded border-0 bg-neutral-50 px-3 py-2 text-sm ring-1 ring-inset ring-neutral-300 dark:bg-neutral-800 dark:ring-neutral-600">
                                        <option value="">{{ __('All Status') }}</option>
                                        <option value="pending" {{ request('status_filter') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                        <option value="completed" {{ request('status_filter') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                        <option value="ready_for_certificate" {{ request('status_filter') === 'ready_for_certificate' ? 'selected' : '' }}>{{ __('Ready for Certificate') }}</option>
                                        <option value="certificate_issued" {{ request('status_filter') === 'certificate_issued' ? 'selected' : '' }}>{{ __('Certificate Issued') }}</option>
                                        <option value="did_not_complete" {{ request('status_filter') === 'did_not_complete' ? 'selected' : '' }}>{{ __('Did Not Complete') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="button" id="clearFilters" class="px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100 border border-neutral-300 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700">
                                        {{ __('Clear') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($enrolledParticipants->count() > 0)
                        <!-- Bulk Actions -->
                        <div class="flex items-center gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="selectAllParticipants" class="rounded border-neutral-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-neutral-700 dark:text-neutral-300">{{ __('Select All') }}</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <select id="bulkActionSelect" class="rounded border-0 bg-neutral-50 px-3 py-1 text-sm ring-1 ring-inset ring-neutral-300 dark:bg-neutral-800 dark:ring-neutral-600">
                                    <option value="">{{ __('Bulk Action') }}</option>
                                    <option value="completed">{{ __('Mark as Completed') }}</option>
                                    <option value="did_not_complete">{{ __('Mark as Did Not Complete') }}</option>
                                    <option value="enrolled">{{ __('Mark as Enrolled') }}</option>
                                    <option value="assign_certificates">{{ __('Assign Certificate Serials') }}</option>
                                </select>
                                <button type="button" id="bulkActionBtn" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors disabled:bg-neutral-400" disabled>
                                    {{ __('Apply') }}
                                </button>
                            </div>
                        </div>
                    @endif
                </div>



                <!-- Participants Table -->
                <div class="overflow-x-auto p-6">
                    <table id="participantsTable" class="w-full">

                        <thead class="bg-neutral-50 dark:bg-neutral-700">
                            <tr>
                                <th class="px-3 py-3 text-left">
                                    <input type="checkbox" id="selectAllHeader" class="rounded border-neutral-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                                    {{ __('Name') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                                    {{ __('Organization') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                                    {{ __('Position') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                                    {{ __('Status') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                                    {{ __('Certificate') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                                    {{ __('Actions') }}
                                </th>

                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-600 dark:bg-neutral-800">
                            @foreach($enrolledParticipants as $participant)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700" data-participant-id="{{ $participant->id }}">
                                    <td class="px-3 py-4">
                                        <input type="checkbox" class="participant-checkbox rounded border-neutral-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500" value="{{ $participant->id }}">
                                    </td>
                                <td class="px-6 py-4 break-words" data-order="{{ $participant->last_name }}, {{ $participant->first_name }}">
                                    <div class="font-medium text-neutral-900 dark:text-neutral-100 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        <a href="{{ route('participants.show', $participant) }}">
                                            {{ $participant->formal_name }}
                                        </a>
                                    </div>

                                    @if($participant->vulnerable_groups)
                                        <div class="mt-1">
                                            @foreach($participant->vulnerable_groups as $group)
                                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200 mr-1">
                                                    {{ $group }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100 break-words">
                                    {{ $participant->agency_organization ?: __('Not specified') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-neutral-600 dark:text-neutral-300 break-words">
                                    {{ $participant->position_designation ?: __('Not specified') }}
                                </td>
                                    <td class="px-6 py-4 text-left">
                                        @if($participant->pivot->completion_status === 'enrolled')
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ __('Enrolled') }}
                                            </span>
                                        @elseif($participant->pivot->completion_status === 'completed')
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ __('Completed') }}
                                            </span>
                                        @elseif($participant->pivot->completion_status === 'did_not_complete')
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">
                                                {{ __('Did Not Complete') }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-left">

                                        @if($participant->pivot->certificate)
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <svg class="mr-1 size-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ __('Issued') }}
                                            </span>
                                            @if($participant->pivot->certificate_serial)
                                                <div class="text-xs mt-1 text-green-600 dark:text-green-300">
                                                    {{ __('Serial:') }} {{ $participant->pivot->certificate_serial }}
                                                </div>
                                            @endif
                                            @if($participant->pivot->issued_by)
                                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    {{ __('By:') }} {{ $participant->pivot->issued_by }}
                                                </div>
                                            @endif
                                            @if($participant->pivot->certificate_issued_at)
                                                <div class="text-xs text-green-600 dark:text-green-300">{{ \Carbon\Carbon::parse($participant->pivot->certificate_issued_at)->format('M d, Y') }}</div>
                                            @endif
                                        @else
                                            @if($participant->pivot->completion_status === 'did_not_complete')
                                                <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-600 dark:bg-red-900 dark:text-red-300">
                                                    {{ __('Not Awarded') }}
                                                </span>
                                            @elseif($participant->pivot->completion_status === 'completed')
                                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300">
                                                    {{ __('Ready for Certificate') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-neutral-100 px-2 py-1 text-xs font-medium text-neutral-600 dark:bg-neutral-700 dark:text-neutral-300">
                                                    {{ __('Pending') }}
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-left">
                                        <div class="flex items-center justify-start gap-2">
                                            <a href="{{ route('participants.show', $participant) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                                {{ __('View Profile') }}
                                            </a>
                                        </div>
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($enrolledParticipants->count() === 0 && (request('search') || request('status_filter')))
                    <!-- No Results Message -->
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto size-12 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-neutral-900 dark:text-neutral-100">{{ __('No participants found') }}</h3>
                        <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                            {{ __('No participants match your current search or filter criteria.') }}
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('trainings.enrolled-participants', $training) }}" 
                               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-medium text-white transition-colors">
                                {{ __('Refresh Page') }}
                            </a>
                        </div>
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto size-12 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-neutral-900 dark:text-neutral-100">{{ __('No participants enrolled') }}</h3>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                        {{ __('No participants have been enrolled in this training yet.') }}
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('trainings.participants', $training) }}" 
                           class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-medium text-white transition-colors">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            {{ __('Add Participants') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Certificate Assignment Modal -->
        <div id="certificateModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative inline-block align-bottom bg-white dark:bg-neutral-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                    <div>
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-lg leading-6 font-medium text-neutral-900 dark:text-neutral-100" id="modal-title">
                                {{ __('Assign Certificate Serials') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ __('Assign certificate serial numbers to completed participants') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <form id="certificateForm">
                            <div class="mb-4">
                                <label for="issued_by" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    {{ __('Issued By') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="issued_by" 
                                       name="issued_by" 
                                       placeholder="{{ __('e.g., Department of Health') }}" 
                                       class="w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm"
                                       required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                    {{ __('Certificate Serials') }}
                                </label>
                                <div id="certificateInputs" class="space-y-3 max-h-64 overflow-y-auto">
                                    <!-- Dynamic inputs will be added here -->
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="button" id="assignCertificatesBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                            {{ __('Assign Certificates') }}
                        </button>
                        <button type="button" id="cancelCertificateModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 dark:border-neutral-600 shadow-sm px-4 py-2 bg-white dark:bg-neutral-700 text-base font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function initializePage() {
            // Check if table exists
            const tableId = '#participantsTable';
            const participantsTable = document.querySelector(tableId);
            if (!participantsTable) return;

            // Get all needed elements at the start to avoid "before initialization" errors
            const selectAllHeader = document.getElementById('selectAllHeader');
            const selectAllParticipants = document.getElementById('selectAllParticipants');
            const bulkActionSelect = document.getElementById('bulkActionSelect');
            const bulkActionBtn = document.getElementById('bulkActionBtn');
            const trainingId = {{ $training->id }};
            
            // Certificate modal elements
            const certificateModal = document.getElementById('certificateModal');
            const cancelCertificateModal = document.getElementById('cancelCertificateModal');
            const assignCertificatesBtn = document.getElementById('assignCertificatesBtn');
            const certificateForm = document.getElementById('certificateForm');
            
            // Search and filter elements
            const searchInput = document.getElementById('searchParticipants');
            const statusFilter = document.getElementById('statusFilter');
            const clearFiltersBtn = document.getElementById('clearFilters');
            
            // Export button
            const exportBtn = document.getElementById('exportBtn');

            // Destroy existing instance if it exists to prevent "Cannot reinitialise" warning
            if ($.fn.DataTable.isDataTable(tableId)) {
                $(tableId).DataTable().destroy();
            }

            // Define syncHeaderCheckboxes before it's used in drawCallback
            function syncHeaderCheckboxes() {
                const checkboxes = document.querySelectorAll('.participant-checkbox');
                const checkedBoxes = document.querySelectorAll('.participant-checkbox:checked');
                const checkedCount = checkedBoxes.length;
                const totalCheckboxes = checkboxes.length;
                if (selectAllHeader) {
                    selectAllHeader.checked = totalCheckboxes > 0 && checkedCount === totalCheckboxes;
                    selectAllHeader.indeterminate = checkedCount > 0 && checkedCount < totalCheckboxes;
                }
                if (selectAllParticipants) {
                    selectAllParticipants.checked = totalCheckboxes > 0 && checkedCount === totalCheckboxes;
                    selectAllParticipants.indeterminate = checkedCount > 0 && checkedCount < totalCheckboxes;
                }
                if (bulkActionBtn) bulkActionBtn.disabled = checkedCount === 0 || !bulkActionSelect || !bulkActionSelect.value;
            }

            function initializeRowEvents() {
                syncHeaderCheckboxes();
            }

            // Initialize DataTable
            const table = $(tableId).DataTable({
                pageLength: 25,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                columnDefs: [
                    { orderable: false, targets: [0, 6] } // Disable sorting on checkbox and actions columns
                ],
                order: [[1, 'asc']], // Default sort by name column
                dom: '<"flex flex-col sm:flex-row justify-between items-center mb-4"l>rtip',
                drawCallback: function() {
                    // Re-initialize event listeners after table redraw
                    initializeRowEvents();
                }
            });

            // Search and filter functionality
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    table.search(this.value).draw();
                });
            }
            
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    applyFilters();
                });
            }
            
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    if (statusFilter) statusFilter.value = '';
                    applyFilters();
                });
            }
            
            function applyFilters() {
                const search = searchInput ? searchInput.value : '';
                const status = statusFilter ? statusFilter.value : '';
                const url = new URL(window.location.href);
                if (search) url.searchParams.set('search', search); else url.searchParams.delete('search');
                if (status) url.searchParams.set('status_filter', status); else url.searchParams.delete('status_filter');
                window.location.href = url.toString();
            }

            if (exportBtn) {
                exportBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const search = searchInput ? searchInput.value : '';
                    const status = statusFilter ? statusFilter.value : '';
                    const url = new URL(`{{ route('trainings.export-participants', $training) }}`);
                    if (search) url.searchParams.set('search', search);
                    if (status) url.searchParams.set('status_filter', status);
                    const originalText = exportBtn.innerHTML;
                    exportBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" ...>{{ __('Exporting...') }}</svg>`;
                    exportBtn.disabled = true;
                    window.location.href = url.toString();
                    setTimeout(() => { exportBtn.innerHTML = originalText; exportBtn.disabled = false; }, 2000);
                });
            }


            function toggleAllSelection(checked) {
                document.querySelectorAll('.participant-checkbox').forEach(cb => cb.checked = checked);
                syncHeaderCheckboxes();
            }

            document.addEventListener('change', function(e) {
                if (e.target.id === 'selectAllHeader' || e.target.id === 'selectAllParticipants') toggleAllSelection(e.target.checked);
                if (e.target.classList.contains('participant-checkbox')) syncHeaderCheckboxes();
                if (e.target.id === 'bulkActionSelect') syncHeaderCheckboxes();
            });

            if (bulkActionBtn) {
                bulkActionBtn.addEventListener('click', function() {
                    const selectedParticipants = Array.from(document.querySelectorAll('.participant-checkbox:checked')).map(cb => cb.value);
                    const selectedAction = bulkActionSelect ? bulkActionSelect.value : '';
                    if (selectedAction === 'assign_certificates') openCertificateModal();
                    else bulkUpdateParticipantStatus(selectedParticipants, selectedAction);
                });
            }

            function bulkUpdateParticipantStatus(participantIds, status) {
                bulkActionBtn.disabled = true;
                bulkActionBtn.textContent = 'Updating...';
                fetch(`/trainings/${trainingId}/participants/bulk-status`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({ participant_ids: participantIds, completion_status: status })
                })
                .then(r => r.json()).then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    } else throw new Error(data.message);
                })
                .catch(error => {
                    showNotification('Error updating: ' + error.message, 'error');
                    bulkActionBtn.disabled = false;
                    bulkActionBtn.textContent = 'Apply';
                });
            }

            function openCertificateModal() {
                const selectedIds = Array.from(document.querySelectorAll('.participant-checkbox:checked')).map(cb => cb.value);
                const participantsContainer = certificateModal.querySelector('#certificateInputs');
                participantsContainer.innerHTML = '';
                selectedIds.forEach(id => {
                    const participantRow = document.querySelector(`input[value="${id}"]`).closest('tr');
                    const nameCell = participantRow.querySelector('td:nth-child(2)');
                    const participantName = nameCell.textContent.trim();
                    const inputGroup = document.createElement('div');
                    inputGroup.className = 'mb-4';
                    inputGroup.innerHTML = `
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">${participantName}</label>
                        <input type="text" name="certificate_serials[${id}]" placeholder="Enter serial" class="certificate-serial-input w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md" required>
                        <div class="duplicate-warning hidden mt-1 text-xs text-red-600">This serial number is already used above</div>
                    `;
                    participantsContainer.appendChild(inputGroup);
                });
                certificateModal.classList.remove('hidden');
            }

            if (cancelCertificateModal) {
                cancelCertificateModal.addEventListener('click', () => certificateModal.classList.add('hidden'));
            }

            if (assignCertificatesBtn) {
                assignCertificatesBtn.addEventListener('click', function() {
                    const formData = new FormData(certificateForm);
                    const issuedBy = formData.get('issued_by');
                    const certificateSerials = {};
                    for (let [key, value] of formData.entries()) {
                        if (key.startsWith('certificate_serials[') && value.trim()) {
                            certificateSerials[key.match(/\[(\d+)\]/)[1]] = value.trim();
                        }
                    }
                    if (!issuedBy.trim()) { showNotification('Please enter the issuing organization', 'error'); return; }
                    assignCertificatesBtn.disabled = true;
                    fetch(`/trainings/${trainingId}/participants/bulk-certificates`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify({ certificate_serials: certificateSerials, issued_by: issuedBy })
                    })
                    .then(r => r.json()).then(data => {
                        if (data.success) {
                            showNotification('Success!', 'success');
                            setTimeout(() => window.location.reload(), 1000);
                        } else showNotification(data.message, 'error');
                    });
                });
            }

            function showNotification(message, type) {
                const n = document.createElement('div');
                n.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
                n.textContent = message;
                document.body.appendChild(n);
                setTimeout(() => n.remove(), 3000);
            }

            syncHeaderCheckboxes();
        }

        document.addEventListener('DOMContentLoaded', initializePage);
        document.addEventListener('livewire:navigated', initializePage);
    </script>
</x-layouts.app>

