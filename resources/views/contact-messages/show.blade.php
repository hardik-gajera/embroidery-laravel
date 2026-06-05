@extends('layouts.app')
@section('title', 'View Message')
@section('subtitle', 'From ' . $contactMessage->name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('contact-messages.index') }}" class="text-sm text-gray-500 hover:text-primary-600 transition">
                <i class="fas fa-arrow-left mr-1"></i> Back to Messages
            </a>
            <span class="text-xs text-gray-400">{{ $contactMessage->created_at->format('d M Y, h:i A') }}</span>
        </div>

        <div class="space-y-4">
            <div>
                <label class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Name</label>
                <p class="text-sm font-medium text-gray-800 mt-1">{{ $contactMessage->name }}</p>
            </div>
            <div>
                <label class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Email</label>
                <p class="text-sm text-gray-800 mt-1">{{ $contactMessage->email }}</p>
            </div>
            <div>
                <label class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Message</label>
                <p class="text-sm text-gray-700 mt-1 leading-relaxed whitespace-pre-wrap">{{ $contactMessage->message }}</p>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 flex gap-3">
            <a href="mailto:{{ $contactMessage->email }}" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition">
                <i class="fas fa-reply mr-1"></i> Reply via Email
            </a>
            <form action="{{ route('contact-messages.destroy', $contactMessage) }}" method="POST" onsubmit="return confirm('Delete this message?')">
                @csrf @method('DELETE')
                <button class="px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition">
                    <i class="fas fa-trash mr-1"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
