<x-layouts.app :title="__('Edit Participant')">
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
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ __('Edit Participant') }}</h1>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Update participant information') }}</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
            <form action="{{ route('participants.update', $participant) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ID Number -->
                    <div class="md:col-span-2">
                        <label for="id_no" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            {{ __('ID Number') }} <span class="text-neutral-400">({{ __('Optional') }})</span>
                        </label>
                        <input type="text" 
                               id="id_no" 
                               name="id_no" 
                               value="{{ old('id_no', $participant->id_no) }}" 
                               placeholder="{{ __('Enter ID number') }}" 
                               class="w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm">
                        @error('id_no')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            {{ __('First Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name', $participant->first_name) }}" 
                               placeholder="{{ __('Enter first name') }}" 
                               class="w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm" 
                               required>
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            {{ __('Middle Name') }} <span class="text-neutral-400">({{ __('Optional') }})</span>
                        </label>
                        <input type="text" 
                               id="middle_name" 
                               name="middle_name" 
                               value="{{ old('middle_name', $participant->middle_name) }}" 
                               placeholder="{{ __('Enter middle name (optional)') }}" 
                               class="w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm">
                        @error('middle_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            {{ __('Last Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name', $participant->last_name) }}" 
                               placeholder="{{ __('Enter last name') }}" 
                               class="w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm" 
                               required>
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sex -->
                    <div>
                        <label for="sex" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            {{ __('Sex') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="sex" 
                                name="sex" 
                                class="w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm" 
                                required>
                            <option value="">{{ __('Select sex') }}</option>
                            <option value="male" {{ old('sex', $participant->sex) === 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                            <option value="female" {{ old('sex', $participant->sex) === 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                        </select>
                        @error('sex')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Agency/Organization -->
                    <div class="md:col-span-2">
                        <label for="agency_organization" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            {{ __('Agency/Organization') }} <span class="text-neutral-400">({{ __('Optional') }})</span>
                        </label>
                        <input type="text" 
                               id="agency_organization" 
                               name="agency_organization" 
                               value="{{ old('agency_organization', $participant->agency_organization) }}" 
                               placeholder="{{ __('Enter agency or organization (optional)') }}" 
                               class="w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm">
                        @error('agency_organization')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Participant Type -->
                    <div class="md:col-span-2">
                        <label for="participant_type" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            {{ __('Participant Type') }} <span class="text-neutral-400">({{ __('Optional') }})</span>
                        </label>
                        <select id="participant_type" 
                                name="participant_type" 
                                class="w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm">
                            <option value="">{{ __('Select participant type') }}</option>
                            @foreach(\App\Models\Participant::PARTICIPANT_TYPES as $type)
                                <option value="{{ $type }}" {{ old('participant_type', $participant->participant_type) === $type ? 'selected' : '' }}>{{ $type === 'BRGY' ? 'BARANGAY' : $type }}</option>
                            @endforeach
                        </select>
                        @error('participant_type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position/Designation -->
                    <div class="md:col-span-2">
                        <label for="position_designation" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            {{ __('Position/Designation') }} <span class="text-neutral-400">({{ __('Optional') }})</span>
                        </label>
                        <input type="text" 
                               id="position_designation" 
                               name="position_designation" 
                               value="{{ old('position_designation', $participant->position_designation) }}" 
                               placeholder="{{ __('Enter position or designation (optional)') }}" 
                               class="w-full rounded-lg border-0 bg-neutral-50 px-4 py-3 text-neutral-900 ring-1 ring-inset ring-neutral-300 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-neutral-800 dark:text-white dark:ring-neutral-600 dark:focus:bg-neutral-700 dark:focus:ring-blue-500 sm:text-sm">
                        @error('position_designation')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vulnerable Groups -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            {{ __('Vulnerable Groups') }} <span class="text-neutral-400">({{ __('Optional') }})</span>
                        </label>
                        <div class="mb-3 text-sm text-neutral-600 dark:text-neutral-400">
                            {{ __('Select all applicable vulnerable groups') }}
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-neutral-50 dark:bg-neutral-800">
                            @php
                                $vulnerableGroupOptions = [
                                    'Persons with Disabilities (PWDs)',
                                    'Senior Citizens',
                                    'Pregnant'
                                ];
                                $selectedGroups = old('vulnerable_groups', $participant->vulnerable_groups ?? []);
                            @endphp
                            
                            @foreach($vulnerableGroupOptions as $group)
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="vulnerable_group_{{ $loop->index }}" 
                                       name="vulnerable_groups[]" 
                                       value="{{ $group }}"
                                       {{ in_array($group, $selectedGroups) ? 'checked' : '' }}
                                       class="rounded border-neutral-300 dark:border-neutral-600 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-neutral-800">
                                <label for="vulnerable_group_{{ $loop->index }}" class="ml-3 text-sm text-neutral-700 dark:text-neutral-300 cursor-pointer">
                                    {{ $group }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('vulnerable_groups')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @error('vulnerable_groups.*')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                    <a href="{{ route('participants.show', $participant) }}" 
                       class="px-6 py-3 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-neutral-700 dark:text-neutral-300 font-semibold rounded-lg transition-colors">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors">
                        {{ __('Update Participant') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
