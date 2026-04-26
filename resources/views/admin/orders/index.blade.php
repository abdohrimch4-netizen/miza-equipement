@extends('layouts.admin')
@section('title', 'Commandes')
@section('subtitle', 'Gestion des commandes clients')

@section('content')
@php
  $statuses = ['pending'=>['label'=>'En attente','color'=>'yellow'],'processing'=>['label'=>'En traitement','color'=>'blue'],
               'shipped'=>['label'=>'Expediee','color'=>'purple'],'delivered'=>['label'=>'Livree','color'=>'green'],'cancelled'=>['label'=>'Annulee','color'=>'red']];
@endphp

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm text-center">
        <p class="text-2xl font-black text-gray-900">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-400 font-semibold mt-0.5">Total</p>
    </div>
    <div class="bg-white rounded-xl p-4 border border-yellow-100 shadow-sm text-center">
        <p class="text-2xl font-black text-yellow-600">{{ $stats['pending'] }}</p>
        <p class="text-xs text-gray-400 font-semibold mt-0.5">En attente</p>
    </div>
    <div class="bg-white rounded-xl p-4 border border-blue-100 shadow-sm text-center">
        <p class="text-2xl font-black text-blue-600">{{ $stats['processing'] }}</p>
        <p class="text-xs text-gray-400 font-semibold mt-0.5">En traitement</p>
    </div>
    <div class="bg-white rounded-xl p-4 border border-purple-100 shadow-sm text-center">
        <p class="text-2xl font-black text-purple-600">{{ $stats['shipped'] }}</p>
        <p class="text-xs text-gray-400 font-semibold mt-0.5">Expediees</p>
    </div>
    <div class="bg-white rounded-xl p-4 border border-green-100 shadow-sm text-center">
        <p class="text-2xl font-black text-green-600">{{ $stats['delivered'] }}</p>
        <p class="text-xs text-gray-400 font-semibold mt-0.5">Livrees</p>
    </div>
    <div class="bg-white rounded-xl p-4 border border-brand-primary/20 shadow-sm text-center">
        <p class="text-xl font-black text-brand-primary">{{ number_format($stats['revenue'],0) }}</p>
        <p class="text-xs text-gray-400 font-semibold mt-0.5">Revenus MAD</p>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-5">
    <form action="{{ route('admin.orders') }}" method="GET" class="flex flex-wrap gap-3 p-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, telephone, #ID..."
               class="flex-1 min-w-40 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-primary">
        <select name="statut" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-primary bg-white">
            <option value="">Tous les statuts</option>
            @foreach($statuses as $key=>$s)
                <option value="{{ $key }}" {{ request('statut')===$key ? 'selected' : '' }}>{{ $s['label'] }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-brand-primary text-white font-bold px-5 py-2.5 rounded-xl text-sm hover:opacity-90 transition">
            <i class="fas fa-search mr-1"></i> Filtrer
        </button>
        @if(request('search') || request('statut'))
            <a href="{{ route('admin.orders') }}" class="border border-gray-200 text-gray-500 font-semibold px-5 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition">
                Effacer
            </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-400 font-semibold uppercase tracking-wider border-b border-gray-100">
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Client</th>
                    <th class="px-5 py-3 text-left">Ville</th>
                    <th class="px-5 py-3 text-center">Articles</th>
                    <th class="px-5 py-3 text-right">Total</th>
                    <th class="px-5 py-3 text-center">Statut</th>
                    <th class="px-5 py-3 text-center">Paiement</th>
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                    @php $s = $statuses[$order->statut] ?? ['label'=>$order->statut,'color'=>'gray']; @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5 font-mono text-xs text-gray-400 font-semibold">#{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</td>
                        <td class="px-5 py-3.5">
                            <p class="font-bold text-gray-800 text-sm">{{ $order->nom_client }}</p>
                            <p class="text-xs text-gray-400">{{ $order->telephone }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-500">{{ $order->ville }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 bg-gray-100 text-gray-700 font-extrabold rounded-full text-xs">{{ $order->items->count() }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-extrabold text-gray-900">{{ number_format($order->total,2) }} <span class="text-xs font-semibold text-gray-400">MAD</span></td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-{{ $s['color'] }}-50 text-{{ $s['color'] }}-700 border border-{{ $s['color'] }}-200">
                                {{ $s['label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($order->payment_status === 'paid')
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                    <i class="fas fa-check text-[10px]"></i> Paye
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">Impaye</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-400">{{ $order->created_at->format('d/m/Y') }}<br>{{ $order->created_at->format('H:i') }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <a href="{{ route('admin.order.show', $order->id) }}"
                               class="inline-flex items-center gap-1 text-xs bg-brand-primary text-white font-bold px-3 py-1.5 rounded-lg hover:opacity-90 transition">
                                <i class="fas fa-eye text-[10px]"></i> Voir
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-5 py-12 text-center text-gray-400 text-sm">Aucune commande trouvee.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
    @endif
</div>
@endsection