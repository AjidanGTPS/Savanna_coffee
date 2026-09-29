<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SAVANA Coffee POS')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: #FDF8F3; }

        /* Sidebar */
        #sidebar {
            width: 240px;
            height: 100vh;
            transition: width 0.22s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }
        #sidebar.collapsed { width: 64px; }

        /* Main wrap */
        #main-wrap {
            margin-left: 240px;
            transition: margin-left 0.22s cubic-bezier(.4,0,.2,1);
        }
        #main-wrap.sidebar-collapsed { margin-left: 64px; }

        /* Nav links */
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 14px; border-radius: 10px;
            font-size: 13.5px; font-weight: 500;
            color: rgba(255,255,255,0.65);
            transition: background .15s, color .15s;
            white-space: nowrap;
        }
        .nav-link:hover  { background: rgba(255,255,255,0.08); color: #fff; }
        .nav-link.active { background: linear-gradient(135deg,#d97706,#ea580c); color: #fff; box-shadow: 0 4px 14px rgba(217,119,6,.35); }

        .nav-section {
            font-size: 10px; font-weight: 700; letter-spacing: .08em;
            color: rgba(255,255,255,.3); text-transform: uppercase;
            padding: 16px 14px 6px; white-space: nowrap;
            transition: opacity .15s;
        }

        /* Collapsed state: hide labels, sections, user text */
        #sidebar.collapsed .nav-label   { display: none; }
        #sidebar.collapsed .nav-section { display: none; }
        #sidebar.collapsed .brand-text  { display: none; }
        #sidebar.collapsed .user-info   { display: none; }
        #sidebar.collapsed .nav-link    { justify-content: center; padding: 9px; gap: 0; }
        #sidebar.collapsed .nav-icon    { font-size: 1.2rem; }

        /* Tooltip when collapsed */
        #sidebar.collapsed .nav-link { position: relative; }
        #sidebar.collapsed .nav-link:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%; transform: translateY(-50%);
            background: #1C0A00;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 8px;
            white-space: nowrap;
            z-index: 100;
            pointer-events: none;
        }
    </style>
</head>
<body class="min-h-screen flex">

{{-- Sidebar --}}
<aside id="sidebar" class="fixed inset-y-0 left-0 flex flex-col z-30 shadow-2xl"
    style="background: linear-gradient(180deg, #1C0A00 0%, #2d1207 100%);">

    {{-- Brand --}}
    <div class="px-4 py-5 border-b border-white/10 shrink-0">
        <div class="flex items-center gap-3 min-w-0">
            {{-- Toggle button doubles as brand icon when expanded --}}
            <div class="brand-text min-w-0 overflow-hidden">
                <p class="font-bold text-white text-sm leading-tight whitespace-nowrap">SAVANA Coffee</p>
                <p class="text-amber-400 text-xs whitespace-nowrap">Gudang Bahan Baku</p>
            </div>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-2 py-4 overflow-y-auto overflow-x-hidden space-y-0.5">

        @if(auth()->user()->hasRole(['admin','kasir']))
        <a href="{{ route('kasir.meja') }}"
            class="nav-link {{ request()->is('kasir/meja') ? 'active' : '' }}"
            data-tooltip="Kelola Meja">
            <span class="nav-icon shrink-0">🪑</span>
            <span class="nav-label">Kelola Meja</span>
        </a>
        @endif

        @if(auth()->user()->hasRole(['admin','kasir','pelayan']))
        <a href="{{ route('kitchen.index') }}"
            class="nav-link {{ request()->is('kitchen*') ? 'active' : '' }}"
            data-tooltip="Kitchen Display">
            <span class="nav-icon shrink-0">👨‍🍳</span>
            <span class="nav-label">Kitchen Display</span>
        </a>
        @endif

        @if(auth()->user()->hasRole(['admin','kasir']))
        <a href="{{ route('kasir.riwayat') }}"
            class="nav-link {{ request()->is('kasir/riwayat*') ? 'active' : '' }}"
            data-tooltip="Riwayat">
            <span class="nav-icon shrink-0">📋</span>
            <span class="nav-label">Riwayat</span>
        </a>
        @endif

        @if(auth()->user()->isAdmin())
        <div class="nav-section">Manajemen</div>
        <a href="{{ route('admin.produk.index') }}"
            class="nav-link {{ request()->is('admin/produk*') ? 'active' : '' }}"
            data-tooltip="Produk">
            <span class="nav-icon shrink-0">🍽</span>
            <span class="nav-label">Produk</span>
        </a>
        <a href="{{ route('admin.meja.index') }}"
            class="nav-link {{ request()->is('admin/meja*') ? 'active' : '' }}"
            data-tooltip="Meja & QR">
            <span class="nav-icon shrink-0">🗺</span>
            <span class="nav-label">Meja & QR</span>
        </a>
        <a href="{{ route('admin.pengguna.index') }}"
            class="nav-link {{ request()->is('admin/pengguna*') ? 'active' : '' }}"
            data-tooltip="Pengguna">
            <span class="nav-icon shrink-0">👥</span>
            <span class="nav-label">Pengguna</span>
        </a>
        @endif

        @if(auth()->user()->hasRole(['admin','kasir','owner']))
        <div class="nav-section">Laporan</div>
        <a href="{{ route('laporan.ringkasan') }}"
            class="nav-link {{ request()->is('laporan/ringkasan*') ? 'active' : '' }}"
            data-tooltip="Ringkasan">
            <span class="nav-icon shrink-0">📊</span>
            <span class="nav-label">Ringkasan</span>
        </a>
        <a href="{{ route('laporan.penjualan') }}"
            class="nav-link {{ request()->is('laporan/penjualan*') ? 'active' : '' }}"
            data-tooltip="Penjualan">
            <span class="nav-icon shrink-0">💰</span>
            <span class="nav-label">Penjualan</span>
        </a>
        <a href="{{ route('laporan.produk-terlaris') }}"
            class="nav-link {{ request()->is('laporan/produk-terlaris*') ? 'active' : '' }}"
            data-tooltip="Produk Terlaris">
            <span class="nav-icon shrink-0">🏆</span>
            <span class="nav-label">Produk Terlaris</span>
        </a>
        @endif

        @if(auth()->user()->hasRole(['admin','manajer','owner']))
        <div class="nav-section">Gudang</div>
        <a href="{{ route('gudang.bahan-baku.index') }}"
            class="nav-link {{ request()->is('gudang/bahan-baku*') ? 'active' : '' }}"
            data-tooltip="Bahan Baku">
            <span class="nav-icon shrink-0">📦</span>
            <span class="nav-label">Bahan Baku</span>
        </a>
        <a href="{{ route('gudang.laporan-stok') }}"
            class="nav-link {{ request()->is('gudang/laporan-stok*') ? 'active' : '' }}"
            data-tooltip="Laporan Stok">
            <span class="nav-icon shrink-0">📉</span>
            <span class="nav-label">Laporan Stok</span>
        </a>
        @endif
    </nav>

    {{-- User info --}}
    <div class="px-2 py-3 border-t border-white/10 shrink-0 space-y-0.5">
        {{-- User row --}}
        <div class="nav-link cursor-default select-none" data-tooltip="{{ auth()->user()->nama }}">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0"
                style="background: linear-gradient(135deg,#d97706,#ea580c)">
                {{ substr(auth()->user()->nama, 0, 1) }}
            </div>
            <div class="nav-label min-w-0 overflow-hidden">
                <p class="text-white text-xs font-semibold truncate whitespace-nowrap leading-tight">{{ auth()->user()->nama }}</p>
                <p class="text-amber-400 text-[11px] whitespace-nowrap">{{ auth()->user()->peran_label }}</p>
            </div>
        </div>
        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-full text-left" data-tooltip="Keluar">
                <span class="nav-label text-xs">← Keluar</span>
            </button>
        </form>
    </div>
</aside>

{{-- Main Content --}}
<div id="main-wrap" class="flex-1 flex flex-col min-h-screen">

    {{-- Topbar --}}
    <header class="sticky top-0 z-20 bg-white/90 backdrop-blur-md border-b border-orange-100 px-6 py-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-4 min-w-0">
            {{-- Mobile / extra toggle button in topbar --}}
            <button onclick="toggleSidebar()"
                class="shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition"
                title="Tutup/Buka Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>
            <div class="min-w-0">
                <h2 class="font-bold text-gray-900 text-lg leading-tight">@yield('header', 'Dashboard')</h2>
                @hasSection('subheader')
                <p class="text-sm text-gray-400 mt-0.5">@yield('subheader')</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            @hasSection('actions')
            @yield('actions')
            @endif
            <span class="text-sm text-gray-300 border-l border-gray-200 pl-3 hidden sm:block">{{ now()->isoFormat('D MMM YYYY') }}</span>
        </div>
    </header>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="mx-6 mt-5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
        <span class="text-emerald-500 text-xl shrink-0">✓</span>
        <p class="text-emerald-700 text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="mx-6 mt-5 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
        <span class="text-red-500 text-xl shrink-0">⚠</span>
        <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>

<script>
(function () {
    const STORAGE_KEY = 'savana_sidebar_collapsed';
    const sidebar   = document.getElementById('sidebar');
    const mainWrap  = document.getElementById('main-wrap');

    function applyState(collapsed, animate) {
        if (!animate) {
            sidebar.style.transition  = 'none';
            mainWrap.style.transition = 'none';
        }
        sidebar.classList.toggle('collapsed', collapsed);
        mainWrap.classList.toggle('sidebar-collapsed', collapsed);
        if (!animate) {
            requestAnimationFrame(() => {
                sidebar.style.transition  = '';
                mainWrap.style.transition = '';
            });
        }
    }

    // Restore saved state instantly (no animation) to avoid flash
    const saved = localStorage.getItem(STORAGE_KEY);
    applyState(saved === '1', false);

    window.toggleSidebar = function () {
        const nowCollapsed = !sidebar.classList.contains('collapsed');
        applyState(nowCollapsed, true);
        localStorage.setItem(STORAGE_KEY, nowCollapsed ? '1' : '0');
    };
})();
</script>

</body>
</html>
