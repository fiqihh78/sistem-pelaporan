<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Si Lapor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
    
    {{-- Logo --}}
    <div class="flex items-center gap-3 mb-8">
        <img src="{{ asset('images/logo_ska.png') }}" alt="Logo Surakarta" width="200">
        <div>
            <p class="font-bold" style="color:#8B2E2E;">Si Lapor</p>
            <p class="text-xs text-gray-400">Sistem Pengaduan Masyarakat<br>Kota Surakarta</p>
        </div>
    </div>

    <h1 class="text-xl font-bold text-gray-800 mb-1">Selamat Datang</h1>
    <p class="text-sm text-gray-400 mb-6">Masuk ke dashboard admin</p>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="text-sm text-gray-600 font-medium">Email</label>
            <input type="email" name="email" required
                   class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                   placeholder="admin@silapor.com">
        </div>
        <div>
            <label class="text-sm text-gray-600 font-medium">Password</label>
            <input type="password" name="password" required
                   class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                   placeholder="••••••••">
        </div>
        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
            Masuk
        </button>
    </form>
</div>

</body>
</html>
