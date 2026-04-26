@extends('layouts.admin')
@section('title', 'Modifier : ' . $category->name)
@section('subtitle', 'Modifier une categorie existante')

@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.categories') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brand-primary font-semibold transition mb-6">
        <i class="fas fa-arrow-left text-xs"></i> Retour aux categories
    </a>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-7">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <h2 class="font-extrabold text-gray-900 text-lg flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-brand-light flex items-center justify-center text-brand-primary">
                    <i class="{{ $category->icon ?: 'fas fa-tag' }}"></i>
                </div>
                {{ $category->name }}
            </h2>
            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-100">
                {{ $category->products_count }} produit(s)
            </span>
        </div>
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nom *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                       class="w-full bg-gray-50 border @error('name') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3"
                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition">{{ old('description', $category->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Icone Font Awesome</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition font-mono"
                       placeholder="fas fa-tag">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold py-3 rounded-xl text-sm hover:opacity-90 transition">
                    <i class="fas fa-save mr-1"></i> Enregistrer
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