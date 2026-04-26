@extends('layouts.admin')
@section('title', 'Nouvelle categorie')
@section('subtitle', 'Ajouter une categorie de produits')

@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.categories') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brand-primary font-semibold transition mb-6">
        <i class="fas fa-arrow-left text-xs"></i> Retour aux categories
    </a>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-7">
        <h2 class="font-extrabold text-gray-900 text-lg mb-6 pb-4 border-b border-gray-100 flex items-center gap-2">
            <i class="fas fa-tag text-brand-primary"></i> Nouvelle categorie
        </h2>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nom * <span class="font-normal text-gray-400">(unique)</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full bg-gray-50 border @error('name') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition"
                       placeholder="Ex : Electromenager">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition"
                          placeholder="Description courte de la categorie...">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    Icone Font Awesome <span class="font-normal text-gray-400">(classe CSS)</span>
                </label>
                <input type="text" name="icon" value="{{ old('icon', 'fas fa-tag') }}"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition font-mono"
                       placeholder="fas fa-tv, fas fa-blender, fas fa-couch...">
                <p class="text-xs text-gray-400 mt-1">Exemple : <code class="bg-gray-100 px-1 rounded">fas fa-tv</code> pour television</p>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold py-3 rounded-xl text-sm hover:opacity-90 transition">
                    <i class="fas fa-plus mr-1"></i> Creer la categorie
                </button>
                <a href="{{ route('admin.categories') }}"
                   class="flex-1 text-center border border-gray-200 text-gray-600 font-bold py-3 rounded-xl text-sm hover:bg-gray-50 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection