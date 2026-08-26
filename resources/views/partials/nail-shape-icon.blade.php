{{--
    Simple vector reference icons for each nail shape.
    Usage: @include('partials.nail-shape-icon', ['shape' => 'Almond'])
--}}
@php
    $shapePaths = [
        'Square'   => 'M12 52 Q12 56 16 56 L28 56 Q32 56 32 52 L32 14 Q32 8 28 8 L16 8 Q12 8 12 14 Z',
        'Round'    => 'M12 52 Q12 56 16 56 L28 56 Q32 56 32 52 L32 24 Q32 8 22 8 Q12 8 12 24 Z',
        'Oval'     => 'M14 52 Q14 56 18 56 L26 56 Q30 56 30 52 L30 20 Q30 6 22 4 Q14 6 14 20 Z',
        'Almond'   => 'M14 52 Q14 56 18 56 L26 56 Q30 56 30 52 L28 22 Q26 8 22 4 Q18 8 16 22 Z',
        'Coffin'   => 'M13 52 Q13 56 17 56 L27 56 Q31 56 31 52 L28 18 Q28 16 26 16 L18 16 Q16 16 16 18 Z',
        'Stiletto' => 'M13 52 Q13 56 17 56 L27 56 Q31 56 31 52 L22 6 Z',
    ];
    $path = $shapePaths[$shape] ?? $shapePaths['Round'];
@endphp
<svg viewBox="0 0 44 60" class="w-8 h-11">
    <path d="{{ $path }}" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
</svg>
