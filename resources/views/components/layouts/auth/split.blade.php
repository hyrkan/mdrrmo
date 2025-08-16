<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800">
                <div class="absolute inset-0 bg-neutral-900"></div>
                <div class="relative z-20 flex items-center gap-3">
                    <img src="{{ asset('icon/makati.jpg') }}" alt="Makati Logo" class="h-9 w-9 rounded-full object-cover">
                    <img src="{{ asset('icon/mdrrmo.png') }}" alt="MDRRMO Logo" class="h-9 w-9 rounded-full object-cover">
                    <span class="text-lg font-medium whitespace-nowrap">Makati Disaster Risk Reduction Management Office</span>
                </div>

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2">
                        <flux:heading size="lg">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                        <footer><flux:heading>{{ trim($author) }}</flux:heading></footer>
                    </blockquote>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <div class="flex flex-col items-center gap-3 lg:hidden">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('icon/makati.jpg') }}" alt="Makati Logo" class="h-10 w-10 rounded-full object-cover">
                            <img src="{{ asset('icon/mdrrmo.png') }}" alt="MDRRMO Logo" class="h-10 w-10 rounded-full object-cover">
                        </div>
                        <div class="text-center">
                            <h1 class="text-xl font-semibold text-gray-900 dark:text-white whitespace-nowrap">Makati Disaster Risk Reduction Management Office</h1>
                        </div>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
