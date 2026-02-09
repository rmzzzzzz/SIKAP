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
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #FDFCF7 0%, #F3F1EA 100%); 
        }

        /* Konsep Pop-up Sidebar */
        #sidebar { 
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            opacity: 0;
            visibility: hidden;
            transform: scale(0.9) translateY(-20px); /* Efek pop dari arah logo */
            transform-origin: top left;
        }

        #sidebar.sidebar-open {
            opacity: 1;
            visibility: visible;
            transform: scale(1) translateY(0);
        }

        /* Hover effect untuk item navigasi */
        .nav-link-hover {
            transition: all 0.3s ease;
        }
        .nav-link-hover:hover {
            background-color: rgba(136, 158, 129, 0.15);
            padding-left: 1.5rem;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen pb-10 text-[#5B4636] overflow-x-hidden">

    {{-- OVERLAY --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-[#2F5F5E]/10 z-40 hidden backdrop-blur-md transition-opacity duration-500"></div>
    
    {{-- FLOATING POP-UP SIDEBAR --}}
    <div id="sidebar" class="fixed top-6 left-6 w-72 max-h-[calc(100vh-3rem)] bg-[#2F5F5E] text-white z-50 rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(47,95,94,0.5)] flex flex-col overflow-hidden border border-white/10">
        
        {{-- Header Pop-up --}}
        <div class="p-8 pb-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-[#FDFCF7] p-2 rounded-xl">
                    <i class="fa-solid fa-calendar-check text-[#2F5F5E] text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black tracking-tighter text-[#FDFCF7]">SIKAP</h2>
                    <p class="text-[9px] uppercase tracking-[0.2em] text-[#889E81] font-bold">Menu Navigasi</p>
                </div>
            </div>
            <button id="closeSidebar" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-red-400 transition-colors">
                <i class="fa-solid fa-xmark text-sm text-white"></i>
            </button>
        </div>
        
        {{-- List Menu --}}
        <nav class="px-4 py-6 space-y-2 flex-1">
            <a href="{{ url('/') }}" 
               class="flex items-center gap-4 p-4 rounded-2xl text-sm font-bold nav-link-hover {{ Request::is('/') ? 'bg-[#FDFCF7] text-[#2F5F5E] shadow-lg' : 'text-white/80' }}">
                <i class="fa-solid fa-house w-5 text-center"></i> 
                BERANDA
            </a>

            <a href="{{ route('kegiatan.opd') }}" 
               class="flex items-center gap-4 p-4 rounded-2xl text-sm font-bold nav-link-hover {{ Request::is('kegiatan*') ? 'bg-[#FDFCF7] text-[#2F5F5E] shadow-lg' : 'text-white/80' }}">
                <i class="fa-solid fa-calendar-days w-5 text-center"></i> 
                AGENDA OPD
            </a>
            
            <a href="#" class="flex items-center gap-4 p-4 rounded-2xl text-sm font-bold text-white/80 nav-link-hover">
                <i class="fa-solid fa-circle-info w-5 text-center"></i> 
                INFORMASI
            </a>
        </nav>

        {{-- Footer Pop-up --}}
        <div class="p-6 bg-black/10">
            <a href="{{ route('login') }}" 
               class="flex items-center justify-center gap-3 bg-[#889E81] hover:bg-[#FDFCF7] hover:text-[#2F5F5E] text-white font-black py-4 rounded-2xl shadow-lg transition-all active:scale-95 w-full uppercase text-xs tracking-widest">
                <i class="fa-solid fa-right-to-bracket"></i>
                LOGIN
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto pt-8 px-6">
        {{-- HEADER UTAMA --}}
        <div class="flex justify-between items-center mb-10">
            <div class="flex items-center gap-5">
                {{-- TOMBOL LOGO: Sekarang Berfungsi sebagai Pemicu Pop-up --}}
                <div id="logoBtn" class="relative z-30 bg-gradient-to-br from-[#2F5F5E] to-[#3E7A78] p-5 rounded-3xl shadow-2xl shadow-[#2F5F5E]/30 cursor-pointer transition-all duration-500 hover:rotate-6 hover:scale-110 active:scale-90 group">
                    <i class="fa-solid fa-calendar-check text-3xl text-[#FDFCF7] group-hover:text-white transition-colors"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-black tracking-tighter leading-none text-[#2F5F5E]">SIKAP</h1>
                    <p class="text-[#889E81] font-bold text-xs tracking-[0.4em] uppercase mt-2">Sumenep</p>
                </div>
            </div>
            
            <div class="hidden lg:flex flex-col text-right border-r-4 border-[#889E81] pr-6">
                <p class="text-lg font-bold text-[#2F5F5E]">Sistem Informasi Kegiatan</p>
                <p class="text-[10px] text-[#5B4636] uppercase tracking-[0.3em] font-extrabold opacity-60">Pemerintah Kabupaten Sumenep</p>
            </div>
        </div>

        {{-- AREA KONTEN --}}
        <main class="min-h-[60vh]">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const logoBtn = document.getElementById('logoBtn');
        const closeBtn = document.getElementById('closeSidebar');

        function openSidebar() {
            sidebar.classList.add('sidebar-open');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            sidebar.classList.remove('sidebar-open');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        logoBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            openSidebar();
        });

        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);
        
        // Menutup pop-up jika menekan tombol Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });
    </script>
    @stack('scripts')
</body>
</html>