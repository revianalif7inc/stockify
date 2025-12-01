@props(['variant' => 'info'])
@php
    $variant = $variant ?? 'info';
    $map = [
        'admin' => ['bg' => 'bg-rose-500/20', 'text' => 'text-rose-300', 'border' => 'border-rose-500/30'],
        'manager' => ['bg' => 'bg-amber-500/20', 'text' => 'text-amber-300', 'border' => 'border-amber-500/30'],
        'staff' => ['bg' => 'bg-sky-500/20', 'text' => 'text-sky-300', 'border' => 'border-sky-500/30'],
        'active' => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-300', 'border' => 'border-emerald-500/30'],
        'inactive' => ['bg' => 'bg-slate-600/30', 'text' => 'text-slate-300', 'border' => 'border-slate-600/30'],
    ];
    $style = $map[$variant] ?? ['bg' => 'bg-slate-600/20', 'text' => 'text-slate-300', 'border' => 'border-slate-600/30'];
@endphp
<span
    class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $style['bg'] }} {{ $style['text'] }} border {{ $style['border'] }}">{{ $slot }}</span>