
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

    $namaNavigasi = auth()->user()->nama_lengkap
        ?: auth()->user()->username;
@endphp

<header class="sticky top-0 z-40
               border-b border-emerald-100
               bg-white shadow-sm">

    <div class="mx-auto flex max-w-6xl
                items-center justify-between
                gap-3 px-5 py-4">

        <a
            href="{{ route('user.dashboard') }}"
            class="shrink-0 font-extrabold text-[#105447]"
        >
            ✦ Beasiswa Cerdas

            <span class="block text-xs
                         font-normal text-slate-500">
                Layanan Beasiswa
            </span>
        </a>

        <!-- MENU DESKTOP -->
        <nav
            aria-label="Navigasi User"
            class="hidden items-center gap-1 lg:flex"
        >

            @foreach ($menuUser as $menu)

                <a
                    href="{{ route($menu['route']) }}"

                    @if (request()->routeIs($menu['route']))
                        aria-current="page"
                    @endif

                    class="rounded-lg px-3 py-2
                           text-xs font-semibold
                           transition
                           {{ request()->routeIs($menu['route'])
                               ? 'bg-emerald-100 text-emerald-800'
                               : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700' }}"
                >

                    <span aria-hidden="true">
                        {{ $menu['ikon'] }}
                    </span>

                    {{ $menu['label'] }}

                </a>

            @endforeach

        </nav>

        <!-- IDENTITAS DAN LOGOUT -->
        <div class="hidden items-center gap-3 lg:flex">

            <span
                class="max-w-32 truncate
                       text-xs font-semibold text-slate-600"
                title="{{ $namaNavigasi }}"
            >
                {{ $namaNavigasi }}
            </span>

            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="rounded-lg bg-red-50
                           px-3 py-2 text-xs
                           font-bold text-red-700
                           hover:bg-red-100"
                >
                    Logout
                </button>

            </form>

        </div>

        <!-- MENU MOBILE -->
        <details class="relative lg:hidden">

            <summary
                class="cursor-pointer list-none
                       rounded-lg border
                       border-emerald-200
                       px-3 py-2 text-sm
                       font-bold text-[#105447]
                       [&::-webkit-details-marker]:hidden"
            >
                ☰ Menu
            </summary>

            <div
                class="absolute right-0 top-full
                       mt-3 w-[min(88vw,310px)]
                       rounded-2xl border
                       border-emerald-100
                       bg-white p-3 shadow-xl"
            >

                <p class="mb-2 border-b
                          border-slate-100
                          px-3 pb-3 text-xs
                          text-slate-500">

                    Login sebagai

                    <strong class="text-[#105447]">
                        {{ $namaNavigasi }}
                    </strong>

                </p>

                <nav
                    aria-label="Navigasi User mobile"
                    class="space-y-1"
                >

                    @foreach ($menuUser as $menu)

                        <a
                            href="{{ route($menu['route']) }}"

                            @if (request()->routeIs($menu['route']))
                                aria-current="page"
                            @endif

                            class="block rounded-xl
                                   px-3 py-2.5
                                   text-sm font-semibold
                                   {{ request()->routeIs($menu['route'])
                                       ? 'bg-emerald-100 text-emerald-800'
                                       : 'text-slate-700 hover:bg-emerald-50' }}"
                        >

                            <span aria-hidden="true">
                                {{ $menu['ikon'] }}
                            </span>

                            {{ $menu['label'] }}

                        </a>

                    @endforeach

                </nav>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="mt-2 border-t
                           border-slate-100 pt-2"
                >
                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-xl
                               px-3 py-2.5 text-left
                               text-sm font-bold
                               text-red-700
                               hover:bg-red-50"
                    >
                        Logout
                    </button>

                </form>

            </div>

        </details>

    </div>

</header>
