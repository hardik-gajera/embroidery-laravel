@extends('frontend.layout')
@section('title', 'About Us')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Hero -->
    <div class="text-center mb-16 scroll-reveal">
        <h1 class="text-4xl md:text-5xl font-heading font-bold text-gray-900 mb-4">About <span class="gradient-text">Aaradhya Design Gallery</span></h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Your trusted destination for premium embroidery designs. We bring creativity and craftsmanship together.</p>
    </div>

    <!-- Story -->
    <div class="grid md:grid-cols-2 gap-12 items-center mb-20 scroll-reveal">
        <div>
            <h2 class="text-2xl font-heading font-bold text-gray-900 mb-4">Our Story</h2>
            <p class="text-gray-600 mb-4">Aaradhya Design Gallery was founded with a passion for embroidery artistry. We provide a curated collection of high-quality embroidery designs that cater to both hobbyists and professionals.</p>
            <p class="text-gray-600">Our platform makes it easy to browse, purchase, and download designs instantly, helping you bring your creative visions to life.</p>
        </div>
        <div class="bg-gradient-to-br from-primary-50 to-accent-50 rounded-2xl p-10 text-center">
            <i class="fas fa-palette text-6xl text-primary-500 mb-4"></i>
            <p class="text-gray-700 font-medium">Creativity meets craftsmanship</p>
        </div>
    </div>

    <!-- Features -->
    <div class="grid md:grid-cols-3 gap-8 scroll-reveal">
        <div class="bg-white rounded-2xl p-8 card-hover text-center">
            <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-gem text-primary-600 text-xl"></i>
            </div>
            <h3 class="font-heading font-semibold text-gray-900 mb-2">Premium Quality</h3>
            <p class="text-sm text-gray-600">Every design is crafted with precision and attention to detail.</p>
        </div>
        <div class="bg-white rounded-2xl p-8 card-hover text-center">
            <div class="w-14 h-14 bg-accent-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-download text-accent-600 text-xl"></i>
            </div>
            <h3 class="font-heading font-semibold text-gray-900 mb-2">Instant Download</h3>
            <p class="text-sm text-gray-600">Get your designs instantly after purchase, ready to use.</p>
        </div>
        <div class="bg-white rounded-2xl p-8 card-hover text-center">
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-headset text-green-600 text-xl"></i>
            </div>
            <h3 class="font-heading font-semibold text-gray-900 mb-2">Dedicated Support</h3>
            <p class="text-sm text-gray-600">Our team is always ready to help you with any queries.</p>
        </div>
    </div>
</div>
@endsection
