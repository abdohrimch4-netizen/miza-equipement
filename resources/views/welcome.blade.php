@extends('layouts.public')

@section('title', 'Accueil')
@section('meta_description', 'MIZA Équipement — Votre référence en électroménager, informatique et mobilier à Safi. Qualité, garantie et meilleurs prix.')

@section('content')

{{-- ======================================================
     HERO SLIDER
====================================================== --}}
<section class="bg-gray-100"
         x-data="{
             current: 0,
             total: 3,
             timer: null,
             init() { this.timer = setInterval(() => this.next(), 5000); },
             next() { this.current = (this.current + 1) % this.total; },
             prev() { this.current = (this.current - 1 + this.total) % this.total; },
             go(n) { this.current = n; clearInterval(this.timer); this.timer = setInterval(() => this.next(), 5000); }
         }">
    <div class="max-w-screen-xl mx-auto px-4 py-4">
        <div class="flex gap-4" style="height:340px;">

            {{-- Main slider --}}
            <div class="relative flex-1 rounded-2xl overflow-hidden min-w-0">

                {{-- Slide 1 --}}
                <div x-show="current === 0" x-transition:enter="transition-opacity duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     class="absolute inset-0 flex items-center px-10"
                     style="background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 50%, #e8340a 100%);">
                    <div class="max-w-xs z-10">
                        <span class="inline-flex items-center gap-2 bg-white/15 text-white text-xs font-bold px-3 py-1 rounded-full mb-4">
                            <i class="fas fa-fire text-yellow-300"></i> Vente Flash
                        </span>
                        <h2 class="text-4xl font-black text-white leading-tight mb-2">Smart TV<br><span class="text-yellow-300">55" 4K</span></h2>
                        <p class="text-white/70 text-sm mb-5">Ultra HD, HDR10+. L'expérience cinéma chez vous.</p>
                        <a href="{{ route('home', ['category' => '']) }}"
                           class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-black px-7 py-3 rounded-full text-sm transition shadow-xl">
                            Découvrir <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                {{-- Slide 2 --}}
                <div x-show="current === 1" x-transition:enter="transition-opacity duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     class="absolute inset-0 flex items-center px-10"
                     style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #7c3aed 100%);">
                    <div class="max-w-xs z-10">
                        <span class="inline-flex items-center gap-2 bg-white/15 text-white text-xs font-bold px-3 py-1 rounded-full mb-4">
                            <i class="fas fa-snowflake text-blue-300"></i> Été — Climatisation
                        </span>
                        <h2 class="text-4xl font-black text-white leading-tight mb-2">Climatiseur<br><span class="text-blue-300">Inverter</span></h2>
                        <p class="text-white/70 text-sm mb-5">A+++ économie d'énergie, Silence optimal.</p>
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-2 bg-blue-400 hover:bg-blue-300 text-gray-900 font-black px-7 py-3 rounded-full text-sm transition shadow-xl">
                            Voir l'offre <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                {{-- Slide 3 --}}
                <div x-show="current === 2" x-transition:enter="transition-opacity duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     class="absolute inset-0 flex items-center px-10"
                     style="background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #059669 100%);">
                    <div class="max-w-xs z-10">
                        <span class="inline-flex items-center gap-2 bg-white/15 text-white text-xs font-bold px-3 py-1 rounded-full mb-4">
                            <i class="fas fa-laptop text-green-300"></i> Nouvelle collection
                        </span>
                        <h2 class="text-4xl font-black text-white leading-tight mb-2">Informatique<br><span class="text-green-300">& Multimédia</span></h2>
                        <p class="text-white/70 text-sm mb-5">Laptops, tablettes, accessoires. Puissance et mobilité.</p>
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-2 bg-green-400 hover:bg-green-300 text-gray-900 font-black px-7 py-3 rounded-full text-sm transition shadow-xl">
                            Découvrir <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                {{-- Arrows --}}
                <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 z-20 w-9 h-9 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center transition">
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
                <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 z-20 w-9 h-9 bg-white/20 hover:bg-white/40 text-white rounded-full flex items-center justify-center transition">
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>

                {{-- Dots --}}
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
                    <template x-for="i in [0,1,2]" :key="i">
                        <button @click="go(i)"
                                :class="current === i ? 'bg-white w-6 rounded' : 'bg-white/40'"
                                class="h-2 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>

            {{-- Side banners --}}
            <div class="hidden lg:flex flex-col gap-3 w-52 shrink-0">
                <a href="{{ route('home') }}" class="flex-1 rounded-xl p-5 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-200 shadow-sm hover:shadow-lg"
                   style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                    <div>
                        <span class="text-xs font-bold text-orange-600 bg-orange-100 px-2 py-0.5 rounded-full">Nouveau</span>
                        <h3 class="text-base font-extrabold text-gray-900 mt-2 leading-tight">Robot<br>Pâtissier</h3>
                    </div>
                    <p class="text-lg font-black text-orange-600">Kenwood</p>
                </a>
                <a href="{{ route('home') }}" class="flex-1 rounded-xl p-5 flex flex-col justify-between hover:-translate-y-1 transition-transform duration-200 shadow-sm hover:shadow-lg"
                   style="background: linear-gradient(135deg, #ede9fe, #c4b5fd);">
                    <div>
                        <span class="text-xs font-bold text-purple-700 bg-purple-100 px-2 py-0.5 rounded-full">Promo</span>
                        <h3 class="text-base font-extrabold text-gray-900 mt-2 leading-tight">Lave-Linge<br>7 kg A+++</h3>
                    </div>
                    <p class="text-lg font-black text-purple-700">-35%</p>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ======================================================
     TRUST BAR
====================================================== --}}
<div class="bg-white border-y border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100">
            <div class="flex items-center gap-3 p-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0"><i class="fas fa-truck-fast"></i></div>
                <div><p class="text-sm font-extrabold text-gray-900">Livraison Rapide</p><p class="text-xs text-gray-400">Partout à Safi</p></div>
            </div>
            <div class="flex items-center gap-3 p-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-lg shrink-0"><i class="fas fa-shield-halved"></i></div>
                <div><p class="text-sm font-extrabold text-gray-900">Garantie Officielle</p><p class="text-xs text-gray-400">Constructeur agréé</p></div>
            </div>
            <div class="flex items-center gap-3 p-4">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-brand-secondary flex items-center justify-center text-lg shrink-0"><i class="fas fa-rotate-left"></i></div>
                <div><p class="text-sm font-extrabold text-gray-900">Retour 7 jours</p><p class="text-xs text-gray-400">Remboursement garanti</p></div>
            </div>
            <div class="flex items-center gap-3 p-4">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shrink-0"><i class="fas fa-headset"></i></div>
                <div><p class="text-sm font-extrabold text-gray-900">Support 24/7</p><p class="text-xs text-gray-400">Via WhatsApp</p></div>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================
     FLASH DEALS (DB products)
====================================================== --}}
@if($flashDeals->count() > 0)
<section class="max-w-screen-xl mx-auto px-4 py-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Flash header --}}
        <div class="bg-gradient-to-r from-brand-primary to-brand-secondary p-4 md:p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3"
             x-data="{
                 secs: 8*3600 + 34*60 + 21,
                 h:'08', m:'34', s:'21',
                 init() {
                     setInterval(() => {
                         if(this.secs > 0) { this.secs--;
                             this.h = String(Math.floor(this.secs/3600)).padStart(2,'0');
                             this.m = String(Math.floor((this.secs%3600)/60)).padStart(2,'0');
                             this.s = String(this.secs%60).padStart(2,'0');
                         }
                     }, 1000);
                 }
             }">
            <div class="flex items-center gap-3 text-white font-black text-xl">
                <i class="fas fa-bolt text-yellow-300 text-2xl"></i>
                Ventes Flash
                <span class="text-sm font-semibold text-white/70">• Stock limité !</span>
            </div>
            <div class="flex items-center gap-3 text-white">
                <span class="text-xs font-semibold text-white/70 hidden sm:block">Se termine dans</span>
                <div class="flex items-center gap-1.5">
                    <div class="bg-black/20 rounded-lg w-10 h-9 flex items-center justify-center text-lg font-black" x-text="h"></div>
                    <span class="font-black text-lg">:</span>
                    <div class="bg-black/20 rounded-lg w-10 h-9 flex items-center justify-center text-lg font-black" x-text="m"></div>
                    <span class="font-black text-lg">:</span>
                    <div class="bg-black/20 rounded-lg w-10 h-9 flex items-center justify-center text-lg font-black" x-text="s"></div>
                </div>
            </div>
        </div>

        {{-- Flash products grid --}}
        <div class="p-4 md:p-5 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($flashDeals as $product)
                <div class="product-card">
                    <a href="{{ route('produit.details', $product->id) }}" class="product-image-wrap">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400/f5f5f5/999?text=MIZA' }}"
                             alt="{{ $product->name }}" loading="lazy">
                        @if($product->stock < 5)
                            <span class="badge-stock">{{ $product->stock }} restant</span>
                        @endif
                    </a>
                    <div class="product-body">
                        <a href="{{ route('produit.details', $product->id) }}" class="product-name">{{ $product->name }}</a>
                        <div class="flex items-baseline gap-1 mb-2">
                            <span class="product-price-current">{{ number_format($product->price, 2) }}</span>
                            <span class="text-xs font-bold text-brand-primary">MAD</span>
                        </div>
                        <div class="sold-bar"><div class="sold-bar-fill" style="width: {{ min(90, 30 + ($product->id % 60)) }}%"></div></div>
                        <p class="text-xs text-brand-secondary font-semibold mt-1">{{ min(90, 30 + ($product->id % 60)) }}% vendu</p>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="add-to-cart-btn">
                                <i class="fas fa-cart-plus text-xs"></i> Ajouter au panier
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ======================================================
     ALL PRODUCTS (paginated + filterable)
====================================================== --}}
<section class="max-w-screen-xl mx-auto px-4 pb-10">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Section header --}}
        <div class="flex flex-wrap items-center justify-between gap-3 p-5 border-b border-gray-100">
            <h2 class="text-xl font-extrabold text-gray-900 flex items-center gap-2">
                <span class="w-1 h-6 bg-gradient-to-b from-brand-primary to-brand-secondary rounded-full inline-block"></span>
                @if(request('search'))
                    Résultats pour "{{ request('search') }}"
                    <span class="text-sm font-normal text-gray-400">({{ $products->total() }} produits)</span>
                @elseif(request('category'))
                    @php $currentCat = $categories->firstWhere('id', request('category')); @endphp
                    {{ $currentCat ? $currentCat->name : 'Catégorie' }}
                    <span class="text-sm font-normal text-gray-400">({{ $products->total() }} produits)</span>
                @else
                    Tous nos produits
                    <span class="text-sm font-normal text-gray-400">({{ $products->total() }})</span>
                @endif
            </h2>

            {{-- Category tabs --}}
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('home') }}"
                   class="px-4 py-1.5 rounded-full text-xs font-bold transition {{ !request('category') && !request('search') ? 'bg-brand-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Tous
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('home', ['category' => $cat->id]) }}"
                       class="px-4 py-1.5 rounded-full text-xs font-bold transition {{ request('category') == $cat->id ? 'bg-brand-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $cat->name }}
                        <span class="opacity-60">({{ $cat->products_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Product grid --}}
        @if($products->count() > 0)
            <div class="p-4 md:p-5 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach($products as $product)
                    <div class="product-card">
                        <a href="{{ route('produit.details', $product->id) }}" class="product-image-wrap">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400/f5f5f5/999?text=MIZA' }}"
                                 alt="{{ $product->name }}" loading="lazy">
                            @if($product->stock == 0)
                                <span class="badge-stock bg-gray-500">Rupture</span>
                            @elseif($product->stock < 5)
                                <span class="badge-stock">{{ $product->stock }} restant</span>
                            @endif
                        </a>
                        <div class="product-body">
                            <a href="{{ route('produit.details', $product->id) }}" class="product-name">{{ $product->name }}</a>
                            @if($product->category)
                                <span class="text-xs text-gray-400 mb-1 block">{{ $product->category->name }}</span>
                            @endif
                            <div class="flex items-baseline gap-1 mt-auto">
                                <span class="product-price-current">{{ number_format($product->price, 2) }}</span>
                                <span class="text-xs font-bold text-brand-primary">MAD</span>
                            </div>
                            <div class="badge-shipping"><i class="fas fa-truck-fast text-xs"></i> Livraison gratuite</div>
                            @if($product->stock > 0)
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="add-to-cart-btn">
                                        <i class="fas fa-cart-plus text-xs"></i> Ajouter au panier
                                    </button>
                                </form>
                            @else
                                <button disabled class="add-to-cart-btn opacity-50 cursor-not-allowed mt-2">Rupture de stock</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
                <div class="px-5 pb-5">{{ $products->links() }}</div>
            @endif
        @else
            <div class="text-center py-16">
                <i class="fas fa-box-open text-5xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-400 font-semibold">Aucun produit trouvé.</p>
                <a href="{{ route('home') }}" class="mt-4 inline-flex items-center gap-2 text-brand-primary font-bold hover:underline text-sm">
                    <i class="fas fa-arrow-left text-xs"></i> Voir tous les produits
                </a>
            </div>
        @endif
    </div>
</section>

{{-- ======================================================
     NEWSLETTER
====================================================== --}}
<section class="py-14 px-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 60%, #e8340a 100%);">
    <div class="max-w-2xl mx-auto text-center">
        <div class="inline-flex items-center gap-2 bg-white/10 text-white text-xs font-bold px-4 py-1.5 rounded-full mb-5 border border-white/20">
            <i class="fas fa-bell text-yellow-300"></i> Recevez nos meilleures offres
        </div>
        <h2 class="text-3xl font-black text-white mb-3">Ne ratez aucune promo !</h2>
        <p class="text-white/60 mb-8 text-sm">Inscrivez-vous et recevez -10% sur votre première commande.</p>
        <div class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <input type="email" placeholder="Votre adresse email"
                   class="flex-1 px-5 py-3.5 rounded-xl text-gray-800 text-sm font-medium focus:outline-none shadow-xl">
            <button class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-black px-7 py-3.5 rounded-xl text-sm transition shadow-xl whitespace-nowrap">
                S'inscrire <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
    </div>
</section>

@endsection
