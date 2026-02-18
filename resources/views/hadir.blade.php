@extends('layouts.app')

@section('title', 'Presensi - SIKAP SUMENEP')

@push('styles')
<style>
    .radio-custom:checked + label { 
        background: #20B2AA; 
        border-color: #20B2AA; 
        color: white; 
        box-shadow: 0 4px 12px rgba(32,178,170,.2); 
    }
    
    input, select, textarea { 
        background-color: #ffffff !important; 
        color: #1f2937 !important; 
        border: 1px solid #e5e7eb !important; 
        padding: 0.75rem !important; 
        border-radius: 0.75rem !important;
    }

    input:focus {
        border-color: #20B2AA !important;
        outline: none;
        box-shadow: 0 0 0 3px rgba(32, 178, 170, 0.1);
    }

    #sig-pad { 
        background: #f9fafb; 
        border-radius: 1rem; 
        border: 2px dashed #d1d5db; 
        touch-action: none; 
        cursor: crosshair; 
    }
    
    .main-card {
        background: white;
        border-radius: 2rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        border: 4px solid #20B2AA; 
        position: relative;
        overflow: hidden;
    }

    .btn-submit {
        background: #20B2AA;
        transition: all 0.3s ease;
    }

    .btn-submit:hover:not(:disabled) {
        background: #1a9690;
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(32, 178, 170, 0.4);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.4s ease-out forwards; }
</style>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12"> 
    <div class="main-card animate-fadeIn">
        <div class="p-6 md:p-10 relative z-20">
            <div class="text-center mb-8">
                <div class="inline-block px-4 py-1 rounded-full bg-[#E0F2F1] text-[#20B2AA] text-[10px] font-bold tracking-widest uppercase mb-3">
                    Presensi Digital
                </div>
                <h1 class="text-2xl md:text-3xl font-black uppercase text-gray-800 leading-tight mb-2">
                    {{ $kegiatan->nama_kegiatan }}
                </h1>
                <p class="text-sm text-gray-500 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-location-dot text-[#20B2AA]"></i> {{ $kegiatan->lokasi }}
                </p>
            </div>

            <hr class="mb-8 border-gray-100">

            <div id="location-warning" class="hidden bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl mb-8 text-center text-sm font-medium">
                <div class="flex items-center justify-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation animate-pulse"></i>
                    <span id="warning-text">Mendeteksi lokasi...</span>
                </div>
            </div>

            <form action="{{ url('/hadir/'.$kegiatan->id_kegiatan) }}" method="POST" id="formPresensi" onsubmit="return validateForm()">
                @csrf
                <input type="hidden" name="latitude_hadir" id="lat">
                <input type="hidden" name="longitude_hadir" id="lng">
                <input type="hidden" name="tanda_tangan" id="tanda_tangan_input">

                <div class="space-y-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold mb-3 text-gray-700 uppercase tracking-wider">Status Pegawai</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="radio" id="internal" name="status_pegawai" value="internal" class="hidden radio-custom" onclick="renderForm('internal')" required>
                                <label for="internal" class="block text-center py-2.5 rounded-xl border border-gray-200 cursor-pointer font-bold text-xs transition-all hover:bg-gray-50">Internal</label>

                                <input type="radio" id="eksternal" name="status_pegawai" value="eksternal" class="hidden radio-custom" onclick="renderForm('eksternal')">
                                <label for="eksternal" class="block text-center py-2.5 rounded-xl border border-gray-200 cursor-pointer font-bold text-xs transition-all hover:bg-gray-50">Eksternal</label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold mb-3 text-gray-700 uppercase tracking-wider">Tipe Peserta</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="radio" id="narsum" name="tipe_pegawai" value="narasumber" class="hidden radio-custom" required>
                                <label for="narsum" class="block text-center py-2.5 rounded-xl border border-gray-200 cursor-pointer font-bold text-xs transition-all hover:bg-gray-50">Narasumber</label>

                                <input type="radio" id="peserta" name="tipe_pegawai" value="pegawai" class="hidden radio-custom">
                                <label for="peserta" class="block text-center py-2.5 rounded-xl border border-gray-200 cursor-pointer font-bold text-xs transition-all hover:bg-gray-50">Pegawai</label>
                            </div>
                        </div>
                    </div>

                    <div id="dynamic-form" class="bg-gray-50 p-5 rounded-2xl border border-gray-100 min-h-[100px]">
                        <div class="text-center text-gray-400 text-xs italic py-4">Silakan pilih status pegawai untuk mengisi data...</div>
                    </div>
                </div>

                <div class="pt-8 border-t border-dashed border-gray-200">
                    <div class="flex justify-between items-end mb-3">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Tanda Tangan Digital</label>
                        <button type="button" onclick="clearPad()" class="text-[#20B2AA] text-[10px] font-bold hover:underline mb-0.5 uppercase tracking-tighter">Hapus Coretan</button>
                    </div>
                    
                    <div class="relative mb-8">
                        <canvas id="sig-pad" class="w-full h-64 md:h-80 shadow-inner"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-5">
                            <i class="fa-solid fa-pen-nib text-8xl"></i>
                        </div>
                    </div>

                    <div class="flex flex-col items-center space-y-6">
                        <div class="g-recaptcha scale-90 md:scale-100" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>

                        <button type="submit" id="btnSubmit" class="btn-submit w-full md:w-2/3 py-4 rounded-xl font-black tracking-[0.2em] text-white shadow-lg disabled:opacity-50 disabled:grayscale uppercase">
                            Kirim Presensi Sekarang <i class="fa-solid fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="bg-gray-50 py-4 px-6 text-center border-t border-gray-100 rounded-b-2xl">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">BKPSDM KABUPATEN SUMENEP</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const TARGET_LOKASI = { lat: {{ $kegiatan->latitude }}, lng: {{ $kegiatan->longitude }} };
const RADIUS_MAKSIMAL = 25; 
const pegawai = @json($pegawai);
const box = document.getElementById('dynamic-form');

// --- LOGIKA NOTIFIKASI & IZIN ---
document.addEventListener('DOMContentLoaded', function() {
    // 1. Cek jika ada notifikasi sukses (Prioritas Tertinggi)
    @if(session('success'))
        Swal.fire({ 
            icon: 'success', 
            title: 'Berhasil!', 
            text: "{{ session('success') }}", 
            confirmButtonColor: '#20B2AA' 
        });
    // 2. Cek jika ada notifikasi error
    @elseif(session('error'))
        Swal.fire({ 
            icon: 'error', 
            title: 'Opps!', 
            text: "{{ session('error') }}", 
            confirmButtonColor: '#20B2AA' 
        });
    // 3. Jika tidak ada notifikasi, baru tampilkan pop-up izin lokasi
    @else
        Swal.fire({
            title: 'IZIN AKSES LOKASI',
            html: 'Aplikasi memerlukan akses GPS untuk memvalidasi kehadiran Anda.<br><small class="text-gray-500 italic">*Pastikan GPS Anda aktif</small>',
            iconHtml: '<i class="fa-solid fa-location-crosshairs text-[#20B2AA]"></i>',
            showCancelButton: false,
            confirmButtonColor: '#20B2AA',
            confirmButtonText: 'IZINKAN AKSES',
            allowOutsideClick: false,
            customClass: {
                popup: 'rounded-[2rem] border-4 border-[#20B2AA]',
                title: 'font-black text-gray-800 tracking-tight',
                confirmButton: 'rounded-xl font-bold tracking-[0.2em] px-8 py-3 uppercase text-[10px]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                initLocationTracking();
            }
        });
    @endif
});

function getDistance(lat1, lon1, lat2, lon2) {
    const R = 6371e3;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLon/2) * Math.sin(dLon/2);
    return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
}

function initLocationTracking() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(position => {
            const uLat = position.coords.latitude;
            const uLng = position.coords.longitude;
            document.getElementById('lat').value = uLat;
            document.getElementById('lng').value = uLng;
            
            const jarak = getDistance(uLat, uLng, TARGET_LOKASI.lat, TARGET_LOKASI.lng);
            const warningBox = document.getElementById('location-warning');
            const btnSubmit = document.getElementById('btnSubmit');
            
            if (jarak > RADIUS_MAKSIMAL) {
                warningBox.classList.remove('hidden');
                document.getElementById('warning-text').innerHTML = `Luar Jangkauan (${Math.round(jarak)}m dari titik lokasi).`;
                btnSubmit.disabled = true;
            } else {
                warningBox.classList.add('hidden');
                btnSubmit.disabled = false;
            }
        }, error => {
            Swal.fire({
                icon: 'error',
                title: 'Akses Lokasi Ditolak',
                text: 'Presensi tidak dapat dilakukan tanpa akses GPS.',
                confirmButtonColor: '#20B2AA'
            });
        }, { enableHighAccuracy: true });
    }
}

function renderForm(type){
    if(type === 'internal') {
        box.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-fadeIn">
                <div>
                    <label class="text-[10px] font-bold text-gray-600 mb-1.5 block uppercase">Nama Pegawai (Min 3 Huruf)</label>
                    <input id="namaInput" name="nama" class="w-full text-sm rounded-lg border" oninput="handleNamaInput(this.value)" required list="listNama" placeholder="Ketik minimal 3 huruf...">
                    <datalist id="listNama"></datalist>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-600 mb-1.5 block uppercase">NIP</label>
                    <input id="nipInput" name="nip" class="w-full text-sm rounded-lg border bg-white" placeholder="Otomatis" oninput="handleNipInput(this.value)" required list="listNip">
                    <datalist id="listNip"></datalist>
                </div>
                <input type="hidden" name="pegawai_id" id="pegawaiId">
            </div>
        `;
    } else {
        box.innerHTML = `
            <div class="space-y-4 animate-fadeIn">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[9px] font-bold text-gray-600 uppercase">Nama Lengkap</label>
                        <input name="nama" class="w-full p-2 text-sm rounded-lg mt-1 border" required>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-600 uppercase">Instansi</label>
                        <input name="unit_kerja" class="w-full p-2 text-sm rounded-lg mt-1 border" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="col-span-1 md:col-span-2">
                        <label class="text-[9px] font-bold text-gray-600 uppercase">Jabatan</label>
                        <input name="jabatan" class="w-full p-2 text-sm rounded-lg mt-1 border" required>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-600 uppercase">NIP/NIK</label>
                        <input name="nip" class="w-full p-2 text-sm rounded-lg mt-1 border">
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-600 uppercase">No. WA</label>
                        <input name="telp" class="w-full p-2 text-sm rounded-lg mt-1 border" required>
                    </div>
                </div>
                <input type="hidden" name="pegawai_id" value="">
            </div>
        `;
    }
}

function handleNamaInput(keyword){
    const list = document.getElementById('listNama'); 
    list.innerHTML = ''; 
    if(keyword.length >= 3) {
        const data = pegawai.filter(p => p.nama.toLowerCase().includes(keyword.toLowerCase()));
        data.slice(0, 10).forEach(p => { 
            const o = document.createElement('option'); 
            o.value = p.nama; 
            list.appendChild(o); 
        });
        const exact = data.find(p => p.nama === keyword);
        if(exact) { 
            document.getElementById('nipInput').value = exact.nip || ''; 
            document.getElementById('pegawaiId').value = exact.id_pegawai; 
        }
    }
}

function handleNipInput(keyword){
    const list = document.getElementById('listNip'); 
    list.innerHTML = '';
    if(keyword.length >= 3) {
        const data = pegawai.filter(p => p.nip && p.nip.includes(keyword));
        data.slice(0, 10).forEach(p => { 
            const o = document.createElement('option'); 
            o.value = p.nip; 
            list.appendChild(o); 
        });
        const exact = data.find(p => p.nip === keyword);
        if(exact) { 
            document.getElementById('namaInput').value = exact.nama; 
            document.getElementById('pegawaiId').value = exact.id_pegawai; 
        }
    }
}

const canvas = document.getElementById('sig-pad');
const ctx = canvas.getContext('2d');
let drawing = false;

function resizeCanvas() { 
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio; 
    canvas.height = canvas.offsetHeight * ratio; 
    ctx.scale(ratio, ratio);
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
    ctx.lineWidth = 2.5; 
    ctx.lineCap = "round";
    ctx.strokeStyle = "#000000"; 
    ctx.stroke(); 
});
window.addEventListener('mouseup', () => drawing = false);

canvas.addEventListener('touchstart', (e) => { 
    drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); 
}, {passive: false});
canvas.addEventListener('touchmove', (e) => { 
    if(!drawing) return; 
    e.preventDefault(); 
    const p = getPos(e); 
    ctx.lineTo(p.x, p.y); 
    ctx.strokeStyle = "#000000"; 
    ctx.stroke(); 
}, {passive: false});

function clearPad(){ ctx.clearRect(0,0,canvas.width,canvas.height); }

function validateForm(){
    const lat = document.getElementById('lat').value;
    if(!lat) { 
        Swal.fire({ icon: 'warning', title: 'GPS Belum Siap', text: 'Mohon aktifkan lokasi/tunggu sebentar.', confirmButtonColor: '#20B2AA' }); 
        return false; 
    }
    const blank = document.createElement('canvas');
    blank.width = canvas.width; blank.height = canvas.height;
    if(canvas.toDataURL() === blank.toDataURL()){ 
        Swal.fire({ icon: 'warning', title: 'Tanda Tangan', text: 'Tanda tangan wajib diisi!', confirmButtonColor: '#20B2AA' }); 
        return false; 
    }
    document.getElementById('tanda_tangan_input').value = canvas.toDataURL();
    if(grecaptcha.getResponse() === ""){ 
        Swal.fire({ icon: 'info', title: 'Captcha', text: 'Silakan selesaikan verifikasi Captcha.', confirmButtonColor: '#20B2AA' }); 
        return false; 
    }
    Swal.fire({ 
        title: 'Mengirim Data...', 
        text: 'Sedang memproses presensi Anda',
        allowOutsideClick: false, 
        didOpen: () => { Swal.showLoading() } 
    });
    return true;
}
</script>
@endpush