
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

    <div class="mx-auto flex max-w-[1440px]
                items-center justify-between
                gap-4 px-5 py-4">

        <!-- LOGO -->
        <a
            href="{{ route('user.dashboard') }}"
            class="flex shrink-0 items-center gap-3
                   font-extrabold text-[#105447]"
        >

            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                fill="none"
                class="h-8 w-8 shrink-0 text-[#264f73]"
                aria-hidden="true"
            >
                <path
                    d="M4.5 5.5H6c2.33 0 4.17.47 6 1.9c1.83-1.43 3.67-1.9 6-1.9h1.5v11.2H18c-2.2 0-4 .35-6 1.8c-2-1.45-3.8-1.8-6-1.8H4.5V5.5Z"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M12 7.4V18.5"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M9.1 11.3l2.1 2.1l4-4.4"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M3.7 7.2H4.5V17.7H6"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M20.3 7.2H19.5V17.7H18"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>

            <span>
                Beasiswa Cerdas

                <span class="block text-xs
                             font-normal text-slate-500">
                    Layanan Beasiswa
                </span>
            </span>
        </a>

        <!-- MENU DESKTOP -->
        <nav
            aria-label="Navigasi User"
            class="hidden items-center gap-1 xl:flex"
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
        <div class="hidden shrink-0 items-center gap-3 xl:flex">

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
        <details class="relative xl:hidden">

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
