@extends('layouts.public')

@section('title', $product->name . ' - MIZA Équipement')
@section('meta_description', Str::limit($product->description ?? 'Détails du produit ' . $product->name, 155))

@section('content')
<div class="max-w-screen-xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand-primary transition">Accueil</a>
        <i class="fas fa-chevron-right text-xs"></i>
        @if($product->category)
            <a href="{{ route('home', ['category' => $product->category_id]) }}" class="hover:text-brand-primary transition">{{ $product->category->name }}</a>
            <i class="fas fa-chevron-right text-xs"></i>
        @endif
        <span class="text-gray-600 font-medium line-clamp-1">{{ $product->name }}</span>
    </nav>

    {{-- Product main card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2">

            {{-- Gallery --}}
            <div class="bg-gray-50 p-8 flex flex-col items-center border-b lg:border-b-0 lg:border-r border-gray-100"
                 x-data="{
                     mainImage: '{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x600/f5f5f5/999?text=MIZA' }}',
                     setMain(src) { this.mainImage = src; }
                 }">

                {{-- Main image --}}
                <div class="relative w-full max-w-sm aspect-square flex items-center justify-center">
                    @if($product->stock < 5 && $product->stock > 0)
                        <span class="absolute top-3 left-3 z-10 bg-brand-secondary text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse">
                            Plus que {{ $product->stock }} en stock !
                        </span>
                    @elseif($product->stock == 0)
                        <span class="absolute top-3 left-3 z-10 bg-gray-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                            Rupture de stock
                        </span>
                    @endif
                    <img :src="mainImage" alt="{{ $product->name }}"
                         class="max-h-full max-w-full object-contain transition-transform duration-500 hover:scale-105 drop-shadow-lg">
                </div>

                {{-- Thumbnails (gallery) --}}
                @if($product->gallery && count($product->gallery) > 0)
                    <div class="flex gap-3 mt-5 flex-wrap justify-center">
                        <button @click="setMain('{{ $product->image ? asset('storage/' . $product->image) : '' }}')"
                                class="w-16 h-16 rounded-lg border-2 border-brand-primary overflow-hidden bg-white p-1">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                 class="w-full h-full object-contain" alt="Principale">
                        </button>
                        @foreach($product->gallery as $img)
                            <button @click="setMain('{{ asset('storage/' . $img) }}')"
                                    class="w-16 h-16 rounded-lg border-2 border-gray-200 hover:border-brand-primary overflow-hidden bg-white p-1 transition">
                                <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-contain" alt="Photo {{ $loop->iteration + 1 }}">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="p-8 lg:p-10 flex flex-col justify-center">

                {{-- Ref + Category --}}
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-xs font-bold text-gray-400 tracking-widest uppercase">RÉF: {{ $product->reference }}</span>
                    @if($product->category)
                        <span class="bg-brand-light text-brand-primary text-xs font-bold px-2.5 py-1 rounded-full">{{ $product->category->name }}</span>
                    @endif
                </div>

                <h1 class="text-2xl md:text-3xl font-black text-gray-900 mb-5 leading-tight">{{ $product->name }}</h1>

                {{-- Price + Stock --}}
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                    <span class="text-4xl font-black text-brand-primary">{{ number_format($product->price, 2) }}<span class="text-xl text-gray-400 ml-1 font-semibold">MAD</span></span>
                    @if($product->stock > 0)
                        <span class="flex items-center gap-1.5 bg-green-50 text-green-700 px-3 py-1.5 rounded-full text-sm font-bold">
                            <i class="fas fa-circle-check"></i> En stock
                        </span>
                    @else
                        <span class="flex items-center gap-1.5 bg-red-50 text-red-700 px-3 py-1.5 rounded-full text-sm font-bold">
                            <i class="fas fa-circle-xmark"></i> Rupture
                        </span>
                    @endif
                </div>

                {{-- Description --}}
                <div class="mb-8">
                    <h3 class="text-base font-bold text-gray-800 mb-2">Description :</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">
                        {{ $product->description ?? 'Contactez-nous via WhatsApp pour plus de détails sur ce produit.' }}
                    </p>
                </div>

                {{-- Actions --}}
                @if($product->stock > 0)
                    {{-- Add to cart --}}
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="flex gap-3 mb-3">
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                <button type="button" onclick="decrementQty()" class="w-10 h-11 flex items-center justify-center text-gray-500 hover:bg-gray-50 font-bold text-lg">−</button>
                                <input type="number" name="quantity" id="qty" value="1" min="1" max="{{ $product->stock }}"
                                       class="w-14 h-11 text-center text-sm font-bold border-x border-gray-200 focus:outline-none">
                                <button type="button" onclick="incrementQty()" class="w-10 h-11 flex items-center justify-center text-gray-500 hover:bg-gray-50 font-bold text-lg">+</button>
                            </div>
                            <button type="submit"
                                    class="flex-1 bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold py-3 rounded-lg hover:opacity-90 transition flex items-center justify-center gap-2 shadow-md shadow-red-100">
                                <i class="fas fa-cart-plus"></i> Ajouter au panier
                            </button>
                        </div>
                    </form>
                @endif
                
                @auth
                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="mb-5">
                    @csrf
                    @php $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists(); @endphp
                    <button type="submit" class="w-full flex items-center justify-center gap-2 border-2 {{ $inWishlist ? 'border-red-500 text-red-500 bg-red-50' : 'border-gray-200 text-gray-600 hover:border-gray-300' }} font-bold py-2.5 rounded-lg transition text-sm">
                        <i class="fas fa-heart {{ $inWishlist ? 'text-red-500' : 'text-gray-400' }}"></i> {{ $inWishlist ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
                    </button>
                </form>
                @endauth

                {{-- WhatsApp --}}
                @php
                    $waMsg = "Bonjour MIZA Équipement ! 👋\nJe suis intéressé(e) par :\n📦 *" . $product->name . "*\n🔖 Réf: " . $product->reference . "\n💰 Prix: *" . $product->price . " MAD*\nPlus d'infos s.v.p.";
                    $waUrl  = "https://wa.me/212622847769?text=" . urlencode($waMsg);
                @endphp
                <a href="{{ $waUrl }}" target="_blank"
                   class="flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold py-3.5 rounded-lg transition shadow-md shadow-green-100">
                    <i class="fab fa-whatsapp text-xl"></i> Demander sur WhatsApp
                </a>

                {{-- Trust badges --}}
                <div class="grid grid-cols-3 gap-3 mt-6 pt-6 border-t border-gray-100">
                    <div class="text-center">
                        <i class="fas fa-shield-halved text-2xl text-brand-primary mb-1 block"></i>
                        <p class="text-xs font-semibold text-gray-600">Garantie officielle</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-truck-fast text-2xl text-blue-500 mb-1 block"></i>
                        <p class="text-xs font-semibold text-gray-600">Livraison rapide</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-rotate-left text-2xl text-green-500 mb-1 block"></i>
                        <p class="text-xs font-semibold text-gray-600">Retour 7 jours</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Review Section --}}
    <section class="mt-10 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-10">
        <h2 class="text-xl font-extrabold text-gray-900 mb-6 flex items-center gap-2">
            <span class="w-1 h-6 bg-gradient-to-b from-brand-primary to-brand-secondary rounded-full inline-block"></span>
            Avis des clients
        </h2>
        @auth
            <form action="{{ route('review.store', $product->id) }}" method="POST" class="mb-8 p-5 bg-gray-50 rounded-xl border border-gray-200">
                @csrf
                <h3 class="font-bold text-gray-800 mb-3">Laisser un avis</h3>
                <div class="flex gap-4 mb-4">
                    <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="rating" value="1" required class="text-brand-primary"> 1 <i class="fas fa-star text-yellow-400 text-xs"></i></label>
                    <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="rating" value="2" required class="text-brand-primary"> 2 <i class="fas fa-star text-yellow-400 text-xs"></i></label>
                    <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="rating" value="3" required class="text-brand-primary"> 3 <i class="fas fa-star text-yellow-400 text-xs"></i></label>
                    <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="rating" value="4" required class="text-brand-primary"> 4 <i class="fas fa-star text-yellow-400 text-xs"></i></label>
                    <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="rating" value="5" required class="text-brand-primary" checked> 5 <i class="fas fa-star text-yellow-400 text-xs"></i></label>
                </div>
                <textarea name="comment" rows="3" placeholder="Partagez votre expérience avec ce produit..." class="w-full border-gray-200 rounded-lg p-3 text-sm focus:ring-brand-primary focus:border-brand-primary mb-3"></textarea>
                <button class="bg-gray-900 text-white font-bold py-2 px-6 rounded-lg text-sm hover:bg-gray-800 transition">Envoyer mon avis</button>
            </form>
        @else
            <div class="mb-8 p-5 bg-blue-50 text-blue-800 rounded-xl border border-blue-200 text-sm font-semibold">
                <a href="{{ route('login') }}" class="underline hover:text-blue-900">Connectez-vous</a> pour laisser un avis sur ce produit.
            </div>
        @endauth

        @php $reviews = \App\Models\Review::where('product_id', $product->id)->latest()->get(); @endphp
        @if($reviews->count() > 0)
            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-gray-800 text-sm">{{ $review->user->name ?? 'Anonyme' }}</span>
                            <div class="flex items-center gap-0.5 ml-2">
                                @for($i = 0; $i < $review->rating; $i++) <i class="fas fa-star text-yellow-400 text-[10px]"></i> @endfor
                                @for($i = $review->rating; $i < 5; $i++) <i class="fas fa-star text-gray-200 text-[10px]"></i> @endfor
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic text-sm text-center">Aucun avis pour l'instant. Soyez le premier !</p>
        @endif
    </section>

    {{-- Similar products --}}
    @if(isset($similarProducts) && $similarProducts->count() > 0)
    <section class="mt-10">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-extrabold text-gray-900 flex items-center gap-2">
                <span class="w-1 h-6 bg-gradient-to-b from-brand-primary to-brand-secondary rounded-full inline-block"></span>
                Produits similaires
            </h2>
            @if($product->category)
                <a href="{{ route('home', ['category' => $product->category_id]) }}" class="text-sm text-brand-primary font-bold hover:underline">
                    Voir tout <i class="fas fa-chevron-right text-xs"></i>
                </a>
            @endif
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($similarProducts as $sim)
                <a href="{{ route('produit.details', $sim->id) }}" class="product-card group">
                    <div class="product-image-wrap">
                        <img src="{{ $sim->image ? asset('storage/' . $sim->image) : 'https://placehold.co/400x400/f5f5f5/999?text=MIZA' }}"
                             alt="{{ $sim->name }}">
                        @if($sim->stock == 0)
                            <span class="badge-stock">Rupture</span>
                        @endif
                    </div>
                    <div class="product-body">
                        <p class="product-name">{{ $sim->name }}</p>
                        <div class="flex items-baseline gap-1 mt-auto pt-2">
                            <span class="product-price-current">{{ number_format($sim->price, 2) }}</span>
                            <span class="text-xs font-bold text-brand-primary">MAD</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function incrementQty() {
        const el = document.getElementById('qty');
        const max = parseInt(el.getAttribute('max') || 999);
        if (parseInt(el.value) < max) el.value = parseInt(el.value) + 1;
    }
    function decrementQty() {
        const el = document.getElementById('qty');
        if (parseInt(el.value) > 1) el.value = parseInt(el.value) - 1;
    }
</script>
@endpush
