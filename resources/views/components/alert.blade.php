@props(['type' => 'info'])
@php
    $type = $type ?? 'info';
    if ($type === 'success') {
        $bg = 'bg-emerald-500/20';
        $border = 'border-emerald-500';
        $text = 'text-emerald-100';
        $icon = '✓';
    } else {
        $bg = 'bg-rose-500/20';
        $border = 'border-rose-500';
        $text = 'text-rose-100';
        $icon = '!';
    }
@endphp
<div {{ $attributes->merge(['class' => "p-4 mb-4 rounded-lg {$bg} border-l-4 {$border} {$text} flex gap-3 items-start"]) }} role="alert">
    <span class="text-xl mt-0.5">{{ $icon }}</span>
    <div class="text-sm">{{ $slot }}</div>
</div>