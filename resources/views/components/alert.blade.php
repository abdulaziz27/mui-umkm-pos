@props(['type' => 'info', 'message'])

@php
    $classes = match ($type) {
        'success' => 'bg-green-50 border border-green-200 text-green-700',
        'error' => 'bg-red-50 border border-red-200 text-red-700',
        'warning' => 'bg-yellow-50 border border-yellow-200 text-yellow-700',
        default => 'bg-blue-50 border border-blue-200 text-blue-700',
    };
    
    $icon = match ($type) {
        'success' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
        'error' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
        'warning' => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
        default => '<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    };
@endphp

<div class="mb-4 p-4 rounded-lg flex items-start gap-3 {{ $classes }}"
     x-data="{ show: true }"
     x-show="show"
     x-transition
     @if($type === 'success') x-init="setTimeout(() => show = false, 5000)" @endif>
    <div class="pt-0.5">
        {!! $icon !!}
    </div>
    <div class="flex-1 text-sm font-medium">
        {{ $message }}
    </div>
    <button type="button" @click="show = false" class="ml-auto opacity-70 hover:opacity-100 transition-opacity">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
