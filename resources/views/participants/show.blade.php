<x-layouts.app :title="$participant->full_name">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('participants.index') }}" 
                   class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Participants') }}
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ $participant->full_name }}</h1>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Participant Profile') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('participants.edit', $participant) }}" 
                   class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-medium text-white transition-colors">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('Edit') }}
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $trainingStats['total_trainings'] }}</div>
                <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Total Trainings') }}</div>
            </div>
            <div class="rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $trainingStats['completed'] }}</div>
                <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Completed') }}</div>
            </div>
            <div class="rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $trainingStats['enrolled'] }}</div>
                <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Enrolled') }}</div>
            </div>
            <div class="rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $trainingStats['did_not_complete'] }}</div>
                <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Did Not Complete') }}</div>
            </div>
            <div class="rounded-xl border border-neutral-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $trainingStats['certificates'] }}</div>
                <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Certificates') }}</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Participant Details -->
            <div class="lg:col-span-1">
                <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 mb-4">{{ __('Personal Information') }}</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('ID Number') }}</dt>
                            <dd class="mt-1 text-sm text-neutral-900 dark:text-neutral-100">
                                {{ $participant->id_no ?: __('Not provided') }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Full Name') }}</dt>
                            <dd class="mt-1 text-lg font-medium text-neutral-900 dark:text-neutral-100">
                                {{ $participant->full_name }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Sex') }}</dt>
                            <dd class="mt-1">
                                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $participant->sex === 'male' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : 'bg-pink-100 text-pink-800 dark:bg-pink-900/20 dark:text-pink-400' }}">
                                    {{ ucfirst($participant->sex) }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Participant Type') }}</dt>
                            <dd class="mt-1">
                                @if($participant->participant_type)
                                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400">
                                        {{ $participant->participant_type }}
                                    </span>
                                @else
                                    <span class="text-sm text-neutral-500 dark:text-neutral-400 italic">{{ __('Not specified') }}</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Agency/Organization') }}</dt>
                            <dd class="mt-1 text-sm text-neutral-900 dark:text-neutral-100">
                                {{ $participant->agency_organization ?: __('Not provided') }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Position/Designation') }}</dt>
                            <dd class="mt-1 text-sm text-neutral-900 dark:text-neutral-100">
                                {{ $participant->position_designation ?: __('Not provided') }}
                            </dd>
                        </div>
                        
                        @if($participant->contact_number || $participant->email_address)
                        <div>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Contact Information') }}</dt>
                            <dd class="mt-1 text-sm text-neutral-900 dark:text-neutral-100">
                                @if($participant->contact_number)
                                    <div>{{ $participant->contact_number }}</div>
                                @endif
                                @if($participant->email_address)
                                    <div>{{ $participant->email_address }}</div>
                                @endif
                            </dd>
                        </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Vulnerable Groups') }}</dt>
                            <dd class="mt-1">
                                @if (!empty($participant->vulnerable_groups) && is_array($participant->vulnerable_groups) && count($participant->vulnerable_groups) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($participant->vulnerable_groups as $group)
                                            @if (!empty(trim($group)))
                                                <span class="inline-flex px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400 rounded-full">
                                                    {{ $group }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm text-neutral-500 dark:text-neutral-400 italic">{{ __('None specified') }}</span>
                                @endif
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Training History -->
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
                    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-700">
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">{{ __('Training History') }}</h2>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('All trainings this participant has enrolled in') }}</p>
                    </div>
                    
                    @if($trainings->count() > 0)
                        <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @foreach($trainings as $training)
                                <div class="p-6">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="font-medium text-neutral-900 dark:text-neutral-100">
                                                    <a href="{{ route('trainings.show', $training) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $training->name }}
                                                    </a>
                                                </h3>
                                                
                                                @switch($training->pivot->completion_status)
                                                    @case('completed')
                                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            <svg class="mr-1 size-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ __('Completed') }}
                                                        </span>
                                                        @break
                                                    @case('did_not_complete')
                                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            <svg class="mr-1 size-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ __('Did Not Complete') }}
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            <svg class="mr-1 size-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ __('Enrolled') }}
                                                        </span>
                                                @endswitch
                                                
                                                @if($training->pivot->certificate)
                                                    <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                        <svg class="mr-1 size-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                        </svg>
                                                        {{ __('Certificate') }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">
                                                <div><strong>{{ __('Organized by:') }}</strong> {{ $training->organized_by }}</div>
                                                @if($training->venue)
                                                    <div><strong>{{ __('Venue:') }}</strong> {{ $training->venue }}</div>
                                                @endif
                                            </div>
                                            
                                            <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                                <div><strong>{{ __('Training Dates:') }}</strong>
                                                    @foreach($training->dates as $date)
                                                        <span class="inline-block mr-2">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span>
                                                    @endforeach
                                                </div>
                                                <div><strong>{{ __('Enrolled:') }}</strong> {{ $training->pivot->created_at->format('M d, Y') }}</div>
                                                @if($training->pivot->completed_at)
                                                    <div><strong>{{ __('Completed:') }}</strong> {{ \Carbon\Carbon::parse($training->pivot->completed_at)->format('M d, Y') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination Links -->
                        @if($trainings->hasPages())
                            <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-700">
                                {{ $trainings->links() }}
                            </div>
                        @endif
                    @else
                        <div class="px-6 py-12 text-center">
                            <svg class="mx-auto size-12 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-neutral-900 dark:text-neutral-100">{{ __('No Training History') }}</h3>
                            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                                {{ __('This participant has not been enrolled in any trainings yet.') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
