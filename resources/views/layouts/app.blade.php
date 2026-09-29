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
        body { font-family: 'Inter', sans-serif; background: #FDF8F3; overflow-x: hidden; }

        /* ── Sidebar base ── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 260px;
            display: flex;
            flex-direction: column;
            z-index: 40;
            box-shadow: 4px 0 24px rgba(0,0,0,.25);
            background: linear-gradient(180deg,#1C0A00 0%,#2d1207 100%);
            overflow-x: hidden;
            overflow-y: hidden;
        }

        /* ── MOBILE: drawer (translate) ── */
        @media (max-width: 767px) {
            #sidebar {
                width: 270px !important;
                transition: transform 0.25s cubic-bezier(.4,0,.2,1);
                transform: translateX(-100%);
            }
            #sidebar.mobile-open { transform: translateX(0); }
            #main-wrap { margin-left: 0 !important; }
            #sidebar-backdrop { display: block; }
        }

        /* ── TABLET: drawer same as mobile ── */
        @media (min-width: 768px) and (max-width: 1023px) {
            #sidebar {
                width: 270px !important;
                transition: transform 0.25s cubic-bezier(.4,0,.2,1);
                transform: translateX(-100%);
            }
            #sidebar.mobile-open { transform: translateX(0); }
            #main-wrap { margin-left: 0 !important; }
            #sidebar-backdrop { display: block; }
        }

        /* ── DESKTOP: width-based collapsible ── */
        @media (min-width: 1024px) {
            #sidebar {
                transition: width 0.22s cubic-bezier(.4,0,.2,1);
                transform: none !important;
            }
            #sidebar.collapsed { width: 68px; }
            #main-wrap {
                margin-left: 260px;
                transition: margin-left 0.22s cubic-bezier(.4,0,.2,1);
            }
            #main-wrap.sidebar-collapsed { margin-left: 68px; }
            #sidebar-backdrop { display: none !important; }

            /* icon-only when collapsed */
            #sidebar.collapsed .nav-label   { display: none; }
            #sidebar.collapsed .nav-section { display: none; }
            #sidebar.collapsed .brand-text  { display: none; }
            #sidebar.collapsed .nav-link    { justify-content: center; padding: 10px; gap: 0; }
            #sidebar.collapsed .nav-icon    { font-size: 1.25rem; }
            /* tooltip */
            #sidebar.collapsed .nav-link { position: relative; }
            #sidebar.collapsed .nav-link:hover::after {
                content: attr(data-tooltip);
                position: absolute;
                left: calc(100% + 12px);
                top: 50%; transform: translateY(-50%);
                background: #1C0A00; color: #fff;
                font-size: 12px; font-weight: 600;
                padding: 5px 10px; border-radius: 8px;
                white-space: nowrap; z-index: 100; pointer-events: none;
            }
        }

        /* ── Backdrop ── */
        #sidebar-backdrop {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.55);
            z-index: 39;
            opacity: 0;
            transition: opacity 0.25s;
        }
        #sidebar-backdrop.visible { opacity: 1; }

        /* ── Nav links ── */
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: 10px;
            font-size: 14px; font-weight: 500;
            color: rgba(255,255,255,0.65);
            transition: background .15s, color .15s;
            white-space: nowrap; text-decoration: none;
        }
        .nav-link:hover  { background: rgba(255,255,255,0.08); color: #fff; }
        .nav-link.active {
            background: linear-gradient(135deg,#d97706,#ea580c);
            color: #fff; box-shadow: 0 4px 14px rgba(217,119,6,.35);
        }
        .nav-section {
            font-size: 10px; font-weight: 700; letter-spacing: .08em;
            color: rgba(255,255,255,.3); text-transform: uppercase;
            padding: 16px 14px 6px; white-space: nowrap;
        }

        /* ── Main wrap ── */
        #main-wrap { flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
    </style>
</head>
<body class="flex">

{{-- Backdrop (mobile/tablet) --}}
<div id="sidebar-backdrop" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<aside id="sidebar">

    {{-- Brand --}}
    <div class="px-4 py-5 border-b shrink-0" style="border-color:rgba(255,255,255,.1)">
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()"
                class="w-9 h-9 rounded-xl flex items-center justify-center text-lg shrink-0 transition hover:opacity-80"
                style="background:linear-gradient(135deg,#d97706,#ea580c)">☰</button>
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
            data-tooltip="Kelola Meja" onclick="closeSidebarMobile()">
            <span class="nav-icon shrink-0">🪑</span>
            <span class="nav-label">Kelola Meja</span>
        </a>
        @endif

        @if(auth()->user()->hasRole(['admin','kasir','pelayan']))
        <a href="{{ route('kitchen.index') }}"
            class="nav-link {{ request()->is('kitchen*') ? 'active' : '' }}"
            data-tooltip="Kitchen Display" onclick="closeSidebarMobile()">
            <span class="nav-icon shrink-0">👨‍🍳</span>
            <span class="nav-label">Kitchen Display</span>
        </a>
        @endif

        @if(auth()->user()->hasRole(['admin','kasir']))
        <a href="{{ route('kasir.riwayat') }}"
            class="nav-link {{ request()->is('kasir/riwayat*') ? 'active' : '' }}"
            data-tooltip="Riwayat" onclick="closeSidebarMobile()">
            <span class="nav-icon shrink-0">📋</span>
            <span class="nav-label">Riwayat</span>
        </a>
        @endif

        @if(auth()->user()->isAdmin())
        <div class="nav-section">Manajemen</div>
        <a href="{{ route('admin.produk.index') }}"
            class="nav-link {{ request()->is('admin/produk*') ? 'active' : '' }}"
            data-tooltip="Produk" onclick="closeSidebarMobile()">
            <span class="nav-icon shrink-0">🍽</span>
            <span class="nav-label">Produk</span>
        </a>
        <a href="{{ route('admin.meja.index') }}"
            class="nav-link {{ request()->is('admin/meja*') ? 'active' : '' }}"
            data-tooltip="Meja & QR" onclick="closeSidebarMobile()">
            <span class="nav-icon shrink-0">🗺</span>
            <span class="nav-label">Meja & QR</span>
        </a>
        <a href="{{ route('admin.pengguna.index') }}"
            class="nav-link {{ request()->is('admin/pengguna*') ? 'active' : '' }}"
            data-tooltip="Pengguna" onclick="closeSidebarMobile()">
            <span class="nav-icon shrink-0">👥</span>
            <span class="nav-label">Pengguna</span>
        </a>
        @endif

        @if(auth()->user()->hasRole(['admin','kasir','owner']))
        <div class="nav-section">Laporan</div>
        <a href="{{ route('laporan.ringkasan') }}"
            class="nav-link {{ request()->is('laporan/ringkasan*') ? 'active' : '' }}"
            data-tooltip="Ringkasan" onclick="closeSidebarMobile()">
            <span class="nav-icon shrink-0">📊</span>
            <span class="nav-label">Ringkasan</span>
        </a>
        <a href="{{ route('laporan.penjualan') }}"
            class="nav-link {{ request()->is('laporan/penjualan*') ? 'active' : '' }}"
            data-tooltip="Penjualan" onclick="closeSidebarMobile()">
            <span class="nav-icon shrink-0">💰</span>
            <span class="nav-label">Penjualan</span>
        </a>
        <a href="{{ route('laporan.produk-terlaris') }}"
            class="nav-link {{ request()->is('laporan/produk-terlaris*') ? 'active' : '' }}"
            data-tooltip="Produk Terlaris" onclick="closeSidebarMobile()">
            <span class="nav-icon shrink-0">🏆</span>
            <span class="nav-label">Produk Terlaris</span>
        </a>
        @endif

        @if(auth()->user()->hasRole(['admin','manajer','owner']))
        <div class="nav-section">Gudang</div>
        <a href="{{ route('gudang.bahan-baku.index') }}"
            class="nav-link {{ request()->is('gudang/bahan-baku*') ? 'active' : '' }}"
            data-tooltip="Bahan Baku" onclick="closeSidebarMobile()">
            <span class="nav-icon shrink-0">📦</span>
            <span class="nav-label">Bahan Baku</span>
        </a>
        <a href="{{ route('gudang.laporan-stok') }}"
            class="nav-link {{ request()->is('gudang/laporan-stok*') ? 'active' : '' }}"
            data-tooltip="Laporan Stok" onclick="closeSidebarMobile()">
            <span class="nav-icon shrink-0">📉</span>
            <span class="nav-label">Laporan Stok</span>
        </a>
        @endif
    </nav>

    {{-- User + Logout --}}
    <div class="px-2 py-3 shrink-0 space-y-0.5" style="border-top:1px solid rgba(255,255,255,.1)">
        <div class="nav-link cursor-default select-none" data-tooltip="{{ auth()->user()->nama }}">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0"
                style="background:linear-gradient(135deg,#d97706,#ea580c)">
                {{ substr(auth()->user()->nama, 0, 1) }}
            </div>
            <div class="nav-label min-w-0 overflow-hidden">
                <p class="text-white text-xs font-semibold truncate leading-tight">{{ auth()->user()->nama }}</p>
                <p class="text-amber-400 text-[11px]">{{ auth()->user()->peran_label }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-full text-left" data-tooltip="Keluar">
                <span class="nav-icon shrink-0">🚪</span>
                <span class="nav-label text-xs">← Keluar</span>
            </button>
        </form>
    </div>
</aside>

{{-- Main Content --}}
<div id="main-wrap">

    {{-- Topbar --}}
    <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-orange-100 px-4 py-3 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <button onclick="toggleSidebar()"
                class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-800 hover:bg-gray-100 active:scale-95 transition"
                aria-label="Toggle Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>
            <div class="min-w-0">
                <h2 class="font-bold text-gray-900 text-base leading-tight truncate">@yield('header', 'Dashboard')</h2>
                @hasSection('subheader')
                <p class="text-xs text-gray-400 mt-0.5 truncate">@yield('subheader')</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            @hasSection('actions')
            @yield('actions')
            @endif
            <span class="text-xs text-gray-300 border-l border-gray-200 pl-2 hidden sm:block whitespace-nowrap">
                {{ now()->isoFormat('D MMM YYYY') }}
            </span>
        </div>
    </header>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="mx-4 mt-4 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
        <span class="text-emerald-500 text-lg shrink-0">✓</span>
        <p class="text-emerald-700 text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="mx-4 mt-4 p-3.5 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
        <span class="text-red-500 text-lg shrink-0">⚠</span>
        <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <main class="flex-1 p-4 lg:p-6">
        @yield('content')
    </main>
</div>

<script>
(function () {
    const STORAGE_KEY = 'savana_sidebar_v2';
    const sidebar   = document.getElementById('sidebar');
    const mainWrap  = document.getElementById('main-wrap');
    const backdrop  = document.getElementById('sidebar-backdrop');

    function isMobile() { return window.innerWidth < 1024; }

    /* ── Desktop: width-based collapsed ── */
    function applyDesktop(collapsed, animate) {
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

    /* ── Mobile/Tablet: translate-based drawer ── */
    function openMobile() {
        sidebar.classList.add('mobile-open');
        backdrop.classList.add('visible');
        document.body.style.overflow = 'hidden';
    }
    function closeMobile() {
        sidebar.classList.remove('mobile-open');
        backdrop.classList.remove('visible');
        document.body.style.overflow = '';
    }

    window.closeSidebar = closeMobile;
    window.closeSidebarMobile = function () { if (isMobile()) closeMobile(); };

    window.toggleSidebar = function () {
        if (isMobile()) {
            sidebar.classList.contains('mobile-open') ? closeMobile() : openMobile();
        } else {
            const nowCollapsed = !sidebar.classList.contains('collapsed');
            applyDesktop(nowCollapsed, true);
            localStorage.setItem(STORAGE_KEY, nowCollapsed ? '1' : '0');
        }
    };

    /* Restore desktop state without flash */
    if (!isMobile()) {
        const saved = localStorage.getItem(STORAGE_KEY);
        applyDesktop(saved === '1', false);
    }

    /* On resize: clean up stale state */
    window.addEventListener('resize', () => {
        if (!isMobile()) {
            closeMobile();
        } else {
            sidebar.classList.remove('collapsed');
            mainWrap.classList.remove('sidebar-collapsed');
        }
    });
})();
</script>

</body>
</html>
