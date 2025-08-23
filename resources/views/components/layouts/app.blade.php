<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>

<body class="min-h-screen font-sans antialiased bg-base-200">
    <div class="h-96 w-96 text-3xl" wire:loading>
        <x-loading class="loading-bars bg-primary w-96 " />
    </div>
    <header class="bg-primary w-full top-0 ">
        <div class="flex justify-between max-w-7xl mx-auto py-2">
            <div class="flex gap-2 items-center justify-center">
                <img src="{{ asset('assets/logo-white.svg') }}" alt="Logo" class="h-10 mx-auto">
                <h1 class="text-white text-2xl font-bold">{{ auth()->user()->tenant->name }}</h1>
            </div>
            <div>
                <x-dropdown>
                    <x-slot:trigger>
                        <button class="btn btn-ghost btn-circle avatar">
                            <div class="flex ">
                                <x-icon name="s-user" class="w-8 h-8 text-primary bg-gray-200 rounded-full" />

                            </div>
                        </button>

                    </x-slot:trigger>

                    <x-menu-item title="Painel" />
                    <x-menu-separator />
                    <x-menu-item title="Sair" link="{{ route('logout') }}" />
                </x-dropdown>
            </div>
        </div>
    </header>
    {{-- MAIN --}}
    <x-main>
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @livewireScripts
</body>

</html>
