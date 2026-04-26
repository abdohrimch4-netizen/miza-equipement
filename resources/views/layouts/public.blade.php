<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'MIZA Équipement — Votre référence en électroménager, informatique et mobilier à Safi.')">
    <title>@yield('title', 'MIZA Équipement') — Électroménager & Informatique</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen"
      x-data="{
          cartOpen: false,
          mobileMenuOpen: false,
          catMenuOpen: false,
          toggleCart() { this.cartOpen = !this.cartOpen; document.body.style.overflow = this.cartOpen ? 'hidden' : ''; },
          closeCart() { this.cartOpen = false; document.body.style.overflow = ''; }
      }">

    @php
        $cartItems  = session('cart', []);
        $cartCount  = collect($cartItems)->sum('quantity');
        $cartTotal  = collect($cartItems)->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));
    @endphp

    {{-- ======================================================
         CART OVERLAY + SIDEBAR
    ====================================================== --}}
    <div class="fixed inset-0 bg-black/50 z-[999] transition-opacity duration-300"
         :class="cartOpen ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
         @click="closeCart()"></div>

    <aside class="fixed top-0 right-0 w-full sm:w-[390px] h-full bg-white z-[1000] flex flex-col
                  shadow-2xl transition-transform duration-300 ease-out"
           :class="cartOpen ? 'translate-x-0' : 'translate-x-full'">

        {{-- Cart header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-extrabold text-gray-900 flex items-center gap-2">
                <i class="fas fa-bag-shopping text-brand-primary"></i>
                Mon Panier
                @if($cartCount > 0)
                    <span class="bg-brand-primary text-white text-xs font-black w-5 h-5 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                @endif
            </h3>
            <button @click="closeCart()" class="text-gray-400 hover:text-gray-700 p-1 rounded-lg hover:bg-gray-100 transition">
                <i class="fas fa-xmark text-xl"></i>
            </button>
        </div>

        {{-- Cart body --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            @forelse($cartItems as $productId => $item)
                <div class="flex gap-3 bg-gray-50 rounded-xl p-3">
                    <img src="{{ isset($item['image']) && $item['image'] ? asset('storage/' . $item['image']) : 'https://placehold.co/70x70/f5f5f5/999?text=MIZA' }}"
                         class="w-16 h-16 rounded-lg object-contain bg-white border border-gray-100" alt="{{ $item['name'] ?? '' }}">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 line-clamp-2">{{ $item['name'] ?? '' }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="font-extrabold text-brand-primary">{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }} MAD</span>
                            <span class="text-xs text-gray-400">Qté: {{ $item['quantity'] ?? 1 }}</span>
                        </div>
                    </div>
                    <form action="{{ route('cart.remove') }}" method="POST" class="self-start">
                        @csrf @method('DELETE')
                        <input type="hidden" name="id" value="{{ $productId }}">
                        <button type="submit" class="text-gray-300 hover:text-red-500 transition p-1">
                            <i class="fas fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center py-16">
                    <i class="fas fa-bag-shopping text-5xl text-gray-200 mb-4 block"></i>
                    <p class="text-gray-400 font-semibold">Votre panier est vide</p>
                    <button @click="closeCart()" class="mt-4 text-sm text-brand-primary font-bold hover:underline">
                        Continuer mes achats
                    </button>
                </div>
            @endforelse
        </div>

        {{-- Cart footer --}}
        @if($cartCount > 0)
        <div class="p-4 border-t border-gray-100 space-y-3">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Sous-total ({{ $cartCount }} article{{ $cartCount > 1 ? 's' : '' }})</span>
                <span class="font-semibold text-gray-800">{{ number_format($cartTotal, 2) }} MAD</span>
            </div>
            <div class="flex justify-between text-sm text-gray-500">
                <span>Livraison</span>
                <span class="text-green-600 font-semibold">Gratuite à Safi</span>
            </div>
            <div class="flex justify-between font-extrabold text-gray-900 pt-2 border-t border-gray-100">
                <span>Total</span>
                <span class="text-brand-primary">{{ number_format($cartTotal, 2) }} MAD</span>
            </div>
            <a href="{{ route('cart.index') }}"
               class="block w-full bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold py-3.5 rounded-xl text-center text-sm hover:opacity-90 transition shadow-lg shadow-red-100">
                <i class="fas fa-lock mr-2 text-xs"></i>Valider la commande
            </a>
            <a href="https://wa.me/212622847769" target="_blank"
               class="block w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl text-center text-sm transition">
                <i class="fab fa-whatsapp mr-2"></i>Commander sur WhatsApp
            </a>
        </div>
        @endif
    </aside>

    {{-- ======================================================
         TOP BAR
    ====================================================== --}}
    <div class="bg-gray-900 text-gray-300 text-xs py-2 hidden md:block">
        <div class="max-w-screen-xl mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center gap-5">
                <span class="flex items-center gap-1.5"><i class="fas fa-location-dot text-brand-secondary"></i> Livraison à Safi et environs</span>
                <span class="flex items-center gap-1.5"><i class="fas fa-tag text-green-400"></i> Garantie officielle constructeur</span>
            </div>
            <div class="flex items-center gap-4 font-semibold">
                <a href="{{ route('tracking.index') }}" class="hover:text-white transition"><i class="fas fa-truck-fast"></i> Suivi commande</a>
                <span class="text-gray-600">|</span>
                <a href="{{ route('wishlist.index') }}" class="hover:text-brand-primary transition"><i class="fas fa-heart text-red-500"></i> Favoris</a>
                <span class="text-gray-600">|</span>
                @auth
                    <a href="{{ route('profile.edit') }}" class="hover:text-white transition"><i class="fas fa-user mb-0"></i> {{ auth()->user()->name }}</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="hover:text-red-400 transition ml-2"><i class="fas fa-power-off"></i></button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-brand-primary transition"><i class="fas fa-user"></i> Connexion</a>
                    <span class="text-gray-600">|</span>
                    <a href="{{ route('register') }}" class="hover:text-white transition">Créer un compte</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- ======================================================
         MAIN HEADER
    ====================================================== --}}
    <header class="sticky top-0 z-50 bg-gradient-to-r from-brand-primary via-red-600 to-brand-secondary shadow-md">
        <div class="max-w-screen-xl mx-auto px-4 py-3 flex items-center gap-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 select-none">
                <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center shadow-md">
                    <span class="text-brand-primary font-black text-lg leading-none">M</span>
                </div>
                <div class="hidden sm:block">
                    <span class="text-white font-black text-lg tracking-tight leading-none block">MIZA</span>
                    <span class="text-orange-200 font-semibold text-[10px] tracking-widest leading-none block">ÉQUIPEMENT</span>
                </div>
            </a>

            {{-- Search Bar --}}
            <form action="{{ route('search') }}" method="GET" class="flex-1 flex bg-white rounded overflow-hidden shadow-md" role="search">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Chercher un produit, une marque…"
                       class="flex-1 px-4 py-2.5 text-sm text-gray-700 border-none focus:outline-none min-w-0">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button type="submit"
                        class="bg-gradient-to-r from-brand-secondary to-brand-primary hover:opacity-90 text-white font-bold px-5 py-2.5 text-sm flex items-center gap-2 transition shrink-0">
                    <i class="fas fa-search text-xs"></i>
                    <span class="hidden sm:inline">Rechercher</span>
                </button>
            </form>

            {{-- Right Icons --}}
            <div class="flex items-center gap-1 shrink-0">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="hidden md:flex flex-col items-center text-white/80 hover:text-white transition px-2 py-1 rounded-lg hover:bg-white/10">
                        <i class="fas fa-user-circle text-xl mb-0.5"></i>
                        <span class="text-[10px] font-semibold">{{ Str::limit(Auth::user()->name, 8) }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden md:flex flex-col items-center text-white/80 hover:text-white transition px-2 py-1 rounded-lg hover:bg-white/10">
                        <i class="fas fa-user-circle text-xl mb-0.5"></i>
                        <span class="text-[10px] font-semibold">Connexion</span>
                    </a>
                @endauth

                {{-- Cart button --}}
                <button @click="toggleCart()"
                        class="relative flex flex-col items-center text-white transition px-3 py-1.5 rounded-lg bg-white/15 hover:bg-white/25">
                    <div class="relative">
                        <i class="fas fa-bag-shopping text-xl mb-0.5"></i>
                        @if($cartCount > 0)
                            <span class="absolute -top-1.5 -right-2 bg-brand-accent text-brand-primary text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full">
                                {{ $cartCount > 9 ? '9+' : $cartCount }}
                            </span>
                        @endif
                    </div>
                    <span class="text-[10px] font-semibold">Panier</span>
                </button>

                {{-- Mobile menu --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden text-white/80 hover:text-white transition px-2 py-1 rounded-lg hover:bg-white/10">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" x-transition
             class="md:hidden bg-brand-dark border-t border-white/10 px-4 py-3 space-y-2">
            @foreach($navCategories as $cat)
                <a href="{{ route('home', ['category' => $cat->id]) }}"
                   class="flex items-center gap-2 text-gray-300 hover:text-white py-1.5 text-sm font-medium transition">
                    <i class="fas fa-chevron-right text-xs text-brand-secondary"></i>
                    {{ $cat->name }}
                </a>
            @endforeach
            @auth
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-gray-300 hover:text-white py-1.5 text-sm font-medium transition border-t border-white/10 mt-2 pt-2">
                    <i class="fas fa-user text-xs text-brand-secondary"></i> Mon profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-gray-300 hover:text-white py-1.5 text-sm font-medium transition">
                        <i class="fas fa-sign-out-alt text-xs text-brand-secondary"></i> Déconnexion
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 text-gray-300 hover:text-white py-1.5 text-sm font-medium transition border-t border-white/10 mt-2 pt-2">
                    <i class="fas fa-sign-in-alt text-xs text-brand-secondary"></i> Connexion
                </a>
            @endauth
        </div>
    </header>

    {{-- ======================================================
         CATEGORY NAV BAR
    ====================================================== --}}
    <nav class="bg-white border-b-2 border-gray-100 shadow-sm hidden md:block">
        <div class="max-w-screen-xl mx-auto px-4 flex items-center overflow-x-auto hide-scrollbar">
            <a href="{{ route('home') }}"
               class="flex items-center gap-1.5 px-4 py-3 text-sm font-semibold whitespace-nowrap border-b-2 transition
                      {{ !request('category') && !request('search') ? 'text-brand-primary border-brand-primary' : 'text-gray-600 border-transparent hover:text-brand-primary hover:border-brand-primary' }}">
                <i class="fas fa-house text-xs"></i> Accueil
            </a>
            @foreach($navCategories as $cat)
                <a href="{{ route('home', ['category' => $cat->id]) }}"
                   class="flex items-center gap-1.5 px-4 py-3 text-sm font-semibold whitespace-nowrap border-b-2 transition
                          {{ request('category') == $cat->id ? 'text-brand-primary border-brand-primary' : 'text-gray-600 border-transparent hover:text-brand-primary hover:border-brand-primary' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
            <a href="https://wa.me/212622847769" target="_blank"
               class="ml-auto flex items-center gap-1.5 px-4 py-3 text-sm font-semibold text-green-600 hover:text-green-700 whitespace-nowrap transition">
                <i class="fab fa-whatsapp"></i> Commander
            </a>
        </div>
    </nav>

    {{-- ======================================================
         FLASH MESSAGES
    ====================================================== --}}
    @if(session('success') || session('error') || $errors->any())
        <div class="max-w-screen-xl mx-auto px-4 mt-4">
            @if(session('success'))
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm">
                    <i class="fas fa-circle-check text-green-500 text-lg shrink-0"></i>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm">
                    <i class="fas fa-circle-exclamation text-red-500 text-lg shrink-0"></i>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm">
                    <ul class="list-disc list-inside text-sm font-semibold space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    {{-- ======================================================
         PAGE CONTENT
    ====================================================== --}}
    <main>
        @yield('content')
    </main>

    {{-- ======================================================
         FOOTER
    ====================================================== --}}
    <footer class="bg-brand-dark mt-12">
        <div class="max-w-screen-xl mx-auto px-4 pt-12 pb-0">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 pb-10 border-b border-gray-800">

                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-brand-primary rounded-xl flex items-center justify-center">
                            <span class="text-white font-black text-xl">M</span>
                        </div>
                        <div>
                            <span class="text-white font-black text-lg tracking-tight block leading-none">MIZA</span>
                            <span class="text-brand-secondary font-semibold text-[10px] tracking-widest block leading-none">ÉQUIPEMENT</span>
                        </div>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed mb-5">
                        Fournisseur officiel des meilleures marques. Électroménager, Informatique et Meubles au meilleur prix à Safi.
                    </p>
                    <a href="https://wa.me/212622847769" class="flex items-center gap-2 text-green-400 hover:text-green-300 font-bold transition">
                        <i class="fab fa-whatsapp text-xl"></i> +212 622-847769
                    </a>
                    <div class="flex gap-2 mt-5">
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-blue-700 text-gray-400 hover:text-white flex items-center justify-center transition text-sm"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-pink-600 text-gray-400 hover:text-white flex items-center justify-center transition text-sm"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/212622847769" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-green-600 text-gray-400 hover:text-white flex items-center justify-center transition text-sm"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-sm font-extrabold text-white mb-5 uppercase tracking-wider">Liens Rapides</h4>
                    <a href="{{ route('home') }}" class="footer-link block mb-2.5">Accueil</a>
                    @foreach($navCategories->take(5) as $cat)
                        <a href="{{ route('home', ['category' => $cat->id]) }}" class="footer-link block mb-2.5">{{ $cat->name }}</a>
                    @endforeach
                </div>

                {{-- Customer Service --}}
                <div>
                    <h4 class="text-sm font-extrabold text-white mb-5 uppercase tracking-wider">Service Client</h4>
                    <a href="{{ route('cart.index') }}" class="footer-link block mb-2.5">Mon Panier</a>
                    @auth
                        <a href="{{ route('profile.edit') }}" class="footer-link block mb-2.5">Mon Compte</a>
                    @else
                        <a href="{{ route('login') }}" class="footer-link block mb-2.5">Connexion / Inscription</a>
                    @endauth
                    <a href="https://wa.me/212622847769" target="_blank" class="footer-link block mb-2.5">Support WhatsApp</a>
                    <a href="#" class="footer-link block mb-2.5">Politique de retour</a>
                    <a href="#" class="footer-link block mb-2.5">FAQ</a>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="text-sm font-extrabold text-white mb-5 uppercase tracking-wider">Contact & Horaires</h4>
                    <div class="space-y-3 text-sm text-gray-500">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-location-dot text-brand-primary mt-0.5"></i>
                            <span>Safi, Maroc</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-clock text-brand-secondary mt-0.5"></i>
                            <span>Lundi – Samedi<br>9h00 – 20h00</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fab fa-whatsapp text-green-400 mt-0.5"></i>
                            <span>Support 24/7 via WhatsApp</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer bottom --}}
        <div class="bg-brand-navy py-4">
            <div class="max-w-screen-xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-600">
                <p>© {{ date('Y') }} MIZA Équipement — Tous droits réservés.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-gray-400 transition">Mentions légales</a>
                    <a href="#" class="hover:text-gray-400 transition">Confidentialité</a>
                    <a href="#" class="hover:text-gray-400 transition">CGV</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- WhatsApp FAB --}}
    <a href="https://wa.me/212622847769?text=Bonjour%20MIZA%20!" target="_blank"
       class="fab-whatsapp" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    @stack('scripts')
</body>
</html>
