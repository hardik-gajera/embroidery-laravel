<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Package Name <span class="text-red-400">*</span></label>
        <input type="text" name="name" value="{{ old('name', $package->name ?? '') }}" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('name') border-red-300 @enderror"
            placeholder="Enter package name">
        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Number of Designs <span class="text-red-400">*</span></label>
        <input type="number" name="number_of_design" value="{{ old('number_of_design', $package->number_of_design ?? '') }}" min="1" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('number_of_design') border-red-300 @enderror"
            placeholder="e.g. 50">
        @error('number_of_design')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Time Period (Months) <span class="text-red-400">*</span></label>
        <input type="number" name="time_period" value="{{ old('time_period', $package->time_period ?? '') }}" min="1" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('time_period') border-red-300 @enderror"
            placeholder="e.g. 3">
        @error('time_period')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Package Price (₹) <span class="text-red-400">*</span></label>
        <input type="number" name="price" value="{{ old('price', $package->price ?? '') }}" step="0.01" min="0" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('price') border-red-300 @enderror"
            placeholder="e.g. 999.00">
        @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Status <span class="text-red-400">*</span></label>
        <select name="state" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('state') border-red-300 @enderror">
            <option value="draft" {{ old('state', $package->state ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="confirm" {{ old('state', $package->state ?? '') === 'confirm' ? 'selected' : '' }}>Confirmed</option>
            <option value="finish" {{ old('state', $package->state ?? '') === 'finish' ? 'selected' : '' }}>Finished</option>
        </select>
        @error('state')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Package Image</label>
        <input type="file" name="package_img" accept="image/*"
            class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100 focus:outline-none @error('package_img') border-red-300 @enderror">
        @error('package_img')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        @if(isset($package) && $package->package_img)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $package->package_img) }}" class="w-16 h-16 rounded-lg object-cover border border-gray-200">
            </div>
        @endif
    </div>
</div>
