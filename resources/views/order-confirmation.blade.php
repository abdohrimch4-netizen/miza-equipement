@extends('layouts.public')

@section('title', 'Commande confirmée #' . str_pad($order->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Success header --}}
        <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-8 text-center text-white">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-circle-check text-3xl"></i>
            </div>
            <h1 class="text-2xl font-black mb-1">Commande confirmée !</h1>
            <p class="text-green-100">Merci {{ $order->nom_client }}, votre commande a été reçue.</p>
        </div>

        <div class="p-7">
            {{-- Order number + info --}}
            <div class="bg-gray-50 rounded-xl p-5 mb-6 flex flex-col sm:flex-row justify-between gap-4">
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Numéro de commande</p>
                    <p class="text-2xl font-black text-gray-900">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="text-right sm:text-right">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Montant total</p>
                    <p class="text-2xl font-black text-brand-primary">{{ number_format($order->total, 2) }} MAD</p>
                </div>
            </div>

            {{-- Delivery info --}}
            <div class="mb-6">
                <h3 class="text-sm font-extrabold text-gray-700 uppercase tracking-wider mb-3">Livraison à</h3>
                <div class="space-y-1 text-sm text-gray-600">
                    <p><i class="fas fa-user w-5 text-brand-primary"></i> {{ $order->nom_client }}</p>
                    <p><i class="fas fa-phone w-5 text-brand-primary"></i> {{ $order->telephone }}</p>
                    <p><i class="fas fa-map-marker-alt w-5 text-brand-primary"></i> {{ $order->adresse }}, {{ $order->ville }}</p>
                </div>
            </div>

            {{-- Order items --}}
            <div class="mb-6">
                <h3 class="text-sm font-extrabold text-gray-700 uppercase tracking-wider mb-3">Articles commandés</h3>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-extrabold text-brand-primary bg-brand-light px-2 py-0.5 rounded">{{ $item->quantity }}×</span>
                                <span class="text-sm text-gray-700">{{ $item->name ?? ($item->product->name ?? 'Produit') }}</span>
                            </div>
                            <span class="text-sm font-bold text-gray-800">{{ number_format(($item->price ?? 0) * $item->quantity, 2) }} MAD</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Next steps --}}
            <div class="bg-blue-50 rounded-xl p-4 mb-6 border border-blue-100">
                <h3 class="text-sm font-extrabold text-blue-800 mb-2 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> Prochaines étapes
                </h3>
                <ol class="list-decimal list-inside space-y-1 text-sm text-blue-700">
                    <li>Notre équipe va vous contacter sous 24h.</li>
                    <li>Confirmation de la date de livraison par WhatsApp.</li>
                    <li>Livraison à domicile à {{ $order->ville }}.</li>
                </ol>
            </div>

            {{-- Actions --}}
            @php
                $waMsg = "Bonjour MIZA ! 👋 Je viens de passer la commande #" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . " pour un montant de " . number_format($order->total, 2) . " MAD. Pouvez-vous me confirmer le délai de livraison ?";
                $waUrl = "https://wa.me/212622847769?text=" . urlencode($waMsg);
            @endphp
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ $waUrl }}" target="_blank"
                   class="flex-1 flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold py-3.5 rounded-xl transition">
                    <i class="fab fa-whatsapp text-lg"></i> Confirmer sur WhatsApp
                </a>
                <a href="{{ route('home') }}"
                   class="flex-1 flex items-center justify-center gap-2 border-2 border-brand-primary text-brand-primary hover:bg-brand-light font-bold py-3.5 rounded-xl transition">
                    <i class="fas fa-store text-sm"></i> Continuer les achats
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
