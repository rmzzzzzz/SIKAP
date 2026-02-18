<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HADIR SUMENEP')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Import Font: Plus Jakarta Sans & Philosopher */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Philosopher:wght@700&display=swap');

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(180deg, #F0F9F8 0%, #20B2AA 100%);
            background-attachment: fixed;
            overflow: hidden;
            color: #1A1A1A;
        }

        .page-wrapper {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .page-header {
            flex-shrink: 0;
            margin-bottom: 0.4rem !important;
            padding-top: 1rem;
        }

        .page-content {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .scroll-area {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 2rem;
        }

        /* ===============================
            SIDEBAR STYLE
           =============================== */
        #sidebar {
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            opacity: 0;
            visibility: hidden;
            transform: scale(0.9) translateY(-20px);
            transform-origin: top left;
            background-color: #1a9690; 
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            z-index: 100;
        }

        #sidebar.sidebar-open {
            opacity: 1;
            visibility: visible;
            transform: scale(1) translateY(0);
        }

        #sidebarOverlay {
            background-color: rgba(0, 0, 0, 0.2); 
            z-index: 90;
            display: none;
        }

        #sidebarOverlay.overlay-show {
            display: block;
        }

        .nav-link-hover {
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .nav-link-hover:hover {
            background-color: rgba(255, 255, 255, 0.15);
            padding-left: 1.5rem;
        }

        /* LOGO & BUTTON ANIMATION */
        #logoBtn {
            padding: 0.7rem !important;
            border-radius: 1rem !important;
            background: #20B2AA; 
            border: 2px solid rgba(255,255,255,0.3);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            z-index: 30;
            box-shadow: 0 4px 15px rgba(32, 178, 170, 0.3);
        }

        @keyframes wobble {
            0% { transform: translateX(0%); }
            15% { transform: translateX(-15%) rotate(-5deg); }
            30% { transform: translateX(10%) rotate(3deg); }
            100% { transform: translateX(0%); }
        }

        #logoBtn:hover {
            animation: wobble 0.8s ease-in-out;
            transform: scale(1.05);
            background: #1a9690;
        }

        /* ===============================
            FANCY LOGO TEXT (PRESISI)
           =============================== */
        h1.brand-fancy {
            font-family: 'Philosopher', sans-serif;
            font-size: 2.1rem !important;
            line-height: 0.85 !important;
            background: linear-gradient(to bottom, #1a9690, #20B2AA);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            margin: 0;
        }

        .logo-sub-fancy {
            font-size: 0.65rem !important;
            letter-spacing: 0.45em !important;
            color: #1a9690 !important; 
            font-weight: 800;
            text-transform: uppercase;
            line-height: 1 !important;
            margin: 0;
            padding-left: 2px;
        }

        /* PERUBAHAN DISINI: Teks Hitam & Terang */
        .header-right-text .title-top { 
            font-size: 0.65rem !important; 
            font-weight: 800; 
            color: #111827 !important; /* Hitam pekat */
        }
        
        .header-right-text .title-bottom { 
            font-size: 0.55rem !important; 
            letter-spacing: 0.2em !important; 
            font-weight: 800; 
            color: #374151 !important; /* Hitam abu tua */
            opacity: 1; /* Terlihat lebih terang/jelas */
            text-transform: uppercase; 
        }

        .page-header .border-r-2 { 
            border-color: #111827 !important; /* Garis pembatas mengikuti warna teks hitam */
            opacity: 0.15;
        }
    </style>

    @stack('styles')
</head>

<body class="overflow-x-hidden">

    <div id="sidebarOverlay" class="fixed inset-0 backdrop-blur-sm transition-opacity duration-500"></div>

    <div id="sidebar" class="fixed top-6 left-6 w-72 max-h-[calc(100vh-3rem)] text-white rounded-[2rem] flex flex-col overflow-hidden">
        
        <div class="p-8 pb-4 flex justify-between items-center border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="bg-white p-2 rounded-xl">
                    <i class="fa-solid fa-calendar-check text-[#20B2AA] text-lg"></i>
                </div>
                <div class="flex flex-col">
                    <h2 style="font-family: 'Philosopher', sans-serif;" class="text-2xl font-bold leading-none text-white">HADIR</h2>
                    <p class="text-[9px] uppercase tracking-[0.2em] text-white/70 font-bold">Menu Navigasi</p>
                </div>
            </div>
            <button id="closeSidebar" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-black/20 transition-colors">
                <i class="fa-solid fa-xmark text-sm text-white"></i>
            </button>
        </div>

        <nav class="px-4 py-6 space-y-2 flex-1 overflow-y-auto">
            <a href="{{ url('/') }}" class="nav-link-hover flex gap-4 p-4 rounded-2xl text-sm font-bold text-white">
                <i class="fa-solid fa-house w-5 text-center text-white/70"></i> BERANDA
            </a>
            
            <a href="{{ route('kegiatan.opd') }}" class="nav-link-hover flex gap-4 p-4 rounded-2xl text-sm font-bold text-white">
                <i class="fa-solid fa-calendar-days w-5 text-center text-white/70"></i> AGENDA OPD
            </a>
        </nav>

        <div class="p-6 bg-black/10">
            <a href="{{ route('login') }}"
                class="flex items-center justify-center gap-3 bg-white text-[#20B2AA] font-black py-4 rounded-2xl 
                        transition-all active:scale-95 w-full uppercase text-xs tracking-widest hover:bg-[#f0f9f8]">
                <i class="fa-solid fa-right-to-bracket"></i> LOGIN
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 page-wrapper">
        <header class="flex justify-between items-center page-header">
            <div class="flex items-center gap-4">
                <div id="logoBtn">
                    <i class="fa-solid fa-bars text-white"></i>
                </div>
                <div class="flex flex-col justify-center">
                    <h1 class="brand-fancy">HADIR</h1>
                    <p class="logo-sub-fancy">Sumenep</p>
                </div>
            </div>

            <div class=" text-right pr-5 header-right-text">
                <p class="title-top">Sistem Informasi Kegiatan</p>
                <p class="title-bottom">Pemerintah Kabupaten Sumenep</p>
            </div>
        </header>

        <main class="page-content mt-4">
            <div class="scroll-area">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const logoBtn = document.getElementById('logoBtn');
        const closeBtn = document.getElementById('closeSidebar');

        function openSidebar() {
            sidebar.classList.add('sidebar-open');
            overlay.classList.add('overlay-show');
        }

        function closeSidebar() {
            sidebar.classList.remove('sidebar-open');
            overlay.classList.remove('overlay-show');
        }

        logoBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            openSidebar();
        });

        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape") closeSidebar();
        });
    </script>

    @stack('scripts')
</body>
</html>