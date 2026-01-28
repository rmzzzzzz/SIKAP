<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi - SIKAP SUMENEP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f172a;
            color: white;
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .btn-back {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-5px);
        }
        .radio-custom:checked + label {
            background: #6366f1;
            border-color: #818cf8;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
        }
        .required-star::after {
            content: " *";
            color: #ef4444;
        }
        #sig-pad {
            background: #ffffff;
            cursor: crosshair;
            touch-action: none;
            border-radius: 1.5rem;
        }
        .animate-fade {
            animation: fadeIn 0.4s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="p-6 md:p-12 relative">

    <div class="max-w-7xl mx-auto">

        <div class="mb-8">
            <a href="/" class="btn-back inline-flex items-center gap-3 px-6 py-3 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-300">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

        <div class="text-center mb-12">
            <h2 class="text-purple-400 font-black uppercase tracking-[0.4em] text-[10px] mb-2 italic">Presensi Digital</h2>
            <h1 class="text-4xl md:text-5xl font-black uppercase italic tracking-tighter text-white drop-shadow-2xl">
                {{ $judul ?? 'DETAIL KEGIATAN' }}
            </h1>
        </div>

        <form action="#" id="attendanceForm" onsubmit="return validateForm()" class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            @csrf

            <div class="glass-card rounded-[3rem] p-8 md:p-10 border-t-4 border-purple-500">
                <h2 class="text-xl font-bold mb-8 text-purple-300 italic">
                    <i class="fa-solid fa-user-check mr-2"></i> Klasifikasi Peserta
                </h2>

                <div class="mb-8">
                    <label class="block text-[10px] font-black uppercase text-gray-500 mb-4 tracking-widest required-star">Pilih Jenis Pegawai</label>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="radio" id="internal" name="jenis" value="internal" class="hidden radio-custom" onclick="renderForm('internal')" required>
                        <label for="internal" class="block text-center py-4 rounded-2xl bg-white/5 border border-white/10 cursor-pointer font-bold uppercase text-sm transition-all">Internal</label>

                        <input type="radio" id="eksternal" name="jenis" value="eksternal" class="hidden radio-custom" onclick="renderForm('eksternal')">
                        <label for="eksternal" class="block text-center py-4 rounded-2xl bg-white/5 border border-white/10 cursor-pointer font-bold uppercase text-sm transition-all">Eksternal</label>
                    </div>
                </div>

                <div class="mb-10">
                    <label class="block text-[10px] font-black uppercase text-gray-500 mb-4 tracking-widest required-star">Pilih Tipe Peserta</label>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="radio" id="narsum" name="tipe" value="narasumber" class="hidden radio-custom" required>
                        <label for="narsum" class="block text-center py-4 rounded-2xl bg-white/5 border border-white/10 cursor-pointer font-bold uppercase text-sm transition-all">Narasumber</label>

                        <input type="radio" id="peserta" name="tipe" value="peserta" class="hidden radio-custom">
                        <label for="peserta" class="block text-center py-4 rounded-2xl bg-white/5 border border-white/10 cursor-pointer font-bold uppercase text-sm transition-all">Peserta</label>
                    </div>
                </div>

                <div id="dynamic-form" class="space-y-6 pt-8 border-t border-white/10 min-h-[250px]">
                    <div class="text-center py-10 opacity-40">
                        <i class="fa-solid fa-hand-pointer text-3xl mb-3 block"></i>
                        <p class="italic text-sm">Pilih Jenis Pegawai di atas...</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div class="glass-card rounded-[3rem] p-8 md:p-10 border-t-4 border-indigo-500 h-full flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-purple-300 italic"><i class="fa-solid fa-pen-nib mr-2"></i> Tanda Tangan</h2>
                        <button type="button" onclick="clearPad()" class="text-[10px] font-bold text-red-400 uppercase tracking-widest hover:text-red-300">Reset</button>
                    </div>

                    <div class="flex-grow">
                        <canvas id="sig-pad" class="w-full h-64 md:h-80 shadow-inner"></canvas>
                    </div>

                    <div class="mt-8 space-y-6">
                        <div class="flex justify-center">
                            <div class="g-recaptcha" data-sitekey="6LfuhFgsAAAAAFwhcV8IFwvrKd-iPXQo0Ek2Mi-t" data-theme="dark"></div>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 py-6 rounded-3xl font-black tracking-[0.3em] uppercase shadow-2xl hover:scale-[1.02] transition-all text-sm">
                            Kirim Kehadiran Sekarang <i class="fa-solid fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function renderForm(type) {
            const container = document.getElementById('dynamic-form');
            if(type === 'internal') {
                container.innerHTML = `
                    <div class="animate-fade space-y-6">
                        <h3 class="text-xs font-black text-purple-400 uppercase tracking-widest mb-2 italic">Data Pegawai Internal</h3>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 required-star">Nama Lengkap</label>
                            <input type="text" name="nama" placeholder="Nama Lengkap" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 outline-none focus:border-purple-500" required>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 required-star">NIP</label>
                            <input type="text" name="nip" placeholder="Masukkan NIP" class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 outline-none focus:border-purple-500" required>
                        </div>
                    </div>`;
            } else {
                container.innerHTML = `
                    <div class="animate-fade space-y-5">
                        <h3 class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-2 italic">Data Pegawai Eksternal</h3>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 required-star">Nama Lengkap</label>
                            <input type="text" name="nama" placeholder="Nama & Gelar" class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-6 outline-none focus:border-indigo-500" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 required-star">Jabatan</label>
                                <input type="text" name="jabatan" placeholder="Jabatan" class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-6 outline-none focus:border-indigo-500" required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 required-star">NIK / ID</label>
                                <input type="text" name="nip" placeholder="NIK" class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-6 outline-none focus:border-indigo-500" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 required-star">Asal Instansi</label>
                            <input type="text" name="instansi" placeholder="Instansi" class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-6 outline-none focus:border-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 required-star">Unit Kerja</label>
                            <input type="text" name="unit_kerja" placeholder="Divisi" class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-6 outline-none focus:border-indigo-500" required>
                        </div>
                    </div>`;
            }
        }

        const canvas = document.getElementById('sig-pad');
        const ctx = canvas.getContext('2d');
        let drawing = false;
        let signed = false;

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            ctx.scale(ratio, ratio);
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        const startDraw = (e) => { drawing = true; signed = true; ctx.beginPath(); draw(e); };
        const endDraw = () => { drawing = false; };
        const draw = (e) => {
            if(!drawing) return;
            const rect = canvas.getBoundingClientRect();
            const x = (e.clientX || (e.touches ? e.touches[0].clientX : 0)) - rect.left;
            const y = (e.clientY || (e.touches ? e.touches[0].clientY : 0)) - rect.top;
            ctx.lineWidth = 3; ctx.lineCap = 'round'; ctx.strokeStyle = '#0f172a';
            ctx.lineTo(x, y); ctx.stroke(); ctx.beginPath(); ctx.moveTo(x, y);
        };

        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', draw);
        window.addEventListener('mouseup', endDraw);
        canvas.addEventListener('touchstart', (e) => { startDraw(e); e.preventDefault(); }, {passive: false});
        canvas.addEventListener('touchmove', (e) => { draw(e); e.preventDefault(); }, {passive: false});
        canvas.addEventListener('touchend', endDraw);

        function clearPad() { ctx.clearRect(0, 0, canvas.width, canvas.height); signed = false; }

        function validateForm() {
            if (!signed) { alert("Tanda tangan wajib!"); return false; }
            if (grecaptcha.getResponse() === "") { alert("Captcha belum dicentang!"); return false; }
            return true;
        }
    </script>
</body>
</html>
