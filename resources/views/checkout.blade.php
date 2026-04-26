@extends('layouts.public')

@section('title', 'Valider la commande')

@section('content')
<div class="max-w-screen-xl mx-auto px-4 py-8">

    {{-- Step indicator --}}
    <div class="flex items-center justify-center gap-3 mb-8">
        <div class="flex items-center gap-2 text-brand-primary font-bold text-sm">
            <span class="w-7 h-7 rounded-full bg-brand-primary text-white flex items-center justify-center text-xs font-black">1</span>
            Panier
        </div>
        <div class="h-px w-10 bg-brand-primary"></div>
        <div class="flex items-center gap-2 text-brand-primary font-bold text-sm">
            <span class="w-7 h-7 rounded-full bg-brand-primary text-white flex items-center justify-center text-xs font-black">2</span>
            Livraison
        </div>
        <div class="h-px w-10 bg-gray-200"></div>
        <div class="flex items-center gap-2 text-gray-400 font-semibold text-sm">
            <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-xs font-black">3</span>
            Paiement
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Form --}}
        <div class="flex-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
                <h2 class="text-xl font-extrabold text-gray-900 mb-6 pb-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-brand-primary"></i> Adresse de livraison
                </h2>

                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nom & Prénom *</label>
                            <input type="text" name="nom_client" required value="{{ old('nom_client', Auth::user()->name ?? '') }}"
                                   class="w-full bg-gray-50 border @error('nom_client') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition"
                                   placeholder="Hassan Alami">
                            @error('nom_client')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Téléphone *</label>
                            <input type="tel" name="telephone" required value="{{ old('telephone') }}"
                                   class="w-full bg-gray-50 border @error('telephone') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition"
                                   placeholder="06 XX XX XX XX">
                            @error('telephone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ville *</label>
                        <input type="text" name="ville" required value="{{ old('ville', 'Safi') }}"
                               class="w-full bg-gray-50 border @error('ville') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition"
                               placeholder="Safi">
                        @error('ville')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Adresse complète *</label>
                        <textarea name="adresse" required rows="3"
                                  class="w-full bg-gray-50 border @error('adresse') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition"
                                  placeholder="Quartier, Rue, Numéro de maison…">{{ old('adresse') }}</textarea>
                        @error('adresse')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Notes de commande <span class="text-gray-400 font-normal">(optionnel)</span></label>
                        <textarea name="notes" rows="2"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition"
                                  placeholder="Instructions spéciales, étage, digicode…">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-black text-base py-4 rounded-xl hover:opacity-90 transition shadow-lg shadow-red-100 flex items-center justify-center gap-2">
                        Continuer vers le paiement <i class="fas fa-arrow-right"></i>
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-3">
                        <i class="fas fa-shield-alt text-green-500 mr-1"></i> Paiement 100% sécurisé
                    </p>
                </form>
            </div>
        </div>

        {{-- Order summary --}}
        <div class="w-full lg:w-80 shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                <h2 class="font-extrabold text-gray-900 mb-5 pb-4 border-b border-gray-100">Votre commande</h2>
                <div class="space-y-3 mb-5">
                    @php $total = 0; @endphp
                    @foreach(session('cart', []) as $details)
                        @php $sub = ($details['price'] ?? 0) * ($details['quantity'] ?? 1); $total += $sub; @endphp
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-extrabold text-brand-primary bg-brand-light px-1.5 py-0.5 rounded shrink-0">{{ $details['quantity'] ?? 1 }}×</span>
                                <span class="text-sm text-gray-700 leading-tight line-clamp-2">{{ $details['name'] ?? '' }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 shrink-0">{{ number_format($sub, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-100 pt-4 space-y-2">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Livraison</span><span class="text-green-600 font-semibold">Gratuite</span>
                    </div>
                    <div class="flex justify-between font-extrabold text-gray-900 text-lg">
                        <span>Total</span>
                        <span class="text-brand-primary">{{ number_format($total, 2) }} MAD</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
