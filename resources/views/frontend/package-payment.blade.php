@extends('frontend.layout')
@section('title', 'Package Payment')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-lg animate-scale-in">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-100/50 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-green-600 text-xl"></i>
                </div>
                <h1 class="text-xl font-heading font-bold text-gray-800">Secure Payment</h1>
                <p class="text-sm text-gray-400 mt-1">Complete your package purchase via Razorpay</p>
            </div>

            <!-- Package Summary -->
            <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-5 border border-gray-100 mb-6">
                <div class="flex items-center gap-4">
                    @if($package->package_img)
                        <img src="{{ asset('storage/' . $package->package_img) }}" class="w-16 h-16 rounded-xl object-cover border border-gray-100 shadow-sm">
                    @else
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-100 to-accent-100 rounded-xl flex items-center justify-center"><i class="fas fa-box-open text-primary-500"></i></div>
                    @endif
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $package->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $package->number_of_design }} designs · {{ $package->time_period }} months</p>
                    </div>
                    <p class="text-xl font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">₹{{ number_format($package->price, 2) }}</p>
                </div>
            </div>

            <button id="pay-btn" class="btn-glow w-full py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl font-semibold text-lg hover:from-primary-700 hover:to-primary-800 transition-all shadow-lg shadow-primary-200 hover:scale-[1.02]">
                <i class="fas fa-credit-card mr-2"></i>Pay ₹{{ number_format($package->price, 2) }}
            </button>

            <div class="flex items-center justify-center gap-4 mt-5">
                <span class="text-xs text-gray-400"><i class="fas fa-shield-alt text-green-400 mr-1"></i>256-bit SSL</span>
                <span class="text-xs text-gray-400"><i class="fas fa-lock text-blue-400 mr-1"></i>Secured by Razorpay</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('pay-btn').addEventListener('click', function() {
    var options = {
        "key": "{{ $razorpayKey }}",
        "amount": "{{ $amount }}",
        "currency": "INR",
        "name": "Embroidery Designs",
        "description": "{{ $package->name }}",
        "prefill": {
            "name": "{{ $customer->name }}",
            "email": "{{ $customer->email }}",
            "contact": "{{ $customer->mobile_no }}"
        },
        "handler": function(response) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("frontend.package.payment.success") }}';
            var fields = {
                '_token': '{{ csrf_token() }}',
                'razorpay_payment_id': response.razorpay_payment_id,
                'razorpay_order_id': response.razorpay_order_id || '',
                'package_id': '{{ $package->id }}',
                'amount': '{{ $amount }}'
            };
            for (var key in fields) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            }
            document.body.appendChild(form);
            form.submit();
        },
        "theme": { "color": "#4f46e5" }
    };
    var rzp = new Razorpay(options);
    rzp.open();
});
</script>
@endpush
