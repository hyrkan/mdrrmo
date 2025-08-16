<x-layouts.app :title="__('Training Participants')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ __('Manage Participants') }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Select participants to enroll in: :training', ['training' => $training->name]) }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('trainings.enrolled-participants', $training) }}" 
                   class="inline-flex items-center gap-2 rounded-lg bg-green-600 hover:bg-green-700 px-4 py-2 text-sm font-medium text-white transition-colors">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ __('View Participants') }}
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

        <!-- Filter and Form -->
        <div class="max-w-6xl rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <form action="{{ route('trainings.participants.update', $training) }}" method="POST" id="participantsForm">
                @csrf

                <!-- Organization Filter -->
                <div class="mb-6">
                    <label for="organization_filter" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                        {{ __('Filter by Organization') }}
                    </label>
                    <select id="organization_filter" 
                            class="w-full max-w-md rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm">
                        <option value="all">{{ __('All Organizations') }}</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org }}">{{ $org }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Participants List -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100">{{ __('Participants') }}</h3>
                        <div class="flex gap-2">
                            <button type="button" id="selectAll" 
                                    class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                {{ __('Select All') }}
                            </button>
                            <span class="text-neutral-400">|</span>
                            <button type="button" id="deselectAll" 
                                    class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                {{ __('Deselect All') }}
                            </button>
                        </div>
                    </div>
                    
                    <div id="participants-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="flex items-center justify-center h-32 text-neutral-500 dark:text-neutral-400">
                            {{ __('Select an organization to view participants') }}
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                    <a href="{{ route('trainings.show', $training) }}" 
                       class="px-6 py-3 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 font-semibold rounded-lg transition-colors">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors">
                        {{ __('Update Participants') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const organizationFilter = document.getElementById('organization_filter');
            const participantsContainer = document.getElementById('participants-container');
            const selectAllBtn = document.getElementById('selectAll');
            const deselectAllBtn = document.getElementById('deselectAll');
            const trainingId = {{ $training->id }};

            function loadParticipants() {
                const organization = organizationFilter.value;
                
                participantsContainer.innerHTML = '<div class="flex items-center justify-center h-32 text-neutral-500 dark:text-neutral-400">Loading...</div>';

                fetch(`{{ route('api.participants.by-organization') }}?organization=${organization}&training_id=${trainingId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.participants.length === 0) {
                            participantsContainer.innerHTML = '<div class="flex items-center justify-center h-32 text-neutral-500 dark:text-neutral-400">No participants found for this organization</div>';
                            return;
                        }

                        participantsContainer.innerHTML = data.participants.map(participant => `
                            <div class="flex items-start space-x-3 p-4 border border-neutral-200 dark:border-neutral-600 rounded-lg bg-neutral-50 dark:bg-neutral-700">
                                <input type="checkbox" 
                                       id="participant_${participant.id}" 
                                       name="participant_ids[]" 
                                       value="${participant.id}"
                                       ${participant.is_enrolled ? 'checked' : ''}
                                       class="mt-1 rounded border-neutral-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500">
                                <div class="flex-1 min-w-0">
                                    <label for="participant_${participant.id}" class="block text-sm font-medium text-neutral-900 dark:text-neutral-100 cursor-pointer">
                                        ${participant.full_name}
                                    </label>
                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">${participant.agency_organization || 'No organization'}</p>
                                    ${participant.position_designation ? `<p class="text-xs text-neutral-400 dark:text-neutral-500">${participant.position_designation}</p>` : ''}
                                </div>
                            </div>
                        `).join('');
                    })
                    .catch(error => {
                        console.error('Error loading participants:', error);
                        participantsContainer.innerHTML = '<div class="flex items-center justify-center h-32 text-red-500">Error loading participants</div>';
                    });
            }

            function selectAll() {
                const checkboxes = participantsContainer.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => checkbox.checked = true);
            }

            function deselectAll() {
                const checkboxes = participantsContainer.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => checkbox.checked = false);
            }

            // Event listeners
            organizationFilter.addEventListener('change', loadParticipants);
            selectAllBtn.addEventListener('click', selectAll);
            deselectAllBtn.addEventListener('click', deselectAll);

            // Load all participants initially
            loadParticipants();
        });
    </script>
</x-layouts.app>
