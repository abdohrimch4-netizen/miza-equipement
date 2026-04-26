@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('subtitle', 'Vue generale de la boutique')

@section('content')

{{-- Stats cards --}}
@php
    $totalOrders   = \App\Models\Order::count();
    $revenue       = \App\Models\Order::where('payment_status', 'paid')->sum('total');
    $totalProducts = \App\Models\Product::count();
    $outOfStock    = \App\Models\Product::where('stock', 0)->count();
    $pendingOrders = \App\Models\Order::where('statut', 'pending')->count();
    $recentOrders  = \App\Models\Order::with('items')->latest()->take(8)->get();
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-bag-shopping"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-900">{{ $totalOrders }}</p>
            <p class="text-xs text-gray-400 font-semibold">Total commandes</p>
            @if($pendingOrders > 0)
                <p class="text-xs text-yellow-600 font-bold mt-0.5">{{ $pendingOrders }} en attente</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-coins"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-900">{{ number_format($revenue, 0) }}</p>
            <p class="text-xs text-gray-400 font-semibold">Revenus totaux (MAD)</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-box-open"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-900">{{ $totalProducts }}</p>
            <p class="text-xs text-gray-400 font-semibold">Produits en catalogue</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-50 text-brand-primary flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-brand-primary">{{ $outOfStock }}</p>
            <p class="text-xs text-gray-400 font-semibold">Ruptures de stock</p>
        </div>
    </div>
</div>

{{-- Recent orders table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h2 class="font-extrabold text-gray-900 flex items-center gap-2">
            <i class="fas fa-clock-rotate-left text-brand-primary"></i> Commandes recentes
        </h2>
        <a href="{{ route('products.index') }}" class="text-xs text-brand-primary font-bold hover:underline">Voir les produits</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-400 font-semibold uppercase tracking-wider">
                    <th class="px-5 py-3 text-left">#</th>
                    <th class="px-5 py-3 text-left">Client</th>
                    <th class="px-5 py-3 text-left">Ville</th>
                    <th class="px-5 py-3 text-right">Total</th>
                    <th class="px-5 py-3 text-center">Statut</th>
                    <th class="px-5 py-3 text-center">Paiement</th>
                    <th class="px-5 py-3 text-left">Date</th>
                    <th class="px-5 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-mono text-xs text-gray-400">#{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-800">{{ $order->nom_client }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $order->ville }}</td>
                        <td class="px-5 py-3 text-right font-extrabold text-gray-900">{{ number_format($order->total,2) }} MAD</td>
                        <td class="px-5 py-3 text-center">
                            @php $colors = ['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red']; $c = $colors[$order->statut] ?? 'gray'; @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-{{ $c }}-50 text-{{ $c }}-700 border border-{{ $c }}-200">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if($order->payment_status === 'paid')
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700"><i class="fas fa-check-circle text-[10px]"></i> Paye</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">En attente</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3 text-center">
                            <a href="{{ route('admin.order.show', $order->id) }}"
                               class="inline-flex items-center gap-1 text-xs text-brand-primary hover:underline font-semibold">
                                <i class="fas fa-eye text-[10px]"></i> Voir
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">Aucune commande pour le moment.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection