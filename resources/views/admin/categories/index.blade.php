@extends('layouts.admin')
@section('title', 'Categories')
@section('subtitle', 'Gestion des categories de produits')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.categories.create') }}"
       class="inline-flex items-center gap-2 bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold px-5 py-2.5 rounded-xl text-sm hover:opacity-90 transition shadow-sm">
        <i class="fas fa-plus text-xs"></i> Nouvelle categorie
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-400 font-semibold uppercase tracking-wider border-b border-gray-100">
                    <th class="px-5 py-3 text-left">Icone</th>
                    <th class="px-5 py-3 text-left">Nom</th>
                    <th class="px-5 py-3 text-left">Description</th>
                    <th class="px-5 py-3 text-center">Produits</th>
                    <th class="px-5 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $cat)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4">
                            <div class="w-9 h-9 rounded-xl bg-brand-light flex items-center justify-center text-brand-primary">
                                <i class="{{ $cat->icon ?: 'fas fa-tag' }}"></i>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-bold text-gray-800">{{ $cat->name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $cat->slug }}</p>
                        </td>
                        <td class="px-5 py-4 text-gray-500 max-w-xs">
                            <p class="line-clamp-1">{{ $cat->description ?: '—' }}</p>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('products.index', ['category' => $cat->id]) }}"
                               class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-700 font-extrabold rounded-full text-xs hover:bg-blue-100 transition">
                                {{ $cat->products_count }}
                            </a>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.categories.edit', $cat->id) }}"
                                   class="inline-flex items-center gap-1 text-xs bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 font-semibold px-3 py-1.5 rounded-lg transition">
                                    <i class="fas fa-pen text-[10px]"></i> Modifier
                                </a>
                                @if($cat->products_count === 0)
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST"
                                          onsubmit="return confirm('Supprimer cette categorie ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 text-xs bg-red-50 hover:bg-red-100 text-red-600 font-semibold px-3 py-1.5 rounded-lg transition">
                                            <i class="fas fa-trash text-[10px]"></i> Supprimer
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-300 px-3 py-1.5" title="Impossible : contient des produits">
                                        <i class="fas fa-lock text-[10px]"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">Aucune categorie. Creez-en une !</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $categories->links() }}</div>
    @endif
</div>
@endsection