<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-5 border-b border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ __('Participants') }}</h1>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">{{ __('Manage and track all training participants') }}</p>
                    </div>
                    <a href="{{ route('participants.create') }}" 
                       class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm shadow-blue-200 dark:shadow-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('Add Participant') }}
                    </a>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-900/20">
                <form action="{{ route('participants.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    @if(request('direction'))
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                    @endif
                    
                    <div class="flex-1">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-neutral-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   class="block w-full pl-10 pr-3 py-2.5 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm"
                                   placeholder="{{ __('Search by name, ID, organization...') }}">
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-neutral-900 dark:bg-neutral-100 text-white dark:text-neutral-900 rounded-lg hover:bg-neutral-800 dark:hover:bg-neutral-200 transition-colors text-sm font-semibold">
                            {{ __('Search') }}
                        </button>
                        @if(request('search'))
                            <a href="{{ route('participants.index', request()->only(['sort', 'direction'])) }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 text-neutral-700 dark:text-neutral-300 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors text-sm font-semibold">
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="px-6 py-4 bg-green-50 dark:bg-green-900/10 border-b border-green-200 dark:border-green-800/30">
                    <div class="flex items-center text-green-700 dark:text-green-400 text-sm font-medium">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Participants Table -->
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                        <tr>
                            @php
                                $columns = [
                                    'id_no' => __('ID No'),
                                    'name' => __('Full Name'),
                                    'agency_organization' => __('Agency/Organization'),
                                    'position_designation' => __('Position'),
                                    'sex' => __('Sex'),
                                ];
                            @endphp

                            @foreach($columns as $key => $label)
                                <th class="px-6 py-4 text-left">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => $key, 'direction' => (request('sort') === $key) ? $nextDirection : 'asc']) }}" 
                                       class="group inline-flex items-center text-xs font-bold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        {{ $label }}
                                        <span class="ml-2 flex-none rounded text-neutral-400 transition-colors">
                                            @if(request('sort') === $key)
                                                @if(request('direction') === 'asc')
                                                    <svg class="h-4 w-4 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="h-4 w-4 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            @else
                                                <svg class="h-4 w-4 opacity-0 group-hover:opacity-100" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </span>
                                    </a>
                                </th>
                            @endforeach

                            <th class="px-6 py-4 text-left text-xs font-bold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                {{ __('Vulnerable Groups') }}
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse ($participants as $participant)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600 dark:text-neutral-300">
                                    {{ $participant->id_no ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                                        {{ $participant->formal_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600 dark:text-neutral-300">
                                    {{ $participant->agency_organization ?: '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600 dark:text-neutral-300">
                                    {{ $participant->position_designation ?: '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($participant->sex === 'male')
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                            <svg class="mr-1.5 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            {{ __('Male') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-400">
                                            <svg class="mr-1.5 h-2 w-2 text-pink-400" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            {{ __('Female') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600 dark:text-neutral-300">
                                    {{ $participant->vulnerable_groups_formatted }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('participants.show', $participant) }}" 
                                           class="p-2 text-neutral-500 hover:text-blue-600 dark:text-neutral-400 dark:hover:text-blue-400 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-all"
                                           title="{{ __('View Details') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('participants.edit', $participant) }}" 
                                           class="p-2 text-neutral-500 hover:text-amber-600 dark:text-neutral-400 dark:hover:text-amber-400 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-all"
                                           title="{{ __('Edit Participant') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('participants.destroy', $participant) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this participant?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-neutral-500 hover:text-red-600 dark:text-neutral-400 dark:hover:text-red-400 hover:bg-neutral-100 dark:hover:bg-neutral-700 rounded-lg transition-all"
                                                    title="{{ __('Delete Participant') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="max-w-xs mx-auto">
                                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-neutral-100 dark:bg-neutral-700">
                                            <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-neutral-900 dark:text-neutral-100 mb-1">{{ __('No participants found') }}</h3>
                                        <p class="text-neutral-500 dark:text-neutral-400 mb-6">{{ __('Try adjusting your search or add a new participant.') }}</p>
                                        <a href="{{ route('participants.create') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            {{ __('Add Participant') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($participants->hasPages())
                <div class="px-6 py-5 border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50/30 dark:bg-neutral-900/10">
                    {{ $participants->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
