<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Full Name</label>
        <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('name') border-red-300 @enderror"
            placeholder="Enter full name">
        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Email Address</label>
        <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('email') border-red-300 @enderror"
            placeholder="email@example.com">
        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Mobile Number</label>
        <input type="text" name="mobile_no" value="{{ old('mobile_no', $customer->mobile_no ?? '') }}" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('mobile_no') border-red-300 @enderror"
            placeholder="+91 9876543210">
        @error('mobile_no')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Downloaded Designs</label>
        <input type="number" name="downloaded_design" value="{{ old('downloaded_design', $customer->downloaded_design ?? 0) }}" min="0" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('downloaded_design') border-red-300 @enderror">
        @error('downloaded_design')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Total Designs</label>
        <input type="number" name="total_design" value="{{ old('total_design', $customer->total_design ?? 0) }}" min="0" required
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('total_design') border-red-300 @enderror">
        @error('total_design')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">
            Password @if(isset($customer))<span class="text-xs text-gray-400 font-normal">(leave blank to keep)</span>@endif
        </label>
        <input type="password" name="password"
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition @error('password') border-red-300 @enderror"
            placeholder="••••••••" {{ isset($customer) ? '' : 'required' }}>
        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="text-sm font-medium text-gray-700 mb-1.5 block">Confirm Password</label>
        <input type="password" name="password_confirmation"
            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition"
            placeholder="••••••••" {{ isset($customer) ? '' : 'required' }}>
    </div>
</div>
