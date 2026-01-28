<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIKAP SUMENEP - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="min-h-screen pb-10 text-white">

    <div class="max-w-7xl mx-auto pt-8 px-6">

        <div class="flex justify-between items-center mb-10">
            <div class="flex items-center gap-5">
                <div class="bg-gradient-to-br from-purple-600 to-indigo-600 p-4 rounded-2xl shadow-xl shadow-purple-500/20">
                    <i class="fa-solid fa-fingerprint text-4xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-black tracking-tighter leading-none">SIKAP</h1>
                    <p class="text-purple-400 font-bold text-xs tracking-[0.4em] uppercase mt-2">Sumenep</p>
                </div>
            </div>
            <div class="hidden lg:block text-right border-l border-white/10 pl-6">
                <p class="text-lg font-bold text-gray-200">Sistem Informasi Kegiatan & Aktivitas Pegawai</p>
                <p class="text-xs text-purple-400 uppercase tracking-widest leading-none mt-1">Pemerintah Kabupaten Sumenep</p>
            </div>
        </div>

        <div class="relative mb-12 group">
            <i class="fa-solid fa-search absolute left-6 top-1/2 -translate-y-1/2 text-purple-400 text-xl"></i>
            <input type="text" id="searchInput" placeholder="Cari agenda kegiatan hari ini..."
                   class="w-full bg-white/5 border border-white/10 rounded-3xl py-6 pl-16 pr-6 text-xl focus:border-purple-500 focus:bg-white/10 outline-none transition-all shadow-2xl placeholder:text-gray-500">
        </div>

        <div class="flex items-center gap-4 mb-8">
            <div class="h-[2px] w-12 bg-purple-500"></div>
            <h2 class="text-sm font-black uppercase tracking-[0.3em] text-purple-300">Agenda Terkini</h2>
        </div>

        <div id="kegiatanContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <div class="kegiatan-card glass-card rounded-[3rem] overflow-hidden flex flex-col justify-between hover:border-purple-500 transition-all duration-500 hover:-translate-y-2 shadow-2xl">
                <div class="p-10">
                    <div class="flex items-start gap-6">
                        <div class="bg-gradient-to-tr from-purple-500 to-indigo-600 w-16 h-16 rounded-2xl flex items-center justify-center font-black text-2xl shadow-lg shrink-0">1</div>
                        <div class="flex-1">
                            <h3 class="nama-kegiatan font-extrabold text-xl mb-6 leading-tight uppercase tracking-tight italic">nerima sekjen dikdasmen</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4 bg-white/5 p-3 rounded-xl">
                                    <i class="fa-regular fa-clock text-purple-400 text-lg"></i>
                                    <div>
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">Waktu</p>
                                        <p class="text-sm font-semibold text-gray-200">13:00 - 15:00 WIB</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white/5 p-3 rounded-xl">
                                    <i class="fa-solid fa-location-dot text-purple-400 text-lg"></i>
                                    <div>
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">Lokasi</p>
                                        <p class="text-sm font-semibold text-gray-200">Ruang Rapat Bone</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="/hadir" class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-center py-6 font-black text-sm tracking-[0.2em] uppercase transition-all flex items-center justify-center gap-3">
                    ISI DAFTAR HADIR <i class="fa-solid fa-signature text-xl"></i>
                </a>
            </div>

            <div class="kegiatan-card glass-card rounded-[3rem] overflow-hidden flex flex-col justify-between hover:border-purple-500 transition-all duration-500 hover:-translate-y-2 shadow-2xl">
                <div class="p-10">
                    <div class="flex items-start gap-6">
                        <div class="bg-gradient-to-tr from-indigo-500 to-purple-600 w-16 h-16 rounded-2xl flex items-center justify-center font-black text-2xl shadow-lg shrink-0">2</div>
                        <div class="flex-1">
                            <h3 class="nama-kegiatan font-extrabold text-xl mb-6 leading-tight uppercase tracking-tight italic">Koordinasi Permenpan No 17 2024</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4 bg-white/5 p-3 rounded-xl">
                                    <i class="fa-regular fa-clock text-purple-400 text-lg"></i>
                                    <div>
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">Waktu</p>
                                        <p class="text-sm font-semibold text-gray-200">09:00 - 15:45 WIB</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 bg-white/5 p-3 rounded-xl">
                                    <i class="fa-solid fa-location-dot text-purple-400 text-lg"></i>
                                    <div>
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">Lokasi</p>
                                        <p class="text-sm font-semibold text-gray-200 uppercase">Samudra Pasai 2</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="/hadir" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-center py-6 font-black text-sm tracking-[0.2em] uppercase transition-all flex items-center justify-center gap-3">
                    ISI DAFTAR HADIR <i class="fa-solid fa-signature text-xl"></i>
                </a>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            // Ambil input user dan ubah ke huruf kecil
            let filter = this.value.toLowerCase();
            // Ambil semua elemen kartu kegiatan
            let cards = document.querySelectorAll('.kegiatan-card');

            cards.forEach(card => {
                // Ambil teks judul kegiatan di dalam kartu
                let title = card.querySelector('.nama-kegiatan').innerText.toLowerCase();

                // Jika teks judul mengandung kata yang diketik, tampilkan. Jika tidak, sembunyikan.
                if (title.includes(filter)) {
                    card.style.display = ""; // Tampilkan
                } else {
                    card.style.display = "none"; // Sembunyikan
                }
            });
        });
    </script>

</body>
</html>
