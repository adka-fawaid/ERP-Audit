<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dashboard Audit Utilitas QAD</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4">
    <div class="w-full max-w-xl">
        <div class="bg-white rounded-2xl shadow-sm px-8 py-10 sm:px-10">
            <div class="flex justify-center mb-8">
                <img src="{{ asset('images/Bangkit_Bersama_Phapros01.png') }}" alt="Phapros" class="h-16 object-contain">
            </div>
            <div class="text-center mb-8">
                <h1 class="text-2xl font-semibold text-slate-800"> Selamat Datang di QAD-Audit</h1>
                <p class="mt-2 text-slate-500"> Masuk dengan akun NIK dan kata sandi Anda </p>
            </div>
            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif
            <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="identity" class="mb-2 block text-sm font-semibold text-slate-700">Nomor NIK</label>
                    <input id="identity" type="text" name="identity" value="{{ old('identity') }}" autocomplete="username" required autofocus class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700"> Kata Sandi </label>
                    <div class="relative">
                        <input id="password" type="password" name="password" autocomplete="current-password" required class="w-full rounded-lg border border-slate-300 px-4 py-3 pr-12 text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-indigo-500" aria-label="Tampilkan password">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                {{-- CAPTCHA --}}
                <div class="flex justify-center">
                    {{-- Cloudflare Turnstile akan ditempatkan di sini --}}
                </div>
                <button type="submit" class="w-full rounded-lg bg-indigo-600 py-3 font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">Masuk</button>
            </form>
            <div class="mt-6 text-center">
                <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Jika Belum Memiliki Akun Silahkan Hubungi Administrator</a>
            </div>
        </div>
    </div>
    <script>
        const password = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        togglePassword.addEventListener('click', () => {
            password.type = password.type === 'password' ? 'text' : 'password';
        });
    </script>
</body>
</html>