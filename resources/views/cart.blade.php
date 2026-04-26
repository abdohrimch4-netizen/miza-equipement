@extends('layouts.public')

@section('title', 'Mon Panier')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Page title --}}
    <div class="flex items-center justify-between mb-7">
        <h1 class="text-2xl font-black text-gray-900 flex items-center gap-3">
            <span class="w-1 h-8 bg-gradient-to-b from-brand-primary to-brand-secondary rounded-full inline-block"></span>
            Mon Panier
            @if(session('cart') && count(session('cart')) > 0)
                <span class="text-base font-semibold text-gray-400">({{ collect(session('cart'))->sum('quantity') }} article{{ collect(session('cart'))->sum('quantity') > 1 ? 's' : '' }})</span>
            @endif
        </h1>
        <a href="{{ route('home') }}" class="text-sm text-brand-primary font-bold hover:underline flex items-center gap-1">
            <i class="fas fa-arrow-left text-xs"></i> Continuer mes achats
        </a>
    </div>

    @if(session('cart') && count(session('cart')) > 0)
        @php $total = 0; @endphp
        <div class="flex flex-col lg:flex-row gap-6">

            {{-- Cart items --}}
            <div class="flex-1 space-y-3">
                @foreach(session('cart') as $id => $details)
                    @php $subtotal = ($details['price'] ?? 0) * ($details['quantity'] ?? 1); $total += $subtotal; @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex gap-4 items-center">

                        {{-- Image --}}
                        <a href="{{ route('produit.details', $id) }}" class="shrink-0">
                            <img src="{{ isset($details['image']) && $details['image'] ? asset('storage/' . $details['image']) : 'https://placehold.co/90x90/f5f5f5/999?text=MIZA' }}"
                                 alt="{{ $details['name'] ?? '' }}"
                                 class="w-20 h-20 object-contain rounded-xl border border-gray-100 bg-gray-50 p-1">
                        </a>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('produit.details', $id) }}" class="font-bold text-gray-800 hover:text-brand-primary transition line-clamp-2 text-sm">{{ $details['name'] ?? '' }}</a>
                            <p class="text-xs text-gray-400 mt-0.5">{{ number_format($details['price'] ?? 0, 2) }} MAD / unité</p>

                            {{-- Quantity controls --}}
                            <div class="flex items-center justify-between mt-3">
                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                        <button type="submit" name="action" value="decrement"
                                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-50 font-bold text-lg transition">−</button>
                                        <span class="w-10 h-8 flex items-center justify-center text-sm font-bold text-gray-700 border-x border-gray-200">{{ $details['quantity'] ?? 1 }}</span>
                                        <button type="submit" name="action" value="increment"
                                                class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-50 font-bold text-lg transition">+</button>
                                    </div>
                                </form>
                                <span class="font-extrabold text-brand-primary text-lg">{{ number_format($subtotal, 2) }} MAD</span>
                            </div>
                        </div>

                        {{-- Remove --}}
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf @method('DELETE')
                            <input type="hidden" name="id" value="{{ $id }}">
                            <button type="submit" class="text-gray-300 hover:text-red-500 transition p-2 rounded-lg hover:bg-red-50" title="Supprimer">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            {{-- Order Summary --}}
            <div class="w-full lg:w-80 shrink-0">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                    <h2 class="font-extrabold text-gray-900 mb-5 pb-4 border-b border-gray-100">Récapitulatif</h2>

                    <div class="space-y-3 mb-5">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Sous-total</span>
                            <span class="font-semibold text-gray-800">{{ number_format($total, 2) }} MAD</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Livraison</span>
                            <span class="font-semibold text-green-600">Gratuite à Safi</span>
                        </div>
                        @if(session('coupon'))
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Code promo ({{ session('coupon.code') }})</span>
                            <span class="font-semibold">-{{ session('coupon.type') == 'fixed' ? session('coupon.value') . ' MAD' : session('coupon.value') . '%' }}</span>
                        </div>
                        @endif
                        @php
                            if (session()->has('coupon')) {
                                $coupon = session('coupon');
                                if ($coupon['type'] == 'fixed') {
                                    $total = max(0, $total - $coupon['value']);
                                } else {
                                    $total = $total - ($total * ($coupon['value'] / 100));
                                }
                            }
                        @endphp
                    </div>

                    <div class="flex justify-between font-extrabold text-gray-900 pt-4 border-t border-gray-100 text-lg mb-5">
                        <span>Total</span>
                        <span class="text-brand-primary">{{ number_format($total, 2) }} MAD</span>
                    </div>

                    <a href="{{ route('checkout') }}"
                       class="block w-full text-center bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition shadow-md shadow-red-100 mb-3">
                        <i class="fas fa-lock mr-2 text-xs"></i>Valider la commande
                    </a>

                    <form action="{{ route('cart.coupon') }}" method="POST" class="mb-5 flex gap-2">
                        @csrf
                        <input type="text" name="code" placeholder="Code promo" class="flex-1 border-gray-200 rounded-lg text-sm px-3 focus:border-brand-primary focus:ring-0">
                        <button class="bg-gray-800 text-white px-4 rounded-lg font-bold hover:bg-gray-700 transition">OK</button>
                    </form>

                    <a href="https://wa.me/212622847769" target="_blank"
                       class="block w-full text-center bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition text-sm">
                        <i class="fab fa-whatsapp mr-2"></i>Commander via WhatsApp
                    </a>

                    {{-- Trust --}}
                    <div class="mt-5 pt-4 border-t border-gray-100 space-y-2">
                        <p class="flex items-center gap-2 text-xs text-gray-500"><i class="fas fa-shield-halved text-brand-primary"></i> Paiement 100% sécurisé</p>
                        <p class="flex items-center gap-2 text-xs text-gray-500"><i class="fas fa-truck-fast text-blue-500"></i> Livraison gratuite à Safi</p>
                        <p class="flex items-center gap-2 text-xs text-gray-500"><i class="fas fa-rotate-left text-green-500"></i> Retour sous 7 jours</p>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- Empty cart --}}
        <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-bag-shopping text-4xl text-gray-300"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-600 mb-2">Votre panier est vide</h3>
            <p class="text-gray-400 text-sm mb-6">Découvrez nos produits et ajoutez-les à votre panier.</p>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold py-3 px-8 rounded-xl shadow-md hover:opacity-90 transition">
                <i class="fas fa-arrow-left text-xs"></i> Voir nos produits
            </a>
        </div>
    @endif
</div>
@endsection
