@extends('layouts.app')
@section('title', 'Settings')
@section('subtitle', 'Configure your application')

@section('content')
<div class="w-full">
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Logo -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-heading font-semibold text-gray-800 mb-4 uppercase tracking-wider">Logo</h3>
            <div class="flex items-center gap-6">
                @if($settings['logo'] ?? null)
                    <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="w-20 h-20 object-contain rounded-xl border border-gray-200">
                @else
                    <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400">
                        <i class="fas fa-image text-2xl"></i>
                    </div>
                @endif
                <div class="flex-1">
                    <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 2MB</p>
                </div>
            </div>
        </div>

        <!-- Company Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-heading font-semibold text-gray-800 mb-4 uppercase tracking-wider">Company Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                    <input type="text" name="company_name" value="{{ $settings['company_name'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $settings['email'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                    <input type="text" name="mobile" value="{{ $settings['mobile'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ $settings['whatsapp'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address" value="{{ $settings['address'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none text-sm">
                </div>
            </div>
        </div>

        <!-- Social Links -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-heading font-semibold text-gray-800 mb-4 uppercase tracking-wider">Social Links</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                    <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none text-sm" placeholder="https://facebook.com/...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none text-sm" placeholder="https://instagram.com/...">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700 transition shadow-lg shadow-primary-200">
                <i class="fas fa-save mr-1.5"></i> Save Settings
            </button>
        </div>
    </form>
</div>

@if($errors->any())
<div class="fixed top-5 right-5 z-50 bg-white border border-red-200 px-5 py-3 rounded-xl shadow-lg">
    <ul class="text-sm text-red-600">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@endsection
