@extends('layouts.admin')
@section('title', 'Commande #' . str_pad($order->id,5,'0',STR_PAD_LEFT))
@section('subtitle', 'Details et gestion du statut')

@section('content')
@php
  $statuses = ['pending'=>['label'=>'En attente','color'=>'yellow'],'processing'=>['label'=>'En traitement','color'=>'blue'],
               'shipped'=>['label'=>'Expediee','color'=>'purple'],'delivered'=>['label'=>'Livree','color'=>'green'],'cancelled'=>['label'=>'Annulee','color'=>'red']];
  $s = $statuses[$order->statut] ?? ['label'=>$order->statut,'color'=>'gray'];
@endphp

<div class="mb-5">
    <a href="{{ route('admin.orders') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brand-primary font-semibold transition">
        <i class="fas fa-arrow-left text-xs"></i> Retour aux commandes
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left column --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Order items --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-extrabold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-bag-shopping text-brand-primary"></i>
                    Articles commandés ({{ $order->items->count() }})
                </h2>
                <span class="font-extrabold text-brand-primary text-lg">{{ number_format($order->total,2) }} MAD</span>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4 px-6 py-4">
                        @if(isset($item->product) && $item->product?->image)
                            <img src="{{ asset('storage/'.$item->product->image) }}" class="w-14 h-14 object-contain rounded-xl border border-gray-100 bg-gray-50 p-1 shrink-0">
                        @else
                            <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas fa-box text-gray-300 text-xl"></i>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm line-clamp-1">{{ $item->name ?? ($item->product->name ?? 'Produit supprime') }}</p>
                            @if(isset($item->product) && $item->product)
                                <p class="text-xs text-gray-400 mt-0.5">Ref: {{ $item->product->reference ?? '-' }}</p>
                            @endif
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs text-gray-400">{{ number_format($item->price,2) }} × {{ $item->quantity }}</p>
                            <p class="font-extrabold text-gray-900">{{ number_format($item->price * $item->quantity,2) }} MAD</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <span class="text-sm font-semibold text-gray-500">Total commande</span>
                <span class="text-xl font-black text-brand-primary">{{ number_format($order->total,2) }} MAD</span>
            </div>
        </div>

        {{-- Client + delivery --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-gray-900 mb-5 flex items-center gap-2">
                <i class="fas fa-user text-brand-primary"></i> Client &amp; Livraison
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-2">Coordonnees</p>
                    <div class="space-y-2 text-sm text-gray-700">
                        <p class="flex items-center gap-2"><i class="fas fa-user w-4 text-brand-primary shrink-0"></i> <strong>{{ $order->nom_client }}</strong></p>
                        <p class="flex items-center gap-2"><i class="fas fa-phone w-4 text-brand-primary shrink-0"></i> {{ $order->telephone }}</p>
                        <p class="flex items-center gap-2"><i class="fas fa-city w-4 text-brand-primary shrink-0"></i> {{ $order->ville }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-2">Adresse</p>
                    <p class="text-sm text-gray-700 leading-relaxed"><i class="fas fa-map-marker-alt text-brand-primary mr-1"></i> {{ $order->adresse }}</p>
                    @if($order->notes)
                        <div class="mt-3 bg-yellow-50 border border-yellow-100 rounded-lg px-3 py-2">
                            <p class="text-xs font-semibold text-yellow-700 mb-1"><i class="fas fa-note-sticky mr-1"></i> Note client :</p>
                            <p class="text-xs text-yellow-800">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div class="space-y-5">

        {{-- Status card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-arrows-rotate text-brand-primary"></i> Statut de la commande
            </h2>

            {{-- Timeline --}}
            <div class="space-y-2 mb-5">
                @foreach($statuses as $key => $info)
                    @php $isCurrent = $order->statut === $key; $isPast = array_search($key, array_keys($statuses)) < array_search($order->statut, array_keys($statuses)); @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 text-xs font-black
                                    {{ $isCurrent ? 'bg-brand-primary text-white' : ($isPast ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-400') }}">
                            @if($isPast) <i class="fas fa-check text-[10px]"></i>
                            @elseif($isCurrent) <i class="fas fa-circle text-[8px]"></i>
                            @else <span class="text-[10px]">·</span>
                            @endif
                        </div>
                        <span class="text-sm {{ $isCurrent ? 'font-extrabold text-gray-900' : ($isPast ? 'font-semibold text-gray-400 line-through' : 'text-gray-400') }}">
                            {{ $info['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- Update form --}}
            <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                @csrf
                <select name="statut" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-primary bg-white mb-3">
                    @foreach($statuses as $key => $info)
                        <option value="{{ $key }}" {{ $order->statut === $key ? 'selected' : '' }}>{{ $info['label'] }}</option>
                    @endforeach
                </select>
                <button type="submit"
                        class="w-full bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold py-2.5 rounded-xl text-sm hover:opacity-90 transition">
                    <i class="fas fa-save mr-1"></i> Mettre a jour
                </button>
            </form>
        </div>

        {{-- Payment info --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-credit-card text-brand-primary"></i> Paiement
            </h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400 font-semibold">Statut</span>
                    @if($order->payment_status === 'paid')
                        <span class="font-bold text-green-600 flex items-center gap-1"><i class="fas fa-check-circle text-xs"></i> Paye</span>
                    @else
                        <span class="font-bold text-red-500">Impaye</span>
                    @endif
                </div>
                @if($order->payment_method)
                    <div class="flex justify-between">
                        <span class="text-gray-400 font-semibold">Methode</span>
                        <span class="font-semibold text-gray-700">{{ $order->payment_method }}</span>
                    </div>
                @endif
                <div class="flex justify-between pt-2 border-t border-gray-100">
                    <span class="font-extrabold text-gray-900">Total</span>
                    <span class="font-extrabold text-brand-primary text-lg">{{ number_format($order->total,2) }} MAD</span>
                </div>
            </div>
        </div>

        {{-- WhatsApp --}}
        @php
            $waMsg = "Bonjour " . $order->nom_client . " ! Votre commande MIZA #" . str_pad($order->id,5,'0',STR_PAD_LEFT) . " est en cours de traitement. Nous vous confirmons la livraison sous peu. Merci de votre confiance !";
        @endphp
        <a href="https://wa.me/{{ preg_replace('/\D/','',$order->telephone) }}?text={{ urlencode($waMsg) }}" target="_blank"
           class="flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition w-full">
            <i class="fab fa-whatsapp text-lg"></i> Contacter le client
        </a>
    </div>
</div>
@endsection