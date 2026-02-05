@extends('layouts.app')

@section('title', 'Presensi - SIKAP SUMENEP')

@push('styles')
<style>
    .radio-custom:checked + label { background: #2F5F5E; border-color: #2F5F5E; color: white; box-shadow: 0 0 18px rgba(47,95,94,.35); }
    input, select, textarea { background-color: #ffffff !important; color: #1f2937 !important; border: 1px solid #D6D1C4; }
    #sig-pad { background: #ffffff; border-radius: 1.5rem; border: 2px dashed #2F5F5E; touch-action: none; cursor: crosshair; }
    
    .btn-back {
        transition: all 0.3s ease;
        background: white;
        border: 1px solid #D6D1C4;
        color: #5B4636;
    }
    .btn-back:hover {
        background: #2F5F5E;
        color: white;
        border-color: #2F5F5E;
        transform: translateX(-5px);
    }
</style>
{{-- Script external untuk fitur khusus presensi --}}
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    {{-- TOMBOL KEMBALI --}}
    <div class="mb-8 flex justify-end">
        <a href="{{ url('/') }}" class="btn-back inline-flex items-center gap-3 px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest shadow-sm">
            <i class="fa-solid fa-chevron-left"></i> Kembali ke Beranda
        </a>
    </div>

    {{-- HEADER KEGIATAN --}}
    <div class="text-center mb-12">
        <h2 class="text-[#5B4636] font-black tracking-[0.4em] text-[10px] italic">PRESENSI DIGITAL</h2>
        <h1 class="text-4xl md:text-5xl font-black uppercase text-[#2F5F5E]">
            {{ $kegiatan->nama_kegiatan }}
        </h1>
        <p class="text-sm mt-2 text-gray-600"><i class="fa-solid fa-location-dot"></i> {{ $kegiatan->lokasi }}</p>
    </div>

    {{-- PERINGATAN GEOFENCE --}}
    <div id="location-warning" class="hidden bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-2xl mb-6 text-center shadow-md">
        <div class="flex items-center justify-center gap-3">
            <i class="fa-solid fa-triangle-exclamation text-xl animate-pulse"></i>
            <span id="warning-text" class="font-bold">Mendeteksi lokasi Anda...</span>
        </div>
    </div>

    <form action="{{ url('/hadir/'.$kegiatan->id_kegiatan) }}" method="POST" id="formPresensi" onsubmit="return validateForm()"
          class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        @csrf

        <input type="hidden" name="latitude_hadir" id="lat">
        <input type="hidden" name="longitude_hadir" id="lng">
        <input type="hidden" name="tanda_tangan" id="tanda_tangan_input">

        {{-- KIRI: DATA DIRI --}}
        <div class="glass-card rounded-[3rem] p-10 border-t-4 border-[#2F5F5E]">
            <div class="mb-8">
                <label class="block text-xs font-bold mb-4 text-[#5B4636]">STATUS PEGAWAI</label>
                <div class="grid grid-cols-2 gap-4">
                    <input type="radio" id="internal" name="status_pegawai" value="internal" class="hidden radio-custom" onclick="renderForm('internal')" required>
                    <label for="internal" class="block text-center py-4 rounded-2xl bg-[#F0EFE9] border border-[#D6D1C4] cursor-pointer font-bold">Internal</label>

                    <input type="radio" id="eksternal" name="status_pegawai" value="eksternal" class="hidden radio-custom" onclick="renderForm('eksternal')">
                    <label for="eksternal" class="block text-center py-4 rounded-2xl bg-[#F0EFE9] border border-[#D6D1C4] cursor-pointer font-bold">Eksternal</label>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold mb-4 text-[#5B4636]">TIPE PESERTA</label>
                <div class="grid grid-cols-2 gap-4">
                    <input type="radio" id="narsum" name="tipe_pegawai" value="narasumber" class="hidden radio-custom" required>
                    <label for="narsum" class="block text-center py-4 rounded-2xl bg-[#F0EFE9] border border-[#D6D1C4] cursor-pointer font-bold">Narasumber</label>

                    <input type="radio" id="peserta" name="tipe_pegawai" value="pegawai" class="hidden radio-custom">
                    <label for="peserta" class="block text-center py-4 rounded-2xl bg-[#F0EFE9] border border-[#D6D1C4] cursor-pointer font-bold">Pegawai</label>
                </div>
            </div>

            <div id="dynamic-form" class="pt-8 border-t border-[#D6D1C4] min-h-[250px]">
                <div class="text-center text-gray-500 italic">Pilih status pegawai di atas...</div>
            </div>
        </div>

        {{-- KANAN: TANDA TANGAN & SUBMIT --}}
        <div class="glass-card rounded-[3rem] p-10 border-t-4 border-[#5B4636] flex flex-col">
            <div class="flex justify-between mb-4">
                <h2 class="font-bold text-[#2F5F5E] uppercase text-xs tracking-widest"><i class="fa-solid fa-pen-nib"></i> Tanda Tangan</h2>
                <button type="button" onclick="clearPad()" class="text-red-500 text-xs font-bold hover:underline">RESET</button>
            </div>

            <canvas id="sig-pad" class="w-full h-72 mb-6"></canvas>

            <div class="flex justify-center mb-6 scale-90 md:scale-100">
                <div class="g-recaptcha" data-sitekey="YOUR_RECAPTCHA_SITE_KEY"></div>
            </div>

            <button type="submit" id="btnSubmit" class="bg-gradient-to-r from-[#2F5F5E] to-[#5B4636] py-5 rounded-2xl font-black tracking-widest text-white shadow-xl hover:scale-[1.02] transition-transform active:scale-95 disabled:opacity-50">
                KIRIM PRESENSI <i class="fa-solid fa-paper-plane ml-2"></i>
            </button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
// --- LOGIC GPS & DISTANCE ---
const TARGET_LOKASI = { lat: {{ $kegiatan->latitude }}, lng: {{ $kegiatan->longitude }} };
const RADIUS_MAKSIMAL = 25; 
const pegawai = @json($pegawai);
const opd = {{ $kegiatan->opd_id }};
const box = document.getElementById('dynamic-form');

function getDistance(lat1, lon1, lat2, lon2) {
    const R = 6371e3;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLon/2) * Math.sin(dLon/2);
    return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
}

if (navigator.geolocation) {
    navigator.geolocation.watchPosition(position => {
        const uLat = position.coords.latitude;
        const uLng = position.coords.longitude;
        document.getElementById('lat').value = uLat;
        document.getElementById('lng').value = uLng;

        const jarak = getDistance(uLat, uLng, TARGET_LOKASI.lat, TARGET_LOKASI.lng);
        const warningBox = document.getElementById('location-warning');
        const warningText = document.getElementById('warning-text');
        const btnSubmit = document.getElementById('btnSubmit');

        if (jarak > RADIUS_MAKSIMAL) {
            warningBox.classList.remove('hidden');
            warningText.innerHTML = `Luar Jangkauan (${Math.round(jarak)}m). Harap mendekat ke lokasi.`;
            btnSubmit.disabled = true;
        } else {
            warningBox.classList.add('hidden');
            btnSubmit.disabled = false;
        }
    }, null, { enableHighAccuracy: true });
}

function renderForm(type){
    if(type === 'internal') {
        box.innerHTML = `
            <label class="text-xs font-bold text-[#5B4636] mb-2 block uppercase">Nama Pegawai</label>
            <input id="namaInput" name="nama" class="w-full p-4 rounded-xl mb-4 border focus:ring-2 focus:ring-[#2F5F5E] font-semibold" oninput="handleNamaInput(this.value)" required list="listNama" placeholder="Cari nama...">
            <datalist id="listNama"></datalist>
            
            <label class="text-xs font-bold text-[#5B4636] mb-2 block uppercase">NIP</label>
            <input id="nipInput" name="nip" class="w-full p-4 rounded-xl mb-4 border focus:ring-2 focus:ring-[#2F5F5E] font-semibold" placeholder="NIP Otomatis" oninput="handleNipInput(this.value)" required list="listNip">
            <datalist id="listNip"></datalist>
            
            <input type="hidden" name="pegawai_id" id="pegawaiId">
        `;
    } else {
        box.innerHTML = `
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-[#5B4636] uppercase tracking-wider">Nama Lengkap</label>
                    <input name="nama" class="w-full p-3 rounded-xl mt-1 border focus:ring-2 focus:ring-[#2F5F5E]" placeholder="Ketik nama lengkap..." required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-[#5B4636] uppercase tracking-wider">Instansi / Unit Kerja</label>
                    <input name="unit_kerja" class="w-full p-3 rounded-xl mt-1 border focus:ring-2 focus:ring-[#2F5F5E]" placeholder="Asal instansi" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-[#5B4636] uppercase tracking-wider">NIP / NIK</label>
                        <input name="nip" class="w-full p-3 rounded-xl mt-1 border" placeholder="Opsional">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-[#5B4636] uppercase tracking-wider">Jabatan</label>
                        <input name="jabatan" class="w-full p-3 rounded-xl mt-1 border" placeholder="Jabatan" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-[#5B4636] uppercase tracking-wider">Email</label>
                        <input name="email" type="email" class="w-full p-3 rounded-xl mt-1 border" placeholder="alamat@email.com" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-[#5B4636] uppercase tracking-wider">Nomor WA</label>
                        <input name="telp" class="w-full p-3 rounded-xl mt-1 border" placeholder="08123xxx" required>
                    </div>
                </div>
                <input type="hidden" name="pegawai_id" value="">
            </div>
        `;
    }
}

// Handle Auto-complete Internal
function handleNamaInput(keyword){
    const list = document.getElementById('listNama'); list.innerHTML = '';
    const data = pegawai.filter(p => p.opd_id == opd && p.nama.toLowerCase().includes(keyword.toLowerCase()));
    data.forEach(p => { const o = document.createElement('option'); o.value = p.nama; list.appendChild(o); });
    const exact = data.find(p => p.nama === keyword);
    if(exact) { 
        document.getElementById('nipInput').value = exact.nip || ''; 
        document.getElementById('pegawaiId').value = exact.id_pegawai; 
    }
}

function handleNipInput(keyword){
    const list = document.getElementById('listNip'); list.innerHTML = '';
    const data = pegawai.filter(p => p.opd_id == opd && p.nip && p.nip.includes(keyword));
    data.forEach(p => { const o = document.createElement('option'); o.value = p.nip; list.appendChild(o); });
    const exact = data.find(p => p.nip === keyword);
    if(exact) { 
        document.getElementById('namaInput').value = exact.nama; 
        document.getElementById('pegawaiId').value = exact.id_pegawai; 
    }
}

// --- LOGIC SIGNATURE PAD ---
const canvas = document.getElementById('sig-pad');
const ctx = canvas.getContext('2d');
let drawing = false;

// Set canvas size based on container
function resizeCanvas() {
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
}
window.addEventListener('resize', resizeCanvas);
resizeCanvas();

function getPos(e) {
    const rect = canvas.getBoundingClientRect();
    const cx = e.touches ? e.touches[0].clientX : e.clientX;
    const cy = e.touches ? e.touches[0].clientY : e.clientY;
    return { x: cx - rect.left, y: cy - rect.top };
}

canvas.addEventListener('mousedown', (e) => { drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
canvas.addEventListener('mousemove', (e) => { 
    if(!drawing) return; 
    e.preventDefault(); 
    const p = getPos(e); 
    ctx.lineTo(p.x, p.y); 
    ctx.lineWidth = 3; 
    ctx.lineCap = "round";
    ctx.strokeStyle = "#2F5F5E";
    ctx.stroke(); 
});
window.addEventListener('mouseup', () => drawing = false);

// Mobile Support
canvas.addEventListener('touchstart', (e) => { drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); }, {passive: false});
canvas.addEventListener('touchmove', (e) => { 
    if(!drawing) return; 
    e.preventDefault(); 
    const p = getPos(e); 
    ctx.lineTo(p.x, p.y); 
    ctx.stroke(); 
}, {passive: false});

function clearPad(){ ctx.clearRect(0,0,canvas.width,canvas.height); }

// --- VALIDATION ---
function validateForm(){
    const lat = document.getElementById('lat').value;
    if(!lat) { Swal.fire('GPS Belum Siap', 'Mohon tunggu koordinat lokasi Anda.', 'warning'); return false; }
    
    // Tanda tangan check
    const blank = document.createElement('canvas');
    blank.width = canvas.width; blank.height = canvas.height;
    if(canvas.toDataURL() === blank.toDataURL()){ 
        Swal.fire('Tanda Tangan', 'Wajib diisi!', 'warning'); 
        return false; 
    }
    
    document.getElementById('tanda_tangan_input').value = canvas.toDataURL();
    
    if(grecaptcha.getResponse() === ""){ 
        Swal.fire('Captcha', 'Selesaikan verifikasi.', 'info'); 
        return false; 
    }

    Swal.fire({
        title: 'Mengirim Data...',
        text: 'Sedang mencatat kehadiran Anda',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading() }
    });

    return true;
}
</script>

{{-- Flash Messages --}}
@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
</script>
@endif
@if(session('error'))
<script>
    Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}" });
</script>
@endif
@endpush