<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — MIZA Back-Office</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-900" x-data="{ sidebar: true }">
<div class="flex h-screen overflow-hidden">

{{-- SIDEBAR --}}
<aside :class="sidebar ? 'w-60' : 'w-16'" class="bg-brand-dark flex flex-col flex-shrink-0 transition-all duration-300 overflow-hidden">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10 shrink-0">
        <div class="w-9 h-9 bg-brand-primary rounded-xl flex items-center justify-center shrink-0">
            <span class="text-white font-black text-lg leading-none">M</span>
        </div>
        <div x-show="sidebar" x-transition class="overflow-hidden">
            <span class="text-white font-black text-base leading-none block">MIZA</span>
            <span class="text-brand-secondary text-[10px] font-semibold tracking-widest block">BACK-OFFICE</span>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-2 py-4 space-y-0.5 overflow-y-auto">
        @php
       $adminNav = [
            ['route' => 'admin.dashboard',        'icon' => 'fas fa-gauge-high',   'label' => 'Dashboard'],
            ['route' => 'products.index',         'icon' => 'fas fa-box-open',     'label' => 'Produits'],
            ['route' => 'admin.categories',       'icon' => 'fas fa-tags',         'label' => 'Categories'],
            ['route' => 'admin.orders',           'icon' => 'fas fa-bag-shopping', 'label' => 'Commandes'],
        ];
        @endphp
        @foreach($adminNav as $item)
            @php $active = request()->routeIs($item['route'].'*'); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition
                      {{ $active ? 'bg-brand-primary text-white' : 'text-gray-400 hover:text-white hover:bg-white/10' }}">
                <div class="flex items-center gap-3">
                    <i class="{{ $item['icon'] }} w-5 text-center shrink-0 text-base"></i>
                    <span x-show="sidebar" x-transition class="whitespace-nowrap">{{ $item['label'] }}</span>
                </div>
                @if($item['route'] === 'admin.orders')
                    @php $unreadCount = \App\Models\Order::where('statut', 'pending')->count(); @endphp
                    @if($unreadCount > 0)
                        <span x-show="sidebar" class="bg-red-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-md">{{ $unreadCount }}</span>
                    @endif
                @endif
            </a>
        @endforeach
        <div class="border-t border-white/10 my-2 mx-1"></div>
        <a href="{{ route('home') }}" target="_blank"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-gray-400 hover:text-white hover:bg-white/10 transition">
            <i class="fas fa-store w-5 text-center shrink-0 text-base"></i>
            <span x-show="sidebar" x-transition>Voir la boutique</span>
        </a>
    </nav>

    {{-- User footer --}}
    <div class="border-t border-white/10 p-3 shrink-0">
        <div class="flex items-center gap-2.5 px-2 py-2 rounded-xl hover:bg-white/5">
            <div class="w-8 h-8 bg-brand-primary rounded-full flex items-center justify-center shrink-0">
                <span class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
            </div>
            <div x-show="sidebar" x-transition class="flex-1 overflow-hidden min-w-0">
                <p class="text-white text-xs font-bold leading-tight truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-gray-500 text-[10px] leading-tight truncate">{{ Auth::user()->email ?? '' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" x-show="sidebar" x-transition class="mt-1">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs text-gray-500 hover:text-red-400 hover:bg-white/5 transition font-semibold">
                <i class="fas fa-sign-out-alt text-xs"></i> Deconnexion
            </button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<div class="flex-1 flex flex-col overflow-hidden min-w-0">

    {{-- Top bar --}}
    <header class="bg-white border-b border-gray-100 px-5 py-3 flex items-center justify-between gap-4 shrink-0 shadow-sm">
        <div class="flex items-center gap-3">
            <button @click="sidebar = !sidebar" class="text-gray-400 hover:text-gray-700 transition p-1.5 rounded-lg hover:bg-gray-100">
                <i class="fas fa-bars text-sm"></i>
            </button>
            <div>
                <h1 class="font-extrabold text-gray-900 text-sm leading-none">@yield('title', 'Dashboard')</h1>
                <p class="text-xs text-gray-400 mt-0.5 leading-none">@yield('subtitle', 'MIZA Back-Office')</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if(session('success'))
                <span class="hidden sm:flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full border border-green-200">
                    <i class="fas fa-check-circle text-xs"></i> {{ session('success') }}
                </span>
            @endif
            <a href="{{ route('products.create') }}"
               class="inline-flex items-center gap-1.5 bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold px-4 py-2 rounded-xl text-xs hover:opacity-90 transition">
                <i class="fas fa-plus text-[10px]"></i> Ajouter produit
            </a>
        </div>
    </header>

    {{-- Page content --}}
    <main class="flex-1 overflow-y-auto p-5">
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5 text-sm text-red-700 flex items-center gap-2">
                <i class="fas fa-circle-exclamation shrink-0"></i> {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>
</div>
</div>
@stack('scripts')
</body>
</html>