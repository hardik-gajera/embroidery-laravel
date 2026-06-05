@extends('frontend.layout')
@section('title', 'Contact Us')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12 scroll-reveal">
        <h1 class="text-4xl md:text-5xl font-heading font-bold text-gray-900 mb-4">Get in <span class="gradient-text">Touch</span></h1>
        <p class="text-lg text-gray-600">Have questions? We'd love to hear from you.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-12 scroll-reveal">
        <!-- Contact Info -->
        <div>
            <h2 class="text-2xl font-heading font-bold text-gray-900 mb-6">Contact Information</h2>
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-map-marker-alt text-primary-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Address</h4>
                        <p class="text-gray-600 text-sm">{{ $appSettings['address'] }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-phone text-primary-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Phone</h4>
                        <p class="text-gray-600 text-sm">{{ $appSettings['mobile'] }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-envelope text-primary-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Email</h4>
                        <p class="text-gray-600 text-sm">{{ $appSettings['email'] }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fab fa-whatsapp text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">WhatsApp</h4>
                        <p class="text-gray-600 text-sm">{{ $appSettings['whatsapp'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white rounded-2xl p-8 card-hover">
            <h3 class="text-xl font-heading font-bold text-gray-900 mb-6">Send us a Message</h3>
            <form action="{{ route('frontend.contact.send') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all text-sm" placeholder="Your name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all text-sm" placeholder="your@email.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" rows="4" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all text-sm resize-none" placeholder="How can we help you?"></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl font-semibold hover:from-primary-700 hover:to-primary-800 transition-all shadow-lg shadow-primary-200 btn-glow">
                    <i class="fas fa-paper-plane mr-2"></i>Send Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
