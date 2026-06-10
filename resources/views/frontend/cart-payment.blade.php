@extends('frontend.layout')
@section('title', 'Cart Payment')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-lg animate-scale-in">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-100/50 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-green-600 text-xl"></i>
                </div>
                <h1 class="text-xl font-heading font-bold text-gray-800">Secure Payment</h1>
                <p class="text-sm text-gray-400 mt-1">Complete your purchase via Razorpay</p>
            </div>

            <!-- Free Designs (via package) -->
            @if($freeDesigns->count() > 0)
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100 mb-4">
                <p class="text-xs font-semibold text-green-700 mb-2"><i class="fas fa-gift mr-1"></i>Free via Package ({{ $freeDesigns->count() }})</p>
                @foreach($freeDesigns as $design)
                <div class="flex items-center gap-3 {{ !$loop->last ? 'mb-2' : '' }}">
                    @if($design->design_img)
                        <img src="{{ asset('storage/' . $design->design_img) }}" class="w-10 h-10 rounded-lg object-cover border border-green-200">
                    @else
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center"><i class="fas fa-swatchbook text-green-500 text-xs"></i></div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $design->name }}</p>
                    </div>
                    <p class="text-sm font-bold text-green-600">FREE</p>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Paid Designs -->
            <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-4 border border-gray-100 mb-6">
                <p class="text-xs font-semibold text-gray-500 mb-2"><i class="fas fa-credit-card mr-1"></i>Payment Required ({{ $paidDesigns->count() }})</p>
                @foreach($paidDesigns as $design)
                <div class="flex items-center gap-3 {{ !$loop->last ? 'mb-2' : '' }}">
                    @if($design->design_img)
                        <img src="{{ asset('storage/' . $design->design_img) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                    @else
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-swatchbook text-gray-400 text-xs"></i></div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $design->name }}</p>
                    </div>
                    <p class="text-sm font-bold text-gray-800">₹{{ number_format($design->design_price, 2) }}</p>
                </div>
                @endforeach
                <div class="border-t border-gray-200 mt-3 pt-3 flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-600">Total</p>
                    <p class="text-xl font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">₹{{ number_format($totalAmount, 2) }}</p>
                </div>
            </div>

            <button id="pay-btn" class="btn-glow w-full py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-xl font-semibold text-lg hover:from-primary-700 hover:to-primary-800 transition-all shadow-lg shadow-primary-200 hover:scale-[1.02]">
                <i class="fas fa-credit-card mr-2"></i>Pay ₹{{ number_format($totalAmount, 2) }}
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
        "description": "{{ $paidDesigns->count() }} design(s)",
        "prefill": {
            "name": "{{ $customer->name }}",
            "email": "{{ $customer->email }}",
            "contact": "{{ $customer->mobile_no }}"
        },
        "handler": function(response) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("frontend.payment.success") }}';

            var token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            form.appendChild(token);

            var paymentId = document.createElement('input');
            paymentId.type = 'hidden';
            paymentId.name = 'razorpay_payment_id';
            paymentId.value = response.razorpay_payment_id;
            form.appendChild(paymentId);

            var orderId = document.createElement('input');
            orderId.type = 'hidden';
            orderId.name = 'razorpay_order_id';
            orderId.value = response.razorpay_order_id || '';
            form.appendChild(orderId);

            var amountInput = document.createElement('input');
            amountInput.type = 'hidden';
            amountInput.name = 'amount';
            amountInput.value = '{{ $amount }}';
            form.appendChild(amountInput);

            // Paid design IDs
            @foreach($paidDesigns as $design)
            var d{{ $design->id }} = document.createElement('input');
            d{{ $design->id }}.type = 'hidden';
            d{{ $design->id }}.name = 'design_ids[]';
            d{{ $design->id }}.value = '{{ $design->id }}';
            form.appendChild(d{{ $design->id }});
            @endforeach

            // Free design IDs (claimed via package)
            @foreach($freeDesigns as $design)
            var f{{ $design->id }} = document.createElement('input');
            f{{ $design->id }}.type = 'hidden';
            f{{ $design->id }}.name = 'free_design_ids[]';
            f{{ $design->id }}.value = '{{ $design->id }}';
            form.appendChild(f{{ $design->id }});
            @endforeach

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