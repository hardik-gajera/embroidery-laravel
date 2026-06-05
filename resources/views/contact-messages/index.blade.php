@extends('layouts.app')
@section('title', 'Contact Messages')
@section('subtitle', 'Messages from customers')

@section('content')
<div class="bg-white rounded-xl border border-gray-200">
    <div class="px-5 py-4 flex items-center justify-between">
        <h3 class="text-base font-heading font-semibold text-gray-800">All Messages</h3>
        <span class="text-xs text-gray-500">{{ $messages->total() }} total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-t border-gray-100">
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">From</th>
                    <th class="px-5 py-3 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Message</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="px-5 py-3 text-center text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($messages as $msg)
                <tr class="hover:bg-gray-50/50 transition {{ !$msg->is_read ? 'bg-primary-50/30' : '' }}">
                    <td class="px-5 py-3.5">
                        <p class="text-sm font-medium text-gray-800">{{ $msg->name }}</p>
                        <p class="text-xs text-gray-400">{{ $msg->email }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        <p class="text-sm text-gray-600 truncate max-w-xs">{{ $msg->message }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if($msg->is_read)
                            <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">Read</span>
                        @else
                            <span class="text-xs font-medium text-primary-600 bg-primary-50 px-2.5 py-1 rounded-full">New</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center text-xs text-gray-500">{{ $msg->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('contact-messages.show', $msg) }}" class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-400 hover:text-blue-600 transition" title="View">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <form action="{{ route('contact-messages.destroy', $msg) }}" method="POST" onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-400 hover:text-red-600 transition" title="Delete">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">No messages yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $messages->links() }}
    </div>
</div>
@endsection
