<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIKAP SUMENEP')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: linear-gradient(135deg, #F3F1EA 0%, #ECE9E0 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); border: 1px solid #D6D1C4; }
        #sidebar { transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .sidebar-closed { transform: translateX(-100%); }
        .nav-item { opacity: 0; transform: translateX(-20px); transition: all 0.4s ease-out; }
        .sidebar-open .nav-item { opacity: 1; transform: translateX(0); }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen pb-10 text-gray-800 overflow-x-hidden">

    {{-- SIDEBAR --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden backdrop-blur-sm transition-opacity"></div>
    <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-[#2F5F5E] text-white z-50 p-6 sidebar-closed shadow-2xl">
        <div class="flex justify-between items-center mb-10 border-b border-white/10 pb-5">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-fingerprint text-2xl text-white/90"></i>
                <h2 class="text-xl font-bold tracking-tight italic">SIKAP MENU</h2>
            </div>
            <button id="closeSidebar" class="text-white/50 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>
        
        <nav class="space-y-3">
            <a href="{{ url('/') }}" class="nav-item flex items-center gap-4 text-base font-semibold p-4 rounded-2xl transition-all group {{ Request::is('/') ? 'bg-white/20 text-yellow-200' : 'hover:bg-white/10' }}" style="transition-delay: 100ms;">
                <i class="fa-solid fa-house w-6 {{ Request::is('/') ? 'text-yellow-200' : 'text-white/60 group-hover:text-white' }}"></i> HOME
            </a>

            <a href="{{ route('kegiatan.opd') }}" class="nav-item flex items-center gap-4 text-base font-semibold p-4 rounded-2xl transition-all group {{ Request::is('kegiatan*') ? 'bg-white/20 text-yellow-200' : 'hover:bg-white/10' }}" style="transition-delay: 200ms;">
                <i class="fa-solid fa-calendar-days w-6 {{ Request::is('kegiatan*') ? 'text-yellow-200' : 'text-white/60 group-hover:text-white' }}"></i> Agenda Per OPD
            </a>
        </nav>
    </div>

    <div class="max-w-7xl mx-auto pt-8 px-6">
        {{-- HEADER UMUM --}}
        <div class="flex justify-between items-center mb-10">
            <div class="flex items-center gap-5">
                <div id="logoBtn" class="bg-gradient-to-br from-[#2F5F5E] to-[#3E7A78] p-4 rounded-2xl shadow-xl shadow-[#2F5F5E]/20 cursor-pointer transition-all duration-300 hover:scale-110 active:scale-95 group">
                    <i class="fa-solid fa-fingerprint text-4xl text-white group-hover:text-yellow-200 transition-colors"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-black tracking-tighter leading-none text-[#2F5F5E]">SIKAP</h1>
                    <p class="text-[#5B4636] font-bold text-xs tracking-[0.4em] uppercase mt-2">Sumenep</p>
                </div>
            </div>
            <div class="hidden lg:block text-right border-l border-[#D6D1C4] pl-6">
                <p class="text-lg font-bold text-gray-700">Sistem Informasi Kegiatan & Aktivitas Pegawai</p>
                <p class="text-xs text-[#5B4636] uppercase tracking-widest leading-none mt-1">Pemerintah Kabupaten Sumenep</p>
            </div>
        </div>

        {{-- AREA KONTEN --}}
        @yield('content')

    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const logoBtn = document.getElementById('logoBtn');
        const closeBtn = document.getElementById('closeSidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('sidebar-closed');
            sidebar.classList.toggle('sidebar-open');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        logoBtn.addEventListener('click', toggleSidebar);
        closeBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>
    @stack('scripts')
</body>
</html>