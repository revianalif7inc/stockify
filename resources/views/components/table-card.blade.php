<div {{ $attributes->merge(['class' => 'rounded-lg border border-slate-700 overflow-hidden bg-slate-800/30']) }}>
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</div>