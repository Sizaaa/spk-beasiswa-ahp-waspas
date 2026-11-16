@php
    $menuUser = [
        [
            'route' => 'user.dashboard',
            'label' => 'Dashboard',
            'ikon' => '▦',
        ],
        [
            'route' => 'user.pengajuan',
            'label' => 'Pengajuan Beasiswa',
            'ikon' => '▤',
        ],
        [
            'route' => 'user.status',
            'label' => 'Status Pengajuan',
            'ikon' => '◷',
        ],
        [
            'route' => 'user.profil',
            'label' => 'Profil Akun',
            'ikon' => '♙',
        ],
        [
            'route' => 'user.panduan',
            'label' => 'Panduan Pengajuan',
            'ikon' => 'ⓘ',
        ],
    ];

    $namaNavigasi = auth()->user()->nama_lengkap ?: auth()->user()->username;
    $initials = strtoupper(substr($namaNavigasi, 0, 2));
@endphp

<header class="sticky top-0 z-40 border-b border-[#E3EBE6] bg-white/90 backdrop-blur-md transition-all">

    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-3.5">

        <!-- BRAND LOGO -->
        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 shrink-0 group">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#103F36] to-[#20A67A] text-white shadow-md shadow-[#20A67A]/20">
                <span class="text-base font-extrabold">✦</span>
            </div>
            <div>
                <span class="text-base font-extrabold tracking-tight text-[#103F36] block leading-tight group-hover:text-[#116759] transition">
                    Beasiswa Cerdas
                </span>
                <span class="text-[11px] font-semibold text-[#64746D] block">
                    Portal Pendaftar Beasiswa
                </span>
            </div>
        </a>

        <!-- MENU DESKTOP -->
        <nav aria-label="Navigasi User" class="hidden items-center gap-1.5 lg:flex">
            @foreach ($menuUser as $menu)
                @php
                    $isAktif = request()->routeIs($menu['route']);
                @endphp
                <a
                    href="{{ route($menu['route']) }}"
                    @if ($isAktif) aria-current="page" @endif
                    class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-xs font-semibold transition-all duration-150 {{ $isAktif ? 'bg-[#103F36] text-white shadow-sm' : 'text-[#64746D] hover:bg-[#F5F8F6] hover:text-[#172B25]' }}"
                >
                    <span class="text-sm opacity-80" aria-hidden="true">{{ $menu['ikon'] }}</span>
                    <span>{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- IDENTITAS DAN LOGOUT (DESKTOP) -->
        <div class="hidden items-center gap-3 lg:flex">
            <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-extrabold text-[#103F36] border border-emerald-200">
                    {{ $initials }}
                </div>
                <div class="min-w-0 max-w-[130px]">
                    <p class="truncate text-xs font-bold text-[#172B25] leading-tight" title="{{ $namaNavigasi }}">
                        {{ $namaNavigasi }}
                    </p>
                    <p class="text-[10px] font-medium text-[#64746D]">
                        Calon Penerima
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="rounded-xl border border-red-200 bg-red-50/70 px-3 py-1.5 text-xs font-bold text-red-700 transition hover:bg-red-100 hover:border-red-300"
                >
                    Logout
                </button>
            </form>
        </div>

        <!-- MENU MOBILE ACCORDION -->
        <details class="relative lg:hidden">
            <summary class="cursor-pointer list-none rounded-xl border border-[#E3EBE6] bg-white px-3 py-2 text-xs font-bold text-[#103F36] hover:bg-[#F5F8F6] transition [&::-webkit-details-marker]:hidden flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <span>Menu</span>
            </summary>

            <div class="absolute right-0 top-full mt-2 w-[min(90vw,320px)] rounded-2xl border border-[#E3EBE6] bg-white p-3.5 shadow-2xl z-50">
                <!-- User chip mobile -->
                <div class="mb-3 flex items-center gap-3 border-b border-[#E3EBE6] pb-3 px-1">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-[#103F36]">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-[#172B25] truncate">{{ $namaNavigasi }}</p>
                        <p class="text-[10px] text-[#64746D]">Pendaftar Beasiswa</p>
                    </div>
                </div>

                <nav aria-label="Navigasi User mobile" class="space-y-1">
                    @foreach ($menuUser as $menu)
                        @php $isAktif = request()->routeIs($menu['route']); @endphp
                        <a
                            href="{{ route($menu['route']) }}"
                            class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold {{ $isAktif ? 'bg-[#103F36] text-white' : 'text-[#64746D] hover:bg-[#F5F8F6] hover:text-[#172B25]' }}"
                        >
                            <span class="text-sm opacity-80" aria-hidden="true">{{ $menu['ikon'] }}</span>
                            <span>{{ $menu['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <form method="POST" action="{{ route('logout') }}" class="mt-3 border-t border-[#E3EBE6] pt-2">
                    @csrf
                    <button
                        type="submit"
                        class="w-full rounded-xl px-3 py-2 text-left text-xs font-bold text-red-700 hover:bg-red-50 transition"
                    >
                        ↪ Keluar (Logout)
                    </button>
                </form>
            </div>
        </details>

    </div>

</header>
