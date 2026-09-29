<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SAVANA Coffee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .grain {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="min-h-screen flex" style="background: linear-gradient(135deg, #1a0800 0%, #3d1a0a 50%, #1a0800 100%);">

    {{-- Panel kiri --}}
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden">
        <div class="absolute inset-0 grain pointer-events-none"></div>

        {{-- Lingkaran dekoratif --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full opacity-10" style="background: radial-gradient(circle, #f59e0b, transparent)"></div>
        <div class="absolute -bottom-32 -left-16 w-80 h-80 rounded-full opacity-10" style="background: radial-gradient(circle, #ea580c, transparent)"></div>

        <div class="relative">
            <div class="flex items-center gap-3">
                <span class="text-4xl">☕</span>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">SAVANA Coffee</h1>
                    <p class="text-amber-400 text-sm">Point of Sale System</p>
                </div>
            </div>
        </div>

        <div class="relative">
            <blockquote class="text-white/80 text-lg leading-relaxed font-light italic">
                "Setiap cangkir menceritakan kisahnya sendiri — dan kami memastikan setiap pesanan tersampaikan dengan sempurna."
            </blockquote>
            <div class="mt-6 flex gap-6 text-sm text-white/40">
                <div><span class="text-amber-400 font-bold text-2xl">20</span><p>Meja</p></div>
                <div><span class="text-amber-400 font-bold text-2xl">37</span><p>Menu</p></div>
                <div><span class="text-amber-400 font-bold text-2xl">5</span><p>Peran</p></div>
            </div>
        </div>
    </div>

    {{-- Panel kanan / form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6">
        <div class="w-full max-w-md">

            {{-- Logo mobile --}}
            <div class="lg:hidden text-center mb-8">
                <span class="text-5xl">☕</span>
                <h1 class="text-xl font-bold text-white mt-2">SAVANA Coffee POS</h1>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #f59e0b, #ea580c, #f59e0b)"></div>

                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Selamat Datang</h2>
                    <p class="text-gray-400 text-sm mb-7">Masuk untuk melanjutkan ke sistem</p>

                    @if($errors->any())
                    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl flex gap-3 items-start">
                        <span class="text-red-500 mt-0.5">⚠</span>
                        <p class="text-red-600 text-sm">{{ $errors->first() }}</p>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-amber-500 focus:outline-none transition placeholder-gray-300"
                                placeholder="admin@savana.test">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                            <input type="password" name="password" required
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-amber-500 focus:outline-none transition"
                                placeholder="••••••••">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember"
                                class="w-4 h-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                            <label for="remember" class="ml-2.5 text-sm text-gray-600">Ingat saya</label>
                        </div>

                        <button type="submit"
                            class="w-full py-3.5 rounded-xl font-semibold text-white text-sm transition active:scale-[0.98]"
                            style="background: linear-gradient(135deg, #d97706, #ea580c)">
                            Masuk ke Sistem →
                        </button>
                    </form>

                    {{-- Demo accounts --}}
                    <div class="mt-7 pt-6 border-t border-gray-100">
                        <p class="text-xs text-gray-400 mb-3 font-medium uppercase tracking-wider">Akun Demo <span class="font-normal normal-case">(password: <code class="bg-gray-100 px-1.5 py-0.5 rounded-md">password</code>)</span></p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach([
                                ['admin@savana.test',   'Admin',   'bg-purple-50 text-purple-700 border-purple-200',  '👑'],
                                ['kasir@savana.test',   'Kasir',   'bg-blue-50 text-blue-700 border-blue-200',        '🧾'],
                                ['owner@savana.test',   'Owner',   'bg-emerald-50 text-emerald-700 border-emerald-200', '💼'],
                                ['manajer@savana.test', 'Manajer', 'bg-teal-50 text-teal-700 border-teal-200',        '📦'],
                            ] as [$email, $label, $cls, $icon])
                            <button type="button"
                                onclick="document.querySelector('[name=email]').value='{{ $email }}';document.querySelector('[name=password]').value='password'"
                                class="text-left p-3 rounded-xl border-2 {{ $cls }} hover:scale-[1.02] transition text-xs">
                                <span class="text-base">{{ $icon }}</span>
                                <span class="font-bold block mt-0.5">{{ $label }}</span>
                                <span class="opacity-70 text-[10px] break-all">{{ $email }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
