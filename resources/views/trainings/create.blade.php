<x-layouts.app :title="__('Create Training')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ __('Create New Training') }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Add a new training session or program') }}</p>
            </div>
            <a href="{{ route('trainings.index') }}" 
               class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back to Trainings') }}
            </a>
        </div>

        <!-- Create Form -->
        <div class="max-w-4xl rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <form action="{{ route('trainings.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Training Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">{{ __('Training Name') }}</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="{{ __('Enter training name') }}" 
                           class="block w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:placeholder:text-neutral-500 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm sm:leading-6" 
                           required>
                    @error('name')
                        <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Training Classification -->
                <div>
                    <label for="training_classification" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">{{ __('Training Classification') }}</label>
                    <select id="training_classification" 
                            name="training_classification" 
                            class="block w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm sm:leading-6" 
                            required>
                        <option value="">{{ __('Select classification') }}</option>
                        <option value="external" {{ old('training_classification') == 'external' ? 'selected' : '' }}>{{ __('External') }}</option>
                        <option value="organized" {{ old('training_classification') == 'organized' ? 'selected' : '' }}>{{ __('Organized') }}</option>
                        <option value="drills" {{ old('training_classification') == 'drills' ? 'selected' : '' }}>{{ __('Drills') }}</option>
                    </select>
                    @error('training_classification')
                        <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Training Dates -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">{{ __('Training Dates') }}</label>
                    <!-- Update the help text around line 59 -->
                    <div class="text-sm text-neutral-600 dark:text-neutral-400 mb-3">
                        {{ __('Select training dates. Additional dates should be equal to or after the first date.') }}
                    </div>
                    <div id="dates-container" class="space-y-3">
                        <div class="flex items-center gap-3 date-field">
                            <input type="date" 
                                   name="dates[]" 
                                   value="{{ old('dates.0') }}"
                                   onchange="updateMinDatesForFollowing(this)"
                                   class="flex-1 rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm sm:leading-6" 
                                   required>
                            <button type="button" 
                                    onclick="addDateField()" 
                                    class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @error('dates')
                        <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                    @enderror
                    @error('dates.*')
                        <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Other Fields -->
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="organized_by" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">{{ __('Organized By') }}</label>
                        <input type="text" 
                               id="organized_by" 
                               name="organized_by" 
                               value="{{ old('organized_by') }}" 
                               placeholder="{{ __('Enter organizing body') }}" 
                               class="block w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:placeholder:text-neutral-500 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm sm:leading-6" 
                               required>
                        @error('organized_by')
                            <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="requesting_party" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">{{ __('Requesting Party') }}</label>
                        <input type="text" 
                               id="requesting_party" 
                               name="requesting_party" 
                               value="{{ old('requesting_party') }}" 
                               placeholder="{{ __('Enter requesting party (optional)') }}" 
                               class="block w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:placeholder:text-neutral-500 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm sm:leading-6">
                        @error('requesting_party')
                            <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="venue" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">{{ __('Venue') }}</label>
                        <input type="text" 
                               id="venue" 
                               name="venue" 
                               value="{{ old('venue') }}" 
                               placeholder="{{ __('Enter training venue') }}" 
                               class="block w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:placeholder:text-neutral-500 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm sm:leading-6">
                        @error('venue')
                            <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="course_facilitator" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">{{ __('Course Facilitator') }}</label>
                        <input type="text" 
                               id="course_facilitator" 
                               name="course_facilitator" 
                               value="{{ old('course_facilitator') }}" 
                               placeholder="{{ __('Enter facilitator name (optional)') }}" 
                               class="block w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:placeholder:text-neutral-500 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm sm:leading-6">
                        @error('course_facilitator')
                            <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="course_monitor" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">{{ __('Course Monitor') }}</label>
                        <input type="text" 
                               id="course_monitor" 
                               name="course_monitor" 
                               value="{{ old('course_monitor') }}" 
                               placeholder="{{ __('Enter course monitor name') }}" 
                               class="block w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 placeholder:text-neutral-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:placeholder:text-neutral-500 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm sm:leading-6">
                        @error('course_monitor')
                            <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">{{ __('Instructors') }}</label>
                        <div id="instructors-container" class="space-y-3">
                            <div class="flex items-center gap-3 instructor-field">
                                <input type="text" 
                                       name="instructor[]" 
                                       placeholder="{{ __('Enter instructor name') }}" 
                                       class="flex-1 rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm sm:leading-6">
                                <button type="button" 
                                        onclick="addInstructorField()" 
                                        class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @error('instructor')
                            <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                        @error('instructor.*')
                            <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 border-t border-neutral-200 pt-6 dark:border-neutral-700">
                    <a href="{{ route('trainings.index') }}" 
                       class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 bg-white px-4 py-2.5 text-sm font-semibold text-neutral-700 shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:ring-neutral-400">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('Create Training') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let dateIndex = {{ count(old('dates', [1])) }};
    
        function updateMinDatesForFollowing(firstDateInput) {
            const firstDate = firstDateInput.value;
            const dateInputs = document.querySelectorAll('input[name="dates[]"](:not(:first-of-type)');
            
            dateInputs.forEach(input => {
                if (firstDate) {
                    input.min = firstDate;
                } else {
                    input.removeAttribute('min');
                }
            });
        }
    
        function addDateField() {
            const container = document.getElementById('dates-container');
            const firstDateInput = document.querySelector('input[name="dates[]"]');
            const minDate = firstDateInput && firstDateInput.value ? firstDateInput.value : '';
            
            const newField = document.createElement('div');
            newField.className = 'flex items-center gap-3 date-field';
            newField.innerHTML = `
                <input type="date" 
                       name="dates[]" 
                       ${minDate ? `min="${minDate}"` : ''}
                       class="flex-1 rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm sm:leading-6" 
                       required>
                <button type="button" 
                        onclick="removeDateField(this)" 
                        class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(newField);
            dateIndex++;
        }
    
        function removeDateField(button) {
            button.closest('.date-field').remove();
        }

        function addInstructorField() {
            const container = document.getElementById('instructors-container');
            const newField = document.createElement('div');
            newField.className = 'flex items-center gap-3 instructor-field';
            newField.innerHTML = `
                <input type="text" 
                       name="instructor[]" 
                       placeholder="{{ __('Enter instructor name') }}" 
                       class="flex-1 rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm sm:leading-6">
                <button type="button" 
                        onclick="removeInstructorField(this)" 
                        class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(newField);
        }

        function removeInstructorField(button) {
            button.closest('.instructor-field').remove();
        }
    </script>
</x-layouts.app>
