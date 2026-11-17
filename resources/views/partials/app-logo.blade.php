@props([
    'class' => 'h-10 w-10',
    'showText' => true,
    'theme' => 'light', // 'light' (untuk background putih/terang) atau 'dark' (untuk background navy/gelap)
    'subtext' => 'SPK AHP–WASPAS',
    'titleClass' => '',
    'subtextClass' => '',
])

@php
    $isDark = $theme === 'dark';
@endphp

<div class="flex items-center gap-3 shrink-0">
    <!-- GEOMETRIC INSTITUTIONAL EMBLEM -->
    <div class="shrink-0 flex items-center justify-center {{ $class }}">
        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-xs">
            <!-- Base Container Badge -->
            <rect width="48" height="48" rx="12" fill="{{ $isDark ? '#0F1E36' : '#0F1E36' }}"/>
            <!-- Border Stroke -->
            <rect x="0.75" y="0.75" width="46.5" height="46.5" rx="11.25" stroke="{{ $isDark ? 'rgba(255,255,255,0.18)' : '#1E293B' }}" stroke-width="1.5"/>
            <!-- Left Pillar (Kriteria Dasar & Integritas) -->
            <path d="M10 21L19 14.5V36H10V21Z" fill="#2563EB"/>
            <!-- Right Pillar (Keseimbangan Evaluasi Objektif) -->
            <path d="M29 14.5L38 21V36H29V14.5Z" fill="#1D4ED8"/>
            <!-- Central Keystone / Delta (Prestasi & Seleksi Keputusan) -->
            <path d="M24 8L32.5 21H27V39H21V21H15.5L24 8Z" fill="#FFFFFF"/>
            <circle cx="24" cy="27" r="2" fill="#0F1E36"/>
        </svg>
    </div>

    @if ($showText)
        <div class="leading-tight">
            <span class="text-base font-extrabold tracking-tight block {{ $isDark ? 'text-white' : 'text-[#0F1E36]' }} {{ $titleClass }}">
                Beasiswa Cerdas
            </span>
            @if ($subtext)
                <span class="text-[10px] font-bold tracking-wider uppercase block mt-0.5 {{ $isDark ? 'text-blue-300' : 'text-[#2563EB]' }} {{ $subtextClass }}">
                    {{ $subtext }}
                </span>
            @endif
        </div>
    @endif
</div>
