<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Détails de la Commande #{{ $order->id }}</h2>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border border-gray-200">
                
                <div class="flex justify-between items-start border-b pb-6 mb-6">
                    <div>
                        <h3 class="text-2xl font-black text-blue-900">{{ $order->nom_client }}</h3>
                        <p class="text-gray-500"><i class="fas fa-phone mr-2"></i>{{ $order->telephone }}</p>
                        <p class="text-gray-500"><i class="fas fa-map-marker-alt mr-2"></i>{{ $order->adresse }}, {{ $order->ville }}</p>
                    </div>
                    <div class="text-right">
                        <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST" class="flex flex-col gap-2">
                            @csrf
                            <select name="statut" class="rounded-lg border-gray-300 text-sm font-bold">
                                <option value="En attente" {{ $order->statut == 'En attente' ? 'selected' : '' }}>En attente</option>
                                <option value="Expédiée" {{ $order->statut == 'Expédiée' ? 'selected' : '' }}>Expédiée</option>
                                <option value="Livrée" {{ $order->statut == 'Livrée' ? 'selected' : '' }}>Livrée</option>
                                <option value="Annulée" {{ $order->statut == 'Annulée' ? 'selected' : '' }}>Annulée</option>
                            </select>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-700 transition">Mettre à jour</button>
                        </form>
                    </div>
                </div>

                <h4 class="font-bold text-lg mb-4 text-gray-800">Articles commandés :</h4>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-4">
                            <span class="bg-blue-100 text-blue-700 font-black px-3 py-1 rounded-lg text-sm">{{ $item->quantite }}x</span>
                            <span class="font-bold text-gray-800">{{ $item->product->name }}</span>
                        </div>
                        <span class="font-black text-gray-900">{{ number_format($item->prix * $item->quantite, 2) }} MAD</span>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-700">Total payé :</span>
                    <span class="text-3xl font-black text-red-600">{{ number_format($order->total, 2) }} MAD</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>