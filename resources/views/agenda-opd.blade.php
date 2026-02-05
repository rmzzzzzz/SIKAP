@extends('layouts.app')

@section('title', 'Jadwal Kegiatan OPD - SIKAP SUMENEP')

@push('styles')
<style>
    /* Styling khusus untuk tabel jadwal */
    .table-container { 
        background: white; 
        border-radius: 1.5rem; 
        overflow: hidden; 
        border: 1px solid #D6D1C4; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); 
    }
    table { width: 100%; border-collapse: collapse; }
    thead { background: #2F5F5E; color: white; }
    th { padding: 1.25rem 1rem; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; font-weight: 800; }
    td { padding: 1.25rem 1rem; border-bottom: 1px solid #F3F1EA; font-size: 0.875rem; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background-color: #FDFDFB; }

    .opd-badge { background: #E9E6DC; color: #5B4636; padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 700; }
    .btn-presensi { 
        background: #2F5F5E; 
        color: white; 
        padding: 8px 16px; 
        border-radius: 10px; 
        font-weight: 700; 
        font-size: 0.75rem; 
        transition: all 0.2s; 
        display: inline-flex; 
        align-items: center; 
        gap: 8px; 
    }
    .btn-presensi:hover { background: #1f3f3e; transform: scale(1.05); }
</style>
@endpush

@section('content')
    {{-- HEADER HALAMAN --}}
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-black uppercase text-[#2F5F5E] tracking-tight">Jadwal Kegiatan Hari Ini</h1>
        <p class="text-[#5B4636] mt-2 font-bold opacity-80 uppercase tracking-widest text-xs">
            Tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} | <span id="clock">00:00:00</span> WIB
        </p>
    </div>

    {{-- FILTER OPD --}}
    <div class="flex justify-end mb-6">
        <div class="w-full md:w-80">
            <div class="relative">
                <select onchange="filterOPD(this.value)" class="w-full p-4 rounded-2xl bg-white border border-[#D6D1C4] focus:ring-2 focus:ring-[#2F5F5E] outline-none font-bold text-xs shadow-sm appearance-none cursor-pointer">
                    <option value="all">🔍 SEMUA UNIT KERJA / OPD</option>
                    @foreach($list_opd as $o)
                        <option value="{{ $o->id_opd }}">{{ $o->nama_opd }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#2F5F5E]">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL KEGIATAN --}}
    <div class="table-container mb-12">
        <div class="overflow-x-auto">
            <table id="table-kegiatan">
                <thead>
                    <tr>
                        <th class="text-center w-16">No</th>
                        <th class="text-left">Kegiatan</th>
                        <th class="text-center w-28">Waktu</th>
                        <th class="text-left">Tempat</th>
                        <th class="text-left">Unit Kerja</th>
                        <th class="text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kegiatan-body">
                    @forelse($kegiatan as $index => $item)
                    <tr class="kegiatan-row" data-opd="{{ $item->opd_id }}">
                        <td class="text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                        <td>
                            <div class="font-bold text-[#2F5F5E] leading-snug">{{ $item->nama_kegiatan }}</div>
                            <div class="text-[10px] text-gray-400 mt-1 uppercase tracking-tighter font-bold">ID: #{{ $item->id_kegiatan }}</div>
                        </td>
                        <td class="text-center font-bold text-[#5B4636]">
                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                        </td>
                        <td>
                            <div class="flex items-start gap-2">
                                <i class="fa-solid fa-location-dot text-[#2F5F5E] mt-1"></i>
                                <span class="text-gray-600 leading-tight font-medium">{{ $item->lokasi }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="opd-badge">{{ $item->opd->nama_opd ?? 'Umum' }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ url('/hadir/'.$item->id_kegiatan) }}" class="btn-presensi">
                                <i class="fa-solid fa-signature"></i> PRESENSI
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-24 text-center">
                            <i class="fa-solid fa-calendar-xmark text-6xl text-[#D6D1C4] mb-4"></i>
                            <p class="text-gray-400 font-bold uppercase text-xs tracking-[0.2em]">Tidak ada jadwal kegiatan untuk hari ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- FOOTER STRIP --}}
    <div class="text-center">
        <div class="bg-[#2F5F5E] py-3 px-8 inline-block rounded-2xl shadow-lg">
            <p class="text-white text-[10px] font-bold italic tracking-widest leading-none uppercase">
                "Bekerja Bersama untuk Sumenep Unggul"
            </p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Realtime Clock
    function updateClock() {
        const now = new Date();
        const timeStr = now.getHours().toString().padStart(2, '0') + ":" + 
                        now.getMinutes().toString().padStart(2, '0') + ":" + 
                        now.getSeconds().toString().padStart(2, '0');
        const clockEl = document.getElementById('clock');
        if(clockEl) clockEl.innerText = timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Filter Logic Berdasarkan OPD
    function filterOPD(val) {
        const rows = document.querySelectorAll('.kegiatan-row');
        let visibleCount = 0;

        rows.forEach(row => {
            if (val === 'all' || row.getAttribute('data-opd') === val) {
                row.style.display = '';
                visibleCount++;
                // Update nomor urut real-time agar tetap berurutan 1,2,3...
                row.querySelector('td:first-child').innerText = visibleCount;
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush