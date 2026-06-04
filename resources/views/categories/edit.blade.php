@extends('layouts.app')
@section('title', 'Edit Category')
@section('subtitle', 'Update category information')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-primary-600 text-white flex items-center gap-3">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-folder-open text-sm"></i>
        </div>
        <div>
            <h3 class="text-sm font-heading font-semibold">Edit Category</h3>
            <p class="text-xs text-primary-200">Updating {{ $category->name }}</p>
        </div>
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route('categories.update', $category) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block">Category Name</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('name') border-red-300 @enderror"
                        placeholder="Enter category name">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block">Parent Category <span class="text-xs text-gray-400 font-normal">(optional)</span></label>
                    <select name="parent_id"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('parent_id') border-red-300 @enderror">
                        <option value="">— None (Root Category) —</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block">Image <span class="text-xs text-gray-400 font-normal">(leave empty to keep current)</span></label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100 focus:outline-none @error('image') border-red-300 @enderror">
                    @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                @if($category->image)
                <div>
                    <p class="text-xs text-gray-500 mb-1.5">Current Image:</p>
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                </div>
                @endif
            </div>

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('categories.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                    <i class="fas fa-save mr-1.5"></i>Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
