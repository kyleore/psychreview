@php
    /** @var \App\Models\Category $category */
    $size = $size ?? 'md';
    $map = [
        'violet'  => 'from-violet-500 to-purple-600 shadow-violet-500/30',
        'emerald' => 'from-emerald-500 to-teal-600 shadow-emerald-500/30',
        'sky'     => 'from-sky-500 to-blue-600 shadow-sky-500/30',
        'rose'    => 'from-rose-500 to-pink-600 shadow-rose-500/30',
        'amber'   => 'from-amber-500 to-orange-600 shadow-amber-500/30',
        'cyan'    => 'from-cyan-500 to-teal-600 shadow-cyan-500/30',
        'indigo'  => 'from-indigo-500 to-brand-600 shadow-indigo-500/30',
        'fuchsia' => 'from-fuchsia-500 to-pink-600 shadow-fuchsia-500/30',
    ];
    $grad = $map[$category->color] ?? $map['indigo'];
    $dims = $size === 'lg' ? 'h-14 w-14' : ($size === 'sm' ? 'h-9 w-9' : 'h-12 w-12');
    $icon = $size === 'lg' ? 'h-7 w-7' : ($size === 'sm' ? 'h-5 w-5' : 'h-6 w-6');
@endphp
<span class="grid {{ $dims }} place-items-center rounded-2xl bg-gradient-to-br {{ $grad }} text-white shadow-lg">
    <i data-lucide="{{ $category->icon }}" class="{{ $icon }}"></i>
</span>
