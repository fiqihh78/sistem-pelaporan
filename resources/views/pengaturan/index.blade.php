@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800">Pengaturan Sistem</h1>
    <p class="text-sm text-gray-400">Kelola profil dan preferensi akun admin</p>
</div>

<div class="grid grid-cols-3 gap-6">

    {{-- Sidebar Pengaturan --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-4">Menu Pengaturan</h2>
        <nav class="space-y-1">
            <a href="#profil" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm bg-blue-50 text-blue-600">
                <i class="fa-solid fa-user w-4"></i> Profil Admin
            </a>
            <a href="#keamanan" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                <i class="fa-solid fa-lock w-4"></i> Keamanan
            </a>
        </nav>
    </div>

    {{-- Form Pengaturan --}}
    <div class="col-span-2 space-y-6">

        {{-- Profil --}}
        <div id="profil" class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Profil Admin</h2>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('pengaturan.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Avatar --}}
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-400">{{ auth()->user()->role }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600 font-medium">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required
                               class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 font-medium">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" required
                               class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                </div>

                <div>
                    <label class="text-sm text-gray-600 font-medium">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ auth()->user()->phone }}"
                           class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Keamanan --}}
        <div id="keamanan" class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Ubah Password</h2>
            <form action="{{ route('pengaturan.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-sm text-gray-600 font-medium">Password Baru</label>
                    <input type="password" name="password"
                           class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                           placeholder="Kosongkan jika tidak ingin mengubah">
                </div>
                <div>
                    <label class="text-sm text-gray-600 font-medium">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                    Update Password
                </button>
            </form>
        </div>

    </div>
</div>

@endsection