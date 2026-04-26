@extends('layouts.public')
@section('title', 'Mes Favoris')
@section('content')
<div class="max-w-screen-xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-2">
        <i class="fas fa-heart text-red-500"></i> Mes Favoris
    </h1>
    @if($wishlists->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($wishlists as $wishlist)
                <div class="product-card">
                    <a href="{{ route('produit.details', $wishlist->product_id) }}" class="product-image-wrap">
                        <img src="{{ $wishlist->product->image ? asset('storage/' . $wishlist->product->image) : 'https://placehold.co/400' }}" alt="{{ $wishlist->product->name }}">
                    </a>
                    <div class="product-body">
                        <a href="{{ route('produit.details', $wishlist->product_id) }}" class="product-name">{{ $wishlist->product->name }}</a>
                        <div class="flex items-baseline gap-1 mt-auto">
                            <span class="product-price-current">{{ number_format($wishlist->product->price, 2) }}</span>
                            <span class="text-xs font-bold text-brand-primary">MAD</span>
                        </div>
                        <form action="{{ route('wishlist.toggle', $wishlist->product_id) }}" method="POST" class="mt-2">
                            @csrf
                            <button class="w-full bg-red-100 text-red-600 font-bold text-xs py-2 rounded shadow-sm hover:bg-red-200"><i class="fas fa-trash-alt"></i> Retirer</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center py-10">Vous n'avez aucun article dans vos favoris.</p>
    @endif
</div>
@endsection
