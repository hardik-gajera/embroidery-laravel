@extends('layouts.app')
@section('title', 'Add Category')
@section('subtitle', 'Create a new category')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-primary-600 text-white flex items-center gap-3">
        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
            <i class="fas fa-folder-plus text-sm"></i>
        </div>
        <div>
            <h3 class="text-sm font-heading font-semibold">New Category</h3>
            <p class="text-xs text-primary-200">Add a new design category</p>
        </div>
    </div>

    <div class="p-6">
        <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block">Category Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
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
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                    @error('parent_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700 mb-1.5 block">Image</label>
                    <input type="file" name="image" accept="image/*" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100 focus:outline-none @error('image') border-red-300 @enderror">
                    @error('image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('categories.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">Cancel</a>
                <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
                    <i class="fas fa-check mr-1.5"></i>Save Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
