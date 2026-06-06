<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title>Sistem Admin - Si Lapor</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 font-sans">

<div class="flex h-screen overflow-hidden">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="w-64 shadow-md flex flex-col justify-between py-6 px-4" style="background-color: #ffffff;">

        <div>

            {{-- Logo --}}
            <div class="flex items-center gap-3 mb-8 px-2">
                <img src="{{ asset('logo_ska.png') }}" alt="Logo Surakarta" width="90">
                <div>
                    <p class="font-bold text-gray-800 text-sm">Si Lapor</p>
                    <p class="text-xs text-gray-400">Kota Surakarta</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="space-y-1">

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('dashboard')
                        ? 'bg-blue-50 text-blue-600 font-medium'
                        : 'text-gray-600 hover:bg-gray-50' }}">

                    <i class="fa-solid fa-gauge-high w-4"></i>
                    Dashboard
                </a>

                <a href="{{ route('laporan.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('laporan*')
                        ? 'bg-blue-50 text-blue-600 font-medium'
                        : 'text-gray-600 hover:bg-gray-50' }}">

                    <i class="fa-solid fa-file-lines w-4"></i>
                    Laporan Masuk
                </a>

                <a href="{{ route('verifikasi.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('verifikasi*')
                        ? 'bg-blue-50 text-blue-600 font-medium'
                        : 'text-gray-600 hover:bg-gray-50' }}">

                    <i class="fa-solid fa-circle-check w-4"></i>
                    Verifikasi Laporan
                </a>

                <a href="{{ route('petugas.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('petugas*')
                        ? 'bg-blue-50 text-blue-600 font-medium'
                        : 'text-gray-600 hover:bg-gray-50' }}">

                    <i class="fa-solid fa-users w-4"></i>
                    Data Petugas
                </a>

                <a href="{{ route('kategori.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('kategori*')
                        ? 'bg-blue-50 text-blue-600 font-medium'
                        : 'text-gray-600 hover:bg-gray-50' }}">

                    <i class="fa-solid fa-tags w-4"></i>
                    Kategori
                </a>

                <a href="{{ route('statistik.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('statistik*')
                        ? 'bg-blue-50 text-blue-600 font-medium'
                        : 'text-gray-600 hover:bg-gray-50' }}">

                    <i class="fa-solid fa-chart-bar w-4"></i>
                    Statistik
                </a>

                <a href="{{ route('notifikasi.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('notifikasi*')
                        ? 'bg-blue-50 text-blue-600 font-medium'
                        : 'text-gray-600 hover:bg-gray-50' }}">

                    <i class="fa-solid fa-bell w-4"></i>
                    Notifikasi
                </a>

            </nav>
        </div>

        {{-- Bottom Menu --}}
        <div class="space-y-1">

            <a href="{{ route('pengaturan.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
               {{ request()->routeIs('pengaturan*')
                    ? 'bg-blue-50 text-blue-600 font-medium'
                    : 'text-gray-600 hover:bg-gray-50' }}">

                <i class="fa-solid fa-gear w-4"></i>
                Pengaturan
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-red-500 hover:bg-red-50 transition">

                    <i class="fa-solid fa-right-from-bracket w-4"></i>
                    Logout
                </button>
            </form>

        </div>
    </aside>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- ================= NAVBAR ================= --}}
        <header class="bg-white border-b border-gray-200 px-6 py-4">

            <div class="flex items-center justify-between">

                {{-- Search --}}
                <div class="flex-1 max-w-md">

                    <form action="{{ route('search.index') }}"
                          method="GET"
                          class="relative">

                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </span>

                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Cari laporan, petugas, atau kategori..."
                               class="w-full bg-gray-100 rounded-xl border border-transparent
                                      pl-11 pr-4 py-2.5 text-sm text-gray-700
                                      placeholder-gray-400
                                      focus:outline-none
                                      focus:ring-2
                                      focus:ring-blue-400
                                      focus:border-blue-400
                                      transition">
                    </form>

                </div>

                {{-- Right Navbar --}}
                <div class="flex items-center gap-5">

                    {{-- Notification --}}
                    <a href="{{ route('notifikasi.index') }}"
                       class="relative w-10 h-10 flex items-center justify-center
                              rounded-xl bg-gray-100 text-gray-500
                              hover:bg-blue-50 hover:text-blue-600 transition">

                        <i class="fa-solid fa-bell text-sm"></i>

                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                    </a>

                    {{-- Profile --}}
                    <div class="flex items-center gap-3 bg-gray-50 border border-gray-100 rounded-xl px-3 py-2">

                        {{-- Avatar --}}
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-700
                                    flex items-center justify-center text-white font-semibold shadow-sm">

                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>

                        {{-- User Info --}}
                        <div class="leading-tight">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ auth()->user()->name ?? 'Admin' }}
                            </p>

                            <p class="text-xs text-gray-400">
                                {{ auth()->user()->role ?? 'Administrator' }}
                            </p>
                        </div>

                        {{-- Dropdown --}}
                        <button class="text-gray-400 hover:text-gray-600 transition">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>

                    </div>

                </div>

            </div>
        </header>

        {{-- ================= PAGE CONTENT ================= --}}
        <main class="flex-1 overflow-y-auto p-6">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Content --}}
            @yield('content')

        </main>

    </div>
</div>

{{-- Scripts --}}
@yield('scripts')

</body>
</html>
