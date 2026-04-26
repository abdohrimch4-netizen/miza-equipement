@extends('layouts.public')
@section('title', 'Suivi Commande')
@section('content')
<div class="max-w-xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-black text-center mb-8">Suivez votre commande</h1>
    <form action="{{ route('tracking.track') }}" method="POST" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-2">Numéro de Commande</label>
            <input type="text" name="order_id" class="w-full border-gray-200 rounded-lg" placeholder="Ex: 123" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">Numéro de Téléphone</label>
            <input type="text" name="telephone" class="w-full border-gray-200 rounded-lg" placeholder="Votre téléphone" required>
        </div>
        <button class="w-full font-bold bg-brand-primary text-white py-3 rounded-xl hover:opacity-90">Vérifier le statut</button>
    </form>
    
    @if(isset($order))
        <div class="mt-8 bg-blue-50 p-6 rounded-2xl border border-blue-100">
            <h3 class="font-bold text-lg mb-2 text-blue-900">Commande #{{ $order->id }}</h3>
            <p class="mb-4 text-blue-800">Statut actuel: <span class="font-bold bg-blue-200 px-2 py-1 rounded inline-block uppercase text-xs">{{ $order->statut }}</span></p>
            <ul class="text-sm font-medium text-blue-800">
                @foreach($order->items as $item)
                    <li>- {{ $item->name }} (x{{ $item->quantity }})</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
