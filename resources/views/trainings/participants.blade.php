<x-layouts.app :title="__('Training Participants')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ __('Manage Participants') }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Select participants to enroll in: :training', ['training' => $training->name]) }}</p>
            </div>
            <div class="flex gap-3">
                <button type="button" id="addParticipantBtn"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-medium text-white transition-colors">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('Add New Participant') }}
                </button>
                <button type="button" id="uploadExcelBtn"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-sm font-medium text-white transition-colors">
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    {{ __('Upload Excel') }}
                </button>
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

        <!-- Session Messages -->
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300">
                <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-yellow-800 dark:border-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('warning') }}
                </div>
            </div>
        @endif

        <!-- Add Participant Modal -->
        <div id="addParticipantModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Backdrop -->
                <div class="fixed" aria-hidden="true"></div>
                
                <!-- Modal positioning -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <!-- Modal content -->
                <div class="relative inline-block align-bottom bg-white dark:bg-neutral-900 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-neutral-200 dark:border-neutral-700">
                    <form id="addParticipantForm">
                        @csrf
                        <!-- Modal Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h3 class="text-xl font-semibold text-white" id="modal-title">
                                {{ __('Add New Participant') }}
                            </h3>
                            <p class="text-blue-100 text-sm mt-1">{{ __('Fill in the participant details below') }}</p>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="bg-white dark:bg-neutral-900 px-6 py-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- ID Number -->
                                <div class="md:col-span-2">
                                    <label for="id_no" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                        {{ __('ID Number') }}
                                    </label>
                                    <input type="text" 
                                           name="id_no" 
                                           id="id_no" 
                                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-0 transition-all duration-200"
                                           placeholder="Enter ID number">
                                </div>
                                
                                <!-- First Name -->
                                <div>
                                    <label for="first_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                        {{ __('First Name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="first_name" 
                                           id="first_name" 
                                           required 
                                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-0 transition-all duration-200"
                                           placeholder="Enter first name">
                                </div>
                                
                                <!-- Last Name -->
                                <div>
                                    <label for="last_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                        {{ __('Last Name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="last_name" 
                                           id="last_name" 
                                           required 
                                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-0 transition-all duration-200"
                                           placeholder="Enter last name">
                                </div>
                                
                                <!-- Middle Name -->
                                <div class="md:col-span-2">
                                    <label for="middle_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                        {{ __('Middle Name') }}
                                    </label>
                                    <input type="text" 
                                           name="middle_name" 
                                           id="middle_name" 
                                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-0 transition-all duration-200"
                                           placeholder="Enter middle name (optional)">
                                </div>
                                
                                <!-- Agency/Organization -->
                                <div>
                                    <label for="agency_organization" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                        {{ __('Agency/Organization') }}
                                    </label>
                                    <input type="text" 
                                           name="agency_organization" 
                                           id="agency_organization" 
                                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-0 transition-all duration-200"
                                           placeholder="Enter organization">
                                </div>
                                
                                <!-- Position/Designation -->
                                <div>
                                    <label for="position_designation" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                        {{ __('Position/Designation') }}
                                    </label>
                                    <input type="text" 
                                           name="position_designation" 
                                           id="position_designation" 
                                           class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-0 transition-all duration-200"
                                           placeholder="Enter position">
                                </div>
                                
                                <!-- Sex -->
                                <div class="md:col-span-2">
                                    <label for="sex" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                        {{ __('Sex') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select name="sex" 
                                            id="sex" 
                                            required 
                                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:border-blue-500 focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-0 transition-all duration-200">
                                        <option value="" class="text-gray-500">{{ __('Select Sex') }}</option>
                                        <option value="male">{{ __('Male') }}</option>
                                        <option value="female">{{ __('Female') }}</option>
                                    </select>
                                </div>
                                
                                <!-- Participant Type -->
                                <div class="md:col-span-2">
                                    <label for="participant_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                        {{ __('Participant Type') }}
                                    </label>
                                    <select name="participant_type" 
                                            id="participant_type" 
                                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:border-blue-500 focus:bg-white dark:focus:bg-gray-700 focus:outline-none focus:ring-0 transition-all duration-200">
                                        <option value="" class="text-gray-500">{{ __('Select Participant Type') }}</option>
                                        @foreach(\App\Models\Participant::PARTICIPANT_TYPES as $type)
                                            <option value="{{ $type }}">{{ $type === 'BRGY' ? 'BARANGAY' : $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Vulnerable Groups -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">
                                        {{ __('Vulnerable Groups') }}
                                    </label>
                                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border-2 border-gray-200 dark:border-gray-600">
                                        <div class="space-y-3">
                                            <label class="flex items-center group cursor-pointer">
                                                <input type="checkbox" 
                                                       name="vulnerable_groups[]" 
                                                       value="Persons with Disabilities (PWDs)" 
                                                       class="w-4 h-4 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                    {{ __('Persons with Disabilities (PWDs)') }}
                                                </span>
                                            </label>
                                            <label class="flex items-center group cursor-pointer">
                                                <input type="checkbox" 
                                                       name="vulnerable_groups[]" 
                                                       value="Senior Citizens" 
                                                       class="w-4 h-4 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                    {{ __('Senior Citizens') }}
                                                </span>
                                            </label>
                                            <label class="flex items-center group cursor-pointer">
                                                <input type="checkbox" 
                                                       name="vulnerable_groups[]" 
                                                       value="Pregnant" 
                                                       class="w-4 h-4 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                    {{ __('Pregnant') }}
                                                </span>
                                            </label>
                                            <label class="flex items-center group cursor-pointer">
                                                <input type="checkbox" 
                                                       name="vulnerable_groups[]" 
                                                       value="Children" 
                                                       class="w-4 h-4 text-blue-600 bg-white border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                    {{ __('Children') }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal Footer -->
                        <div class="bg-gray-50 dark:bg-neutral-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse sm:space-y-0">
                                <button type="button" 
                                        id="cancelAddParticipant" 
                                        class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-all duration-200 font-medium">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="submit" 
                                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                                    {{ __('Add Participant') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Upload Excel Modal -->
        <div id="uploadExcelModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="excel-modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Backdrop -->
                <div class="fixed " aria-hidden="true"></div>
                
                <!-- Modal positioning -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <!-- Modal content -->
                <div class="relative inline-block align-bottom bg-white dark:bg-neutral-900 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-neutral-200 dark:border-neutral-700">
                    <form id="uploadExcelForm" enctype="multipart/form-data">
                        @csrf
                        <!-- Modal Header -->
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4">
                            <h3 class="text-xl font-semibold text-white" id="excel-modal-title">
                                {{ __('Upload Participants Excel') }}
                            </h3>
                            <p class="text-emerald-100 text-sm mt-1">{{ __('Upload an Excel file with participant information') }}</p>
                        </div>
                        
                        <!-- Modal Body -->
                        <div class="bg-white dark:bg-neutral-900 px-6 py-6">
                            <!-- File Upload -->
                            <div class="mb-6">
                                <label for="excel_file" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                                    {{ __('Excel File') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-emerald-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label for="excel_file" class="relative cursor-pointer bg-white dark:bg-neutral-900 rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-emerald-500">
                                                <span>{{ __('Upload a file') }}</span>
                                                <input id="excel_file" name="excel_file" type="file" class="sr-only" accept=".xlsx,.xls" required>
                                            </label>
                                            <p class="pl-1">{{ __('or drag and drop') }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Excel files only (.xlsx, .xls)') }}</p>
                                    </div>
                                </div>
                                <div id="file-name" class="mt-2 text-sm text-gray-600 dark:text-gray-400 hidden"></div>
                            </div>
                            
                            <!-- Instructions -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">{{ __('Excel Format Requirements:') }}</h4>
                                <ul class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
                                    <li>• {{ __('Column A: ID Number (optional)') }}</li>
                                    <li>• {{ __('Column B: First Name (required)') }}</li>
                                    <li>• {{ __('Column C: Middle Name (optional)') }}</li>
                                    <li>• {{ __('Column D: Last Name (required)') }}</li>
                                    <li>• {{ __('Column E: Agency/Organization (optional)') }}</li>
                                    <li>• {{ __('Column F: Position/Designation (optional)') }}</li>
                                    <li>• {{ __('Column G: Sex (male/female, required)') }}</li>
                                    <li>• {{ __('Column H: Vulnerable Groups (comma-separated, optional)') }}</li>
                                    <li>• {{ __('Column I: Participant Type (optional)') }}</li>
                                    <li>&nbsp;&nbsp;{{ __('Allowed values: DRRMO, DRRMC, CITY HALL OFFICE, BRGY, NATL. AGENCY, OTHER LGU, PRIVATE SECTOR, OTHER/S (school)') }}</li>
                                    <li>&nbsp;&nbsp;<span class="italic text-blue-500">{{ __('Tip: "Barangay" will be automatically converted to "BRGY"') }}</span></li>
                                    <li>• {{ __('Column I: Vulnerable Groups (comma-separated, optional)') }}</li>
                                </ul>
                                <p class="text-xs text-blue-600 dark:text-blue-400 mt-2 font-medium">{{ __('First row should contain headers') }}</p>
                            </div>
                        </div>
                        
                        <!-- Modal Footer -->
                        <div class="bg-gray-50 dark:bg-neutral-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse sm:space-y-0">
                                <button type="button" 
                                        id="cancelUploadExcel" 
                                        class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-all duration-200 font-medium">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="submit" 
                                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                                    {{ __('Upload & Process') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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
            
            // Modal elements
            const addParticipantBtn = document.getElementById('addParticipantBtn');
            const addParticipantModal = document.getElementById('addParticipantModal');
            const addParticipantForm = document.getElementById('addParticipantForm');
            const cancelAddParticipant = document.getElementById('cancelAddParticipant');
            
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

                        participantsContainer.innerHTML = data.participants.map(participant => {
                            const isProtected = participant.completion_status === 'completed' || participant.has_certificate;
                            
                            return `
                                <div class="flex items-start space-x-3 p-4 border border-neutral-200 dark:border-neutral-600 rounded-lg ${isProtected ? 'bg-blue-50/50 dark:bg-blue-900/10 border-blue-200 dark:border-blue-800' : 'bg-neutral-50 dark:bg-gray-800'}">
                                    <div class="relative flex items-center h-5">
                                        <input type="checkbox" 
                                               id="participant_${participant.id}" 
                                               name="participant_ids[]" 
                                               value="${participant.id}"
                                               ${participant.is_enrolled ? 'checked' : ''}
                                               ${isProtected ? 'disabled' : ''}
                                               class="mt-1 rounded border-neutral-300 dark:border-neutral-600 text-blue-600 focus:ring-blue-500 ${isProtected ? 'opacity-50 cursor-not-allowed' : ''}">
                                        ${isProtected ? `<input type="hidden" name="participant_ids[]" value="${participant.id}">` : ''}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <label for="participant_${participant.id}" class="block text-sm font-medium text-neutral-900 dark:text-neutral-100 ${isProtected ? '' : 'cursor-pointer'}">
                                                ${participant.full_name}
                                            </label>
                                            ${participant.has_certificate ? 
                                                '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Awarded</span>' : 
                                                (participant.completion_status === 'completed' ? 
                                                    '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Completed</span>' : '')
                                            }
                                        </div>
                                        <p class="text-sm text-neutral-500 dark:text-neutral-400">${participant.agency_organization || 'No organization'}</p>
                                        ${participant.position_designation ? `<p class="text-xs text-neutral-400 dark:text-neutral-500">${participant.position_designation}</p>` : ''}
                                        ${isProtected ? `<p class="text-[10px] text-blue-600 dark:text-blue-400 mt-1 font-medium">Cannot be removed - already completed</p>` : ''}
                                    </div>
                                </div>
                            `;
                        }).join('');
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
            
            // Modal functions
            function openModal() {
                addParticipantModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
            
            function closeModal() {
                addParticipantModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                addParticipantForm.reset();
            }
            
            // Handle form submission
            function handleFormSubmit(e) {
                e.preventDefault();
                
                const formData = new FormData(addParticipantForm);
                formData.append('training_id', trainingId);
                
                // Disable submit button
                const submitBtn = addParticipantForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Adding...';
                
                fetch('{{ route("trainings.participants.store", $training) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal();
                        loadParticipants(); // Refresh the participants list
                        
                        // Show success message
                        const successDiv = document.createElement('div');
                        successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                        successDiv.textContent = data.message;
                        document.body.appendChild(successDiv);
                        
                        setTimeout(() => {
                            successDiv.remove();
                        }, 3000);
                    } else {
                        // Show error messages
                        alert('Error: ' + (data.message || 'Failed to add participant'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the participant.');
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
            }

            // Event listeners
            organizationFilter.addEventListener('change', loadParticipants);
            selectAllBtn.addEventListener('click', selectAll);
            deselectAllBtn.addEventListener('click', deselectAll);
            addParticipantBtn.addEventListener('click', openModal);
            cancelAddParticipant.addEventListener('click', closeModal);
            addParticipantForm.addEventListener('submit', handleFormSubmit);
            
            // Close modal when clicking outside
            addParticipantModal.addEventListener('click', function(e) {
                if (e.target === addParticipantModal) {
                    closeModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !addParticipantModal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            // Load all participants initially
            loadParticipants();
            
            // Excel upload modal elements
            const uploadExcelBtn = document.getElementById('uploadExcelBtn');
            const uploadExcelModal = document.getElementById('uploadExcelModal');
            const uploadExcelForm = document.getElementById('uploadExcelForm');
            const cancelUploadExcel = document.getElementById('cancelUploadExcel');
            const excelFileInput = document.getElementById('excel_file');
            const fileNameDiv = document.getElementById('file-name');
            
            // Excel modal functions
            function openExcelModal() {
                uploadExcelModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
            
            function closeExcelModal() {
                uploadExcelModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                uploadExcelForm.reset();
                fileNameDiv.classList.add('hidden');
            }
            
            // Handle file selection
            function handleFileSelect(e) {
                const file = e.target.files[0];
                if (file) {
                    fileNameDiv.textContent = `Selected: ${file.name}`;
                    fileNameDiv.classList.remove('hidden');
                }
            }
            
            // Handle Excel form submission
            function handleExcelFormSubmit(e) {
                e.preventDefault();
                
                const formData = new FormData(uploadExcelForm);
                formData.append('training_id', trainingId);
                
                // Disable submit button
                const submitBtn = uploadExcelForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Processing...';
                
                fetch('{{ route("trainings.participants.upload-excel", $training) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeExcelModal();
                        loadParticipants(); // Refresh the participants list
                        
                        // Show success message
                        const successDiv = document.createElement('div');
                        successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                        successDiv.innerHTML = `
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="font-semibold">${data.message}</p>
                                    ${data.stats ? `<p class="text-sm opacity-90">Added: ${data.stats.added}, Skipped: ${data.stats.skipped}, Errors: ${data.stats.errors}</p>` : ''}
                                </div>
                            </div>
                        `;
                        document.body.appendChild(successDiv);
                        
                        setTimeout(() => {
                            successDiv.remove();
                        }, 5000);
                    } else {
                        // Show error messages
                        let errorMessage = data.message || 'Failed to process Excel file';
                        if (data.errors && data.errors.length > 0) {
                            errorMessage += '\n\nErrors:\n' + data.errors.slice(0, 5).join('\n');
                            if (data.errors.length > 5) {
                                errorMessage += `\n... and ${data.errors.length - 5} more errors`;
                            }
                        }
                        alert(errorMessage);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing the Excel file.');
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
            }
            
            // Excel upload event listeners
            uploadExcelBtn.addEventListener('click', openExcelModal);
            cancelUploadExcel.addEventListener('click', closeExcelModal);
            excelFileInput.addEventListener('change', handleFileSelect);
            
            // Close Excel modal when clicking outside
            uploadExcelModal.addEventListener('click', function(e) {
                if (e.target === uploadExcelModal) {
                    closeExcelModal();
                }
            });
            
            // Close Excel modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !uploadExcelModal.classList.contains('hidden')) {
                    closeExcelModal();
                }
            });
            
            // Excel upload form submission with enhanced feedback
            uploadExcelForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                
                try {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Uploading...';
                    
                    // Fix: Use the correct route URL
                    const response = await fetch(`/trainings/${trainingId}/participants/upload-excel`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        // Show success message
                        let message = `Successfully processed: ${result.stats.added} added`;
                        if (result.stats.skipped > 0) {
                            message += `, ${result.stats.skipped} skipped`;
                        }
                        if (result.stats.errors > 0) {
                            message += `, ${result.stats.errors} errors`;
                        }
                        
                        // Show main success toast
                        toastr.success(message);
                        
                        // Show detailed information for skipped rows with red toastr
                        if (result.skippedRows && result.skippedRows.length > 0) {
                            result.skippedRows.forEach(skippedRow => {
                                toastr.error(
                                    `Row ${skippedRow.row}: ${skippedRow.name} - ${skippedRow.reason}`,
                                    'Skipped Participant',
                                    {
                                        timeOut: 8000, // Show longer for skipped rows
                                        extendedTimeOut: 2000
                                    }
                                );
                            });
                        }
                        
                        // Show errors if any
                        if (result.errors && result.errors.length > 0) {
                            result.errors.forEach(error => {
                                toastr.error(error, 'Validation Error', {
                                    timeOut: 8000,
                                    extendedTimeOut: 2000
                                });
                            });
                        }
                        
                        // Close modal and refresh
                        closeExcelModal();
                        loadParticipants();
                        
                    } else {
                        toastr.error(result.message || 'Failed to upload Excel file');
                    }
                    
                } catch (error) {
                    console.error('Upload error:', error);
                    toastr.error('An error occurred while uploading the file');
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            });
        });
    </script>
</x-layouts.app>
