@extends('layouts.app')
@section('title', 'Customers')
@section('subtitle', 'Manage your customer database')

@section('content')
<!-- Header Actions -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
    <form method="GET" class="flex-1 max-w-sm">
        <div class="relative">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customers..."
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-50 transition">
            @if(request('search'))
                <a href="{{ route('customers.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i class="fas fa-times-circle text-sm"></i></a>
            @endif
        </div>
    </form>
    <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
        <i class="fas fa-plus text-xs"></i>Add Customer
    </a>
</div>

@if(request('search'))
<div class="mb-4 text-sm text-gray-500">
    Showing results for "<span class="font-medium text-gray-700">{{ request('search') }}</span>" • {{ $customers->total() }} found
</div>
@endif

<!-- Table -->
<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">#</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Mobile</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Downloads</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Designs</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Package</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3.5 text-sm text-gray-400">{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-[11px] font-bold">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-400">{{ $customer->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-gray-600">{{ $customer->mobile_no }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-full">
                            <i class="fas fa-arrow-trend-up text-[9px]"></i>{{ $customer->downloaded_design }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">
                            {{ $customer->total_design }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if($customer->package && $customer->package_end_date && $customer->package_end_date->isFuture())
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full">
                                <i class="fas fa-box text-[9px]"></i>{{ $customer->package->name }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('customers.show', $customer) }}" class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-400 hover:text-blue-600 transition" title="View">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('customers.edit', $customer) }}" class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-400 hover:text-green-600 transition" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <button onclick="confirmDelete({{ $customer->id }}, '{{ $customer->name }}')" class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-400 hover:text-red-600 transition" title="Delete">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                            <form id="delete-form-{{ $customer->id }}" action="{{ route('customers.destroy', $customer) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">
                        <i class="fas fa-users text-2xl mb-2 block"></i>
                        <p class="font-medium">No customers found</p>
                        <p class="text-xs mt-1">{{ request('search') ? 'Try a different search term' : 'Add your first customer' }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $customers->links() }}</div>
    @endif
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
        <div class="text-center">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash text-red-500"></i>
            </div>
            <h3 class="text-lg font-heading font-bold text-gray-800">Delete Customer</h3>
            <p class="text-gray-500 text-sm mt-2">Are you sure you want to delete <span id="deleteName" class="font-semibold text-gray-700"></span>?</p>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">Cancel</button>
            <button onclick="submitDelete()" class="flex-1 px-4 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">Delete</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let deleteId = null;
    function confirmDelete(id, name) {
        deleteId = id;
        document.getElementById('deleteName').textContent = name;
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
    function submitDelete() { if(deleteId) document.getElementById('delete-form-' + deleteId).submit(); }
    document.getElementById('deleteModal').addEventListener('click', function(e) { if(e.target === this) closeModal(); });
</script>
@endpush
