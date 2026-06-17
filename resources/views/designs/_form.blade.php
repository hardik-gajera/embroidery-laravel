<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Design Name <span class="text-red-400">*</span></label>
        <input type="text" name="name" value="{{ old('name', $design->name ?? '') }}" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('name') border-red-300 @enderror"
            placeholder="Enter design name">
        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    @if(isset($design) && $design->design_code)
    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Design Code</label>
        <input type="text" value="{{ $design->design_code }}" readonly
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-gray-50 text-gray-600 cursor-not-allowed"
            placeholder="Auto-generated on save">
        <p class="text-xs text-gray-400 mt-1">Code is automatically generated and cannot be changed</p>
    </div>
    @else
    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Design Code</label>
        <input type="text" readonly
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm bg-gray-50 text-gray-600 cursor-not-allowed"
            placeholder="Will be auto-generated on save">
        <p class="text-xs text-gray-400 mt-1">Unique code will be automatically generated</p>
    </div>
    @endif

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Category</label>
        <select name="category_id"
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('category_id') border-red-300 @enderror">
            <option value="">— Select Category —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $design->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Upload Design File (EMB) <span class="text-red-400">{{ isset($design) ? '' : '*' }}</span></label>
        <input type="file" name="emb_file" {{ isset($design) ? '' : 'required' }}
            class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100 focus:outline-none @error('emb_file') border-red-300 @enderror">
        @if(isset($design) && $design->file_name)
            <p class="text-xs text-gray-400 mt-1">Current: {{ $design->file_name }}</p>
        @endif
        @error('emb_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Design Image</label>
        <input type="file" name="design_img" accept="image/*"
            class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100 focus:outline-none @error('design_img') border-red-300 @enderror">
        @error('design_img')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Price (₹) <span class="text-red-400">*</span></label>
        <input type="number" name="design_price" value="{{ old('design_price', $design->design_price ?? 300) }}" step="0.01" min="0" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('design_price') border-red-300 @enderror">
        @error('design_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Stitches</label>
        <input type="number" name="stitches" value="{{ old('stitches', $design->stitches ?? 0) }}" min="0"
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('stitches') border-red-300 @enderror">
        @error('stitches')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Height</label>
        <input type="text" name="height" value="{{ old('height', $design->height ?? '') }}"
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('height') border-red-300 @enderror"
            placeholder="e.g. 120mm">
        @error('height')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Width</label>
        <input type="text" name="width" value="{{ old('width', $design->width ?? '') }}"
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('width') border-red-300 @enderror"
            placeholder="e.g. 80mm">
        @error('width')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Area</label>
        <input type="text" name="area" value="{{ old('area', $design->area ?? '') }}"
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('area') border-red-300 @enderror"
            placeholder="e.g. 9600 sq mm">
        @error('area')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Needle/Color</label>
        <input type="text" name="needle_color" value="{{ old('needle_color', $design->needle_color ?? '') }}"
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('needle_color') border-red-300 @enderror"
            placeholder="e.g. 5 colors">
        @error('needle_color')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Design Format</label>
        <input type="text" name="design_format" value="{{ old('design_format', $design->design_format ?? 'emb') }}"
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('design_format') border-red-300 @enderror"
            placeholder="emb">
        @error('design_format')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2 lg:col-span-3">
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Description</label>
        <textarea name="description" rows="3"
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('description') border-red-300 @enderror"
            placeholder="Enter design description...">{{ old('description', $design->description ?? '') }}</textarea>
        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>
