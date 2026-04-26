@extends('layouts.admin')
@section('title', 'Produits')
@section('subtitle', 'Catalogue complet des produits')

@section('content')

{{-- Stats bar --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    @foreach([['Produits total','fas fa-box-open','blue',$totalProducts],['Valeur stock','fas fa-coins','green',number_format($totalValue,0).' MAD'],['En vedette','fas fa-star','yellow',$featured],['Ruptures','fas fa-triangle-exclamation','red',$outOfStock]] as [$label,$icon,$color,$val])
        <div class="bg-white rounded-xl p-4 border border-{{ $color }}-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-{{ $color }}-50 text-{{ $color }}-600 flex items-center justify-center shrink-0"><i class="{{ $icon }}"></i></div>
            <div><p class="font-extrabold text-gray-900 text-lg leading-none">{{ $val }}</p><p class="text-xs text-gray-400 font-semibold">{{ $label }}</p></div>
        </div>
    @endforeach
</div>

{{-- Toolbar --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
    <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, reference..."
               class="flex-1 min-w-40 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-primary">
        <select name="category" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-brand-primary">
            <option value="">Toutes categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category')==$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="stock" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-brand-primary">
            <option value="">Tout le stock</option>
            <option value="out"  {{ request('stock')==='out' ? 'selected' : '' }}>Rupture (0)</option>
            <option value="low"  {{ request('stock')==='low' ? 'selected' : '' }}>Stock bas (1-5)</option>
            <option value="ok"   {{ request('stock')==='ok'  ? 'selected' : '' }}>Disponible (&gt;5)</option>
        </select>
        <button type="submit" class="bg-brand-primary text-white font-bold px-5 py-2.5 rounded-xl text-sm hover:opacity-90 transition">
            <i class="fas fa-search mr-1"></i>Filtrer
        </button>
        @if(request('search') || request('category') || request('stock'))
            <a href="{{ route('products.index') }}" class="border border-gray-200 text-gray-500 font-semibold px-4 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition">
                Effacer
            </a>
        @endif
        <div class="ml-auto">
            <a href="{{ route('products.create') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold px-5 py-2.5 rounded-xl text-sm hover:opacity-90 transition shadow-sm">
                <i class="fas fa-plus text-xs"></i> Ajouter un produit
            </a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-400 font-semibold uppercase tracking-wider border-b border-gray-100">
                    <th class="px-5 py-3 text-left">Image</th>
                    <th class="px-5 py-3 text-left">Produit</th>
                    <th class="px-5 py-3 text-left">Categorie</th>
                    <th class="px-5 py-3 text-right">Prix</th>
                    <th class="px-5 py-3 text-center">Stock</th>
                    <th class="px-5 py-3 text-center">Vedette</th>
                    <th class="px-5 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('produit.details', $product->id) }}" target="_blank">
                                <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/60x60/f5f5f5/999?text=M' }}"
                                     class="w-12 h-12 object-contain rounded-xl border border-gray-100 bg-gray-50 p-1 group-hover:shadow-sm transition">
                            </a>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-bold text-gray-800 line-clamp-1">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $product->reference ?: '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($product->category)
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full border border-blue-100">
                                    @if($product->category->icon)<i class="{{ $product->category->icon }} text-[10px]"></i>@endif
                                    {{ $product->category->name }}
                                </span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right font-extrabold text-gray-900">
                            {{ number_format($product->price,2) }} <span class="text-xs font-semibold text-gray-400">MAD</span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($product->stock <= 0)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-red-50 text-red-600 border border-red-200">Rupture</span>
                            @elseif($product->stock <= 5)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-orange-50 text-orange-600 border border-orange-200">{{ $product->stock }} bas</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold bg-green-50 text-green-700 border border-green-200">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($product->is_featured)
                                <i class="fas fa-star text-yellow-400" title="En vedette"></i>
                            @else
                                <i class="far fa-star text-gray-200"></i>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('products.edit', $product->id) }}"
                                   class="inline-flex items-center gap-1 text-xs bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 font-semibold px-3 py-1.5 rounded-lg transition">
                                    <i class="fas fa-pen text-[10px]"></i> Modifier
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                      onsubmit="return confirm('Supprimer ce produit ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 text-xs bg-red-50 hover:bg-red-100 text-red-600 font-semibold px-3 py-1.5 rounded-lg transition">
                                        <i class="fas fa-trash text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400">Aucun produit. <a href="{{ route('products.create') }}" class="text-brand-primary font-bold hover:underline">Ajouter le premier</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $products->links() }}</div>
    @endif
</div>
@endsection