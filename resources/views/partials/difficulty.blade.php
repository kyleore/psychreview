@php
    $difficulty = $difficulty ?? 'BEGINNER';
    $styles = [
        'BEGINNER' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'INTERMEDIATE' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'ADVANCED' => 'bg-rose-50 text-rose-700 ring-rose-200',
    ];
    $icons = ['BEGINNER' => 'seedling', 'INTERMEDIATE' => 'flame', 'ADVANCED' => 'zap'];
    $cls = $styles[$difficulty] ?? $styles['BEGINNER'];
@endphp
<span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wide ring-1 {{ $cls }}">
    <i data-lucide="{{ $icons[$difficulty] ?? 'seedling' }}" class="h-3 w-3"></i> {{ $difficulty }}
</span>
