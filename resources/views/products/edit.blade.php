@extends('layouts.admin')
@section('title', 'Modifier : ' . Str::limit($product->name,35))
@section('subtitle', 'Mettre a jour les informations du produit')

@section('content')
<div class="max-w-3xl">
<a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brand-primary font-semibold transition mb-6">
    <i class="fas fa-arrow-left text-xs"></i> Retour aux produits
</a>

<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main info --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-gray-900 mb-5 pb-4 border-b border-gray-100">Informations generales</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nom du produit *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full bg-gray-50 border @error('name') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Reference</label>
                        <input type="text" name="reference" value="{{ old('reference', $product->reference) }}"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition font-mono">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Categorie</label>
                        <select name="category_id"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition">
                            <option value="">Sans categorie</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Prix (MAD) *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required step="0.01" min="0"
                               class="w-full bg-gray-50 border @error('price') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition">
                        @error('price')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Stock *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                               class="w-full bg-gray-50 border @error('stock') border-red-400 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 transition">
                        @error('stock')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">

        {{-- Current image --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-gray-900 mb-4 pb-3 border-b border-gray-100 text-sm">Image principale</h2>
            @if($product->image)
                <div class="mb-3 relative group">
                    <img src="{{ asset('storage/'.$product->image) }}" class="w-full max-h-40 object-contain rounded-xl border border-gray-100 bg-gray-50 p-2">
                    <p class="text-xs text-gray-400 text-center mt-1">Image actuelle</p>
                </div>
            @endif
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-brand-primary transition cursor-pointer"
                 onclick="document.getElementById('main-img').click()">
                <div id="img-preview" class="hidden mb-2">
                    <img id="img-preview-src" class="max-h-32 mx-auto object-contain rounded-lg">
                </div>
                <div id="img-placeholder">
                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 mb-1 block"></i>
                    <p class="text-xs text-gray-400 font-semibold">{{ $product->image ? 'Remplacer' : 'Choisir' }}</p>
                </div>
                <input type="file" id="main-img" name="image" accept="image/*" class="hidden"
                       onchange="previewImage(this,'img-preview','img-preview-src','img-placeholder')">
            </div>
        </div>

        {{-- Gallery --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-gray-900 mb-4 pb-3 border-b border-gray-100 text-sm">Galerie</h2>
            @if($product->gallery && count($product->gallery) > 0)
                <div class="grid grid-cols-3 gap-2 mb-3">
                    @foreach($product->gallery as $img)
                        <div class="relative group">
                            <img src="{{ asset('storage/'.$img) }}" class="w-full h-14 object-contain rounded-lg border border-gray-100 bg-gray-50">
                            <form action="{{ route('products.update', $product->id) }}" method="POST" class="absolute top-0.5 right-0.5">
                                @csrf @method('PUT')
                                <input type="hidden" name="name" value="{{ $product->name }}">
                                <input type="hidden" name="price" value="{{ $product->price }}">
                                <input type="hidden" name="stock" value="{{ $product->stock }}">
                                <input type="hidden" name="remove_gallery" value="{{ $img }}">
                                <button type="submit" title="Supprimer"
                                        class="w-5 h-5 bg-red-500 text-white rounded-full text-[10px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
            <label class="border-2 border-dashed border-gray-200 rounded-xl p-3 text-center hover:border-brand-primary transition cursor-pointer flex flex-col items-center">
                <i class="fas fa-images text-xl text-gray-300 mb-1"></i>
                <p class="text-xs text-gray-400 font-semibold">Ajouter des photos</p>
                <input type="file" name="gallery[]" accept="image/*" multiple class="hidden"
                       onchange="previewGallery(this)">
            </label>
            <div id="gallery-preview" class="grid grid-cols-3 gap-2 mt-3"></div>
        </div>

        {{-- Options --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-gray-900 mb-4 pb-3 border-b border-gray-100 text-sm">Options</h2>
            <label class="flex items-center gap-3 cursor-pointer">
                <div class="relative">
                    <input type="checkbox" name="is_featured" value="1"
                           {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-10 h-5 bg-gray-200 peer-checked:bg-brand-primary rounded-full transition"></div>
                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-700">En vedette</p>
                    <p class="text-xs text-gray-400">Flash Deals sur la homepage</p>
                </div>
            </label>
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-brand-primary to-brand-secondary text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition shadow-md">
            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
        </button>
        <a href="{{ route('products.index') }}" class="block w-full text-center border border-gray-200 text-gray-500 font-bold py-3 rounded-xl hover:bg-gray-50 transition text-sm">
            Annuler
        </a>
    </div>
</div>
</form>
</div>

@push('scripts')
<script>
function previewImage(input, previewDivId, previewImgId, placeholderId) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById(previewImgId).src = e.target.result;
        document.getElementById(previewDivId).classList.remove('hidden');
        document.getElementById(placeholderId).classList.add('hidden');
    };
    reader.readAsDataURL(file);
}
function previewGallery(input) {
    const container = document.getElementById('gallery-preview');
    container.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full h-14 object-contain rounded-lg border border-gray-100 bg-gray-50';
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush
@endsection