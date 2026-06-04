@extends('layouts.app')
@section('title', 'Designs')
@section('subtitle', 'Manage embroidery designs')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
    <form method="GET" class="flex-1 max-w-sm">
        <div class="relative">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or code..."
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition">
            @if(request('search'))
                <a href="{{ route('designs.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i class="fas fa-times-circle text-sm"></i></a>
            @endif
        </div>
    </form>
    <div class="flex items-center gap-2">
        <select onchange="window.location.href=this.value" class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-primary-500">
            <option value="{{ route('designs.index') }}" {{ !request('category') ? 'selected' : '' }}>All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ route('designs.index', ['category' => $cat->id]) }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <a href="{{ route('designs.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
            <i class="fas fa-plus text-xs"></i>Add Design
        </a>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">#</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Design</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Code</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Category</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Stitches</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Price</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($designs as $design)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3.5 text-sm text-gray-400">{{ $loop->iteration + ($designs->currentPage() - 1) * $designs->perPage() }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if($design->design_img)
                                <img src="{{ asset('storage/' . $design->design_img) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                            @else
                                <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-swatchbook text-indigo-400 text-sm"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $design->name }}</p>
                                <p class="text-xs text-gray-400">{{ $design->file_name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="text-xs font-mono text-gray-600 bg-gray-100 px-2 py-0.5 rounded">{{ $design->design_code ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        @if($design->category)
                            <span class="inline-flex items-center text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">{{ $design->category->name }}</span>
                        @else
                            <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center text-xs font-semibold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-full">
                            {{ number_format($design->stitches) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">
                            ₹{{ number_format($design->design_price, 2) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('designs.show', $design) }}" class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-400 hover:text-blue-600 transition" title="View">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ route('designs.edit', $design) }}" class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-400 hover:text-green-600 transition" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <button onclick="confirmDelete({{ $design->id }}, '{{ $design->name }}')" class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-400 hover:text-red-600 transition" title="Delete">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                            <form id="delete-form-{{ $design->id }}" action="{{ route('designs.destroy', $design) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-gray-400 text-sm">
                        <i class="fas fa-swatchbook text-2xl mb-2 block"></i>
                        <p class="font-medium">No designs found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($designs->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $designs->links() }}</div>
    @endif
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-xl">
        <div class="text-center">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash text-red-500"></i>
            </div>
            <h3 class="text-lg font-heading font-bold text-gray-800">Delete Design</h3>
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
