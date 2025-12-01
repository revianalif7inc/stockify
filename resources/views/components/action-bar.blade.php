@props(['count' => null])
<div {{ $attributes->merge(['class' => 'flex justify-between items-center mb-6 p-4 rounded-lg bg-slate-800/50 border border-slate-700']) }}>
    <div class="text-slate-300 text-sm">
        {{ $slot->isEmpty() ? '' : '' }}
        @if(isset($count))
            <span class="font-bold text-white">{{ $count }}</span>
        @endif
    </div>
    <div>
        {{ $right ?? $slot }}
    </div>
</div>