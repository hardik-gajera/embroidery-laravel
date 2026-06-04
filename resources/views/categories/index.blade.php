@extends('layouts.app')
@section('title', 'Categories')
@section('subtitle', 'Manage design categories')

@section('content')
<!-- Header Actions -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
    <form method="GET" class="flex-1 max-w-sm">
        <div class="relative">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..."
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-50 transition">
            @if(request('search'))
                <a href="{{ route('categories.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i class="fas fa-times-circle text-sm"></i></a>
            @endif
        </div>
    </form>
    <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition text-sm font-medium">
        <i class="fas fa-plus text-xs"></i>Add Category
    </a>
</div>

<!-- Filter Tabs -->
<div class="flex items-center gap-2 mb-5 flex-wrap">
    <a href="{{ route('categories.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !request('parent') ? 'bg-primary-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">All</a>
    <a href="{{ route('categories.index', ['parent' => 'only']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('parent') === 'only' ? 'bg-primary-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">Parents Only</a>
    @foreach($parentCategories as $pc)
        <a href="{{ route('categories.index', ['parent' => $pc->id]) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('parent') == $pc->id ? 'bg-primary-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">{{ $pc->name }}</a>
    @endforeach
</div>



<!-- Table -->
<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">#</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Image</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Name</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Parent</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Sub-categories</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3.5 text-sm text-gray-400">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                    <td class="px-5 py-3.5">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                        @else
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-gray-300 text-sm"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-sm font-medium text-gray-800">{{ $category->name }}</td>
                    <td class="px-5 py-3.5">
                        @if($category->parent)
                            <span class="inline-flex items-center text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">{{ $category->parent->name }}</span>
                        @else
                            <span class="inline-flex items-center text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">Root</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        @if($category->children->count() > 0)
                            <a href="{{ route('categories.index', ['parent' => $category->id]) }}" class="inline-flex items-center text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full hover:bg-green-100 transition">
                                {{ $category->children->count() }} <i class="fas fa-arrow-right text-[8px] ml-1"></i>
                            </a>
                        @else
                            <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('categories.edit', $category) }}" class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-400 hover:text-green-600 transition" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <button onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}', {{ $category->children->count() }})" class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-400 hover:text-red-600 transition" title="Delete">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                            <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                        <i class="fas fa-folder-open text-2xl mb-2 block"></i>
                        <p class="font-medium">No categories found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $categories->links() }}</div>
    @endif
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-xl">
        <div class="text-center">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash text-red-500"></i>
            </div>
            <h3 class="text-lg font-heading font-bold text-gray-800">Delete Category</h3>
            <p class="text-gray-500 text-sm mt-2">Are you sure you want to delete <span id="deleteName" class="font-semibold text-gray-700"></span>?</p>
        </div>

        <!-- Move children option (shown only for parent categories) -->
        <div id="moveChildrenSection" class="hidden mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
            <p class="text-sm text-amber-800 font-medium mb-2"><i class="fas fa-exclamation-triangle mr-1"></i> This category has sub-categories</p>
            <label class="text-xs text-amber-700 font-medium">Move sub-categories to:</label>
            <select id="moveChildrenTo" class="w-full mt-1 border border-amber-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500">
                <option value="">— Make them parent categories —</option>
                @foreach($parentCategories as $pc)
                    <option value="{{ $pc->id }}">{{ $pc->name }}</option>
                @endforeach
            </select>
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

    function confirmDelete(id, name, childrenCount) {
        deleteId = id;
        document.getElementById('deleteName').textContent = name;
        document.getElementById('moveChildrenTo').value = '';

        // Show move children section if this category has children
        const section = document.getElementById('moveChildrenSection');
        if (childrenCount > 0) {
            section.classList.remove('hidden');
            // Hide current category from dropdown
            document.querySelectorAll('#moveChildrenTo option').forEach(opt => {
                opt.style.display = opt.value == id ? 'none' : '';
            });
        } else {
            section.classList.add('hidden');
        }

        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
    function submitDelete() {
        if (!deleteId) return;
        const form = document.getElementById('delete-form-' + deleteId);
        const moveTo = document.getElementById('moveChildrenTo').value;
        if (moveTo) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'move_children_to';
            input.value = moveTo;
            form.appendChild(input);
        }
        form.submit();
    }
    document.getElementById('deleteModal').addEventListener('click', function(e) { if(e.target === this) closeModal(); });
</script>
@endpush
