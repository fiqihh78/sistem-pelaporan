@extends('layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Pusat Notifikasi</h1>
        <p class="text-sm text-gray-400">Semua notifikasi dan pemberitahuan sistem</p>
    </div>
    <form action="{{ route('notifikasi.readAll') }}" method="POST">
        @csrf
        @method('PUT')
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Tandai Semua Dibaca
        </button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm p-5">
    <div class="space-y-3">
        @forelse($notifikasis as $notif)
        <div class="flex items-start gap-3 p-3 rounded-lg {{ $notif->dibaca ? 'bg-white' : 'bg-blue-50' }} border border-gray-100">
            <div class="mt-1">
                @if($notif->tipe == 'info')
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-circle-info text-blue-600 text-xs"></i>
                    </div>
                @elseif($notif->tipe == 'success')
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-circle-check text-green-600 text-xs"></i>
                    </div>
                @else
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-triangle-exclamation text-yellow-600 text-xs"></i>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800">{{ $notif->judul }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $notif->pesan }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
            </div>
            @if(!$notif->dibaca)
            <form action="{{ route('notifikasi.read', $notif->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="text-xs text-blue-600 hover:underline">
                    Tandai dibaca
                </button>
            </form>
            @endif
        </div>
        @empty
        <div class="text-center py-8 text-gray-400">
            <i class="fa-solid fa-bell-slash text-3xl mb-2"></i>
            <p class="text-sm">Tidak ada notifikasi</p>
        </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $notifikasis->links() }}</div>
</div>

@endsection