@extends('layouts.public')

@section('title', $term ? 'Recherche : ' . $term : 'Rechercher un produit')

@section('content')
<div class="max-w-screen-xl mx-auto px-4 py-8">

    {{-- Search header --}}
    <div class="mb-6">
        @if($term)
            <h1 class="text-2xl font-extrabold text-gray-900">
                Résultats pour <span class="text-brand-primary">"{{ $term }}"</span>
                <span class="text-base font-normal text-gray-400 ml-2">— {{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }}</span>
            </h1>
        @else
            <h1 class="text-2xl font-extrabold text-gray-900">Tous les produits</h1>
        @endif
    </div>

    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Sidebar filters --}}
        <aside class="w-full lg:w-64 shrink-0">
            <form action="{{ route('search') }}" method="GET" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-5 sticky top-24">
                @if($term)<input type="hidden" name="q" value="{{ $term }}">@endif

                <h3 class="font-extrabold text-gray-900 text-sm uppercase tracking-wider">Filtrer</h3>

                {{-- Category --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catégorie</label>
                    <div class="space-y-1.5">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="category" value="" {{ !$categoryId ? 'checked' : '' }} class="text-brand-primary">
                            <span class="text-sm text-gray-600">Toutes</span>
                        </label>
                        @foreach($categories as $cat)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="category" value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'checked' : '' }} class="text-brand-primary">
                                <span class="text-sm text-gray-600">{{ $cat->name }}</span>
                                <span class="text-xs text-gray-400 ml-auto">({{ $cat->products_count }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Price range --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Prix (MAD)</label>
                    <div class="flex gap-2">
                        <input type="number" name="min_price" value="{{ $minPrice }}" placeholder="Min"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-primary">
                        <input type="number" name="max_price" value="{{ $maxPrice }}" placeholder="Max"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-primary">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-brand-primary hover:bg-red-700 text-white font-bold py-2.5 rounded-xl text-sm transition">
                    Appliquer les filtres
                </button>
                @if($term || $categoryId || $minPrice || $maxPrice)
                    <a href="{{ route('search') }}" class="block text-center text-xs text-gray-400 hover:text-brand-primary transition">
                        Effacer les filtres
                    </a>
                @endif
            </form>
        </aside>

        {{-- Products grid --}}
        <div class="flex-1">
            @if($products->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-6">
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
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="add-to-cart-btn">
                                            <i class="fas fa-cart-plus text-xs"></i> Ajouter au panier
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $products->withQueryString()->links() }}
            @else
                <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <i class="fas fa-search text-5xl text-gray-200 mb-4 block"></i>
                    <h3 class="text-xl font-bold text-gray-500 mb-2">Aucun résultat</h3>
                    <p class="text-gray-400 text-sm mb-5">Essayez avec d'autres mots clés ou parcourez nos catégories.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-brand-primary text-white font-bold py-3 px-7 rounded-xl hover:opacity-90 transition">
                        <i class="fas fa-home text-xs"></i> Retour à l'accueil
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
