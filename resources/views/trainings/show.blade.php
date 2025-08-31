<x-layouts.app :title="__('Training Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ $training->name }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Training Details') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('trainings.enrolled-participants', $training) }}" 
                   class="inline-flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm font-medium text-green-700 hover:bg-green-100 dark:border-green-700 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/40">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ __('View Participants') }}
                </a>
                <a href="{{ route('trainings.participants', $training) }}" 
                   class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100 dark:border-blue-700 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('Manage Participants') }}
                </a>
                <a href="{{ route('trainings.edit', $training) }}" 
                   class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('trainings.index') }}" 
                   class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
        </div>

        <!-- Training Information -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Basic Information -->
            <div class="space-y-6">
                <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                    <h3 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                        {{ __('Basic Information') }}
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Training Name') }}</dt>
                            <dd class="mt-1 text-sm text-neutral-900 dark:text-neutral-100">{{ $training->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Training Classification') }}</dt>
                            <dd class="mt-1">
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
                                    <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Not specified') }}</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Organized By') }}</dt>
                            <dd class="mt-1 text-sm text-neutral-900 dark:text-neutral-100">{{ $training->organized_by }}</dd>
                        </div>
                        @if ($training->requesting_party)
                            <div>
                                <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Requesting Party') }}</dt>
                                <dd class="mt-1 text-sm text-neutral-900 dark:text-neutral-100">{{ $training->requesting_party }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Personnel -->
                <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                    <h3 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                        {{ __('Personnel') }}
                    </h3>
                    <div class="space-y-4">
                        @if ($training->course_facilitator)
                            <div>
                                <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Course Facilitator') }}</dt>
                                <dd class="mt-1 text-sm text-neutral-900 dark:text-neutral-100">{{ $training->course_facilitator }}</dd>
                            </div>
                        @endif
                        @if ($training->instructor)
                            <div>
                                <dt class="text-sm font-medium text-neutral-500 dark:text-neutral-400">{{ __('Instructor') }}</dt>
                                <dd class="mt-1 text-sm text-neutral-900 dark:text-neutral-100">{{ $training->instructor }}</dd>
                            </div>
                        @endif
                        @if (!$training->course_facilitator && !$training->instructor)
                            <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                {{ __('No personnel information available') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Schedule & Location -->
            <div class="space-y-6">
                <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                    <h3 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                        {{ __('Schedule') }}
                    </h3>
                    <div class="space-y-2">
                        @if ($training->dates && count($training->dates) > 0)
                            @foreach ($training->dates as $date)
                                <div class="flex items-center gap-2 text-sm text-neutral-900 dark:text-neutral-100">
                                    <svg class="size-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ date('F j, Y (l)', strtotime($date)) }}
                                </div>
                            @endforeach
                        @else
                            <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                {{ __('No dates scheduled') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                    <h3 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                        {{ __('Location') }}
                    </h3>
                    <div class="flex items-center gap-2 text-sm text-neutral-900 dark:text-neutral-100">
                        <svg class="size-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $training->venue ?? __('Venue to be determined') }}
                    </div>
                </div>

                <!-- Metadata -->
                <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                    <h3 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                        {{ __('Record Information') }}
                    </h3>
                    <div class="space-y-2 text-xs text-neutral-500 dark:text-neutral-400">
                        <div>{{ __('Created: :date', ['date' => $training->created_at->format('M j, Y g:i A')]) }}</div>
                        <div>{{ __('Updated: :date', ['date' => $training->updated_at->format('M j, Y g:i A')]) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
