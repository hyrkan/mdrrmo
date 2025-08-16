<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <div class="flex flex-col items-center gap-3 mb-2">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('icon/makati.jpg') }}" alt="Makati Logo" class="h-10 w-10 rounded-full object-cover">
                        <img src="{{ asset('icon/mdrrmo.png') }}" alt="MDRRMO Logo" class="h-10 w-10 rounded-full object-cover">
                    </div>
                    <div class="text-center">
                        <h1 class="text-xl font-semibold text-gray-900 dark:text-white whitespace-nowrap">Makati Disaster Risk Reduction Management Office</h1>
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
