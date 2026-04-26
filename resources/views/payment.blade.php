@extends('layouts.public')

@section('title', 'Paiement sécurisé')

@section('content')
<div class="max-w-lg mx-auto px-4 py-10">

    {{-- Step indicator --}}
    <div class="flex items-center justify-center gap-3 mb-8">
        <div class="flex items-center gap-2 text-gray-400 font-semibold text-sm">
            <span class="w-7 h-7 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-black"><i class="fas fa-check text-xs"></i></span>
            Panier
        </div>
        <div class="h-px w-10 bg-green-400"></div>
        <div class="flex items-center gap-2 text-gray-400 font-semibold text-sm">
            <span class="w-7 h-7 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-black"><i class="fas fa-check text-xs"></i></span>
            Livraison
        </div>
        <div class="h-px w-10 bg-brand-primary"></div>
        <div class="flex items-center gap-2 text-brand-primary font-bold text-sm">
            <span class="w-7 h-7 rounded-full bg-brand-primary text-white flex items-center justify-center text-xs font-black">3</span>
            Paiement
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-brand-dark to-brand-navy p-6 text-center text-white">
            <div class="w-14 h-14 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-lock text-2xl text-white"></i>
            </div>
            <h2 class="text-xl font-black">Paiement Sécurisé</h2>
            <p class="text-gray-400 text-sm mt-1">Commande <strong class="text-white">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></p>
        </div>

        <div class="p-7">
            {{-- Amount --}}
            <div class="bg-brand-light rounded-xl p-4 flex justify-between items-center mb-6">
                <span class="text-sm font-semibold text-gray-600">Montant à payer</span>
                <span class="text-2xl font-black text-brand-primary">{{ number_format($order->total, 2) }} MAD</span>
            </div>

            {{-- Client info --}}
            <div class="bg-gray-50 rounded-xl p-4 mb-6 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Client</span>
                    <span class="font-semibold text-gray-800">{{ $order->nom_client }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Téléphone</span>
                    <span class="font-semibold text-gray-800">{{ $order->telephone }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Ville</span>
                    <span class="font-semibold text-gray-800">{{ $order->ville }}</span>
                </div>
            </div>

            {{-- Payment form --}}
            <form action="{{ route('payment.process', $order->id) }}" method="POST">
                @csrf

                <div class="mb-4 relative">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Numéro de carte</label>
                    <div class="relative">
                        <i class="fas fa-credit-card absolute top-1/2 -translate-y-1/2 left-4 text-gray-400"></i>
                        <input type="text" placeholder="0000 0000 0000 0000" maxlength="19"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition font-mono tracking-widest">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Expiration</label>
                        <input type="text" placeholder="MM/AA" maxlength="5"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition font-mono text-center">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">CVC</label>
                        <input type="password" placeholder="•••" maxlength="3"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition font-mono text-center">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-black text-base py-4 rounded-xl hover:opacity-90 transition shadow-lg shadow-red-100 flex items-center justify-center gap-2">
                    <i class="fas fa-lock text-sm"></i>
                    Payer {{ number_format($order->total, 2) }} MAD
                </button>

                <div class="flex items-center justify-center gap-4 mt-5">
                    <i class="fab fa-cc-visa text-3xl text-blue-700 opacity-50"></i>
                    <i class="fab fa-cc-mastercard text-3xl text-red-600 opacity-50"></i>
                    <i class="fab fa-cc-paypal text-3xl text-blue-500 opacity-50"></i>
                </div>
            </form>
        </div>
    </div>

    <p class="text-center text-xs text-gray-400 mt-4">
        <i class="fas fa-shield-alt mr-1 text-green-500"></i>
        Mode de simulation — Données de carte non enregistrées.
    </p>
</div>
@endsection
