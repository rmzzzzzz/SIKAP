@extends('layouts.app')

@section('title', 'Jadwal Kegiatan OPD - SIKAP SUMENEP')

@push('styles')
<style>
    /* Import Font Modern jika belum ada di layout utama */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --primary-teal: #2F5F5E;
        --text-brown: #5B4636;
        --border-color: #EDEBE4;
        --bg-light: #FDFDFB;
    }

    .table-container { 
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background: white; 
        border-radius: 1.25rem; 
        overflow: hidden; 
        border: 2px solid #D6D1C4; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); 
    }

    table { width: 100%; border-collapse: collapse; table-layout: fixed; }

    /* Header Styling */
    thead { background: var(--primary-teal); color: white; }
    th { 
        padding: 1rem; 
        text-transform: uppercase; 
        font-size: 0.75rem; 
        letter-spacing: 0.05em; 
        font-weight: 700;
        border-right: 1px solid rgba(255, 255, 255, 0.15);
        text-align: left;
    }
    th.text-center { text-align: center; }

    /* Body Styling */
    td { 
        padding: 1rem; 
        border-bottom: 1.5px solid var(--border-color); 
        border-right: 1px solid var(--border-color); 
        font-size: 0.813rem; /* Ukuran font standar (13px) */
        color: #374151;
        vertical-align: middle;
        line-height: 1.5;
        word-wrap: break-word;
    }
    
    td:last-child, th:last-child { border-right: none; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background-color: var(--bg-light); }

    /* Penyeragaman Ukuran Teks Spesifik */
    .kegiatan-title { 
        color: var(--primary-teal); 
        font-weight: 700; 
        font-size: 0.813rem; 
        text-transform: uppercase; 
    }

    .waktu-text { 
        font-weight: 700; 
        color: var(--text-brown); 
        font-size: 0.813rem; 
    }

    .lokasi-text { 
        font-size: 0.75rem; 
        color: #6B7280; 
        font-weight: 500;
    }

    .pic-name { 
        color: var(--primary-teal); 
        font-weight: 700; 
        font-size: 0.75rem; 
        text-transform: uppercase; 
    }

    .opd-badge { 
        background: #F3F4F6; 
        color: #374151; 
        padding: 4px 8px; 
        border-radius: 6px; 
        font-size: 0.7rem; 
        font-weight: 600; 
        border: 1px solid #D1D5DB;
        display: inline-block;
    }

    /* Penyeragaman List Peserta */
    .participant-item {
        font-size: 0.7rem;
        font-weight: 600;
        color: #4B5563;
        background: #F9FAFB;
        padding: 2px 6px;
        border-radius: 4px;
        border-left: 2px solid var(--primary-teal);
        margin-bottom: 2px;
    }
</style>
@endpush

@section('content')
    {{-- HEADER --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-extrabold uppercase text-[#2F5F5E] tracking-tight">Jadwal Kegiatan Hari Ini</h1>
        <p class="text-[#5B4636] mt-1 font-semibold uppercase tracking-widest text-[10px] opacity-80">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} | <span id="clock" class="font-bold">00:00:00</span> WIB
        </p>
    </div>

    {{-- FILTER --}}
    <div class="flex justify-end mb-5">
        <select id="opd-filter" onchange="filterOPD(this.value)" class="w-full md:w-72 p-3 rounded-xl bg-white border-2 border-[#D6D1C4] focus:ring-2 focus:ring-[#2F5F5E] outline-none font-bold text-[11px] uppercase cursor-pointer shadow-sm">
            <option value="all">🔍 SEMUA UNIT KERJA / OPD</option>
            @foreach($list_opd as $o)
                <option value="{{ $o->id_opd }}">{{ $o->nama_opd }}</option>
            @endforeach
        </select>
    </div>

    {{-- TABEL --}}
    <div class="table-container mb-10">
        <div class="overflow-x-auto">
            <table id="table-kegiatan">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th style="width: 200px;">Kegiatan</th>
                        <th class="text-center" style="width: 80px;">Waktu</th>
                        <th style="width: 180px;">Tempat</th>
                        <th style="width: 150px;">PIC</th>
                        <th style="width: 150px;">Unit Kerja</th>
                        <th style="width: 180px;">Daftar Peserta</th>
                    </tr>
                </thead>
                <tbody id="kegiatan-body">
                    @forelse($kegiatan as $index => $item)
                    <tr class="kegiatan-row" data-opd="{{ $item->opd_id }}">
                        <td class="text-center font-bold text-gray-400 iteration-count">{{ $index + 1 }}</td>
                        <td><div class="kegiatan-title">{{ $item->nama_kegiatan }}</div></td>
                        <td class="text-center waktu-text">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}</td>
                        <td>
                            <div class="flex items-start gap-1.5">
                                <i class="fa-solid fa-location-dot text-[#2F5F5E] mt-0.5 text-[10px]"></i>
                                <span class="lokasi-text">{{ $item->lokasi }}</span>
                            </div>
                        </td>
                        <td><span class="pic-name">{{ $item->pegawai->nama ?? '-' }}</span></td>
                        <td><span class="opd-badge">{{ $item->opd->nama_opd ?? 'Umum' }}</span></td>
                        <td>
                            <div class="flex flex-col gap-1">
                                @foreach($item->pegawaiWajib->take(3) as $peserta)
                                    <div class="participant-item truncate">{{ $peserta->nama }}</div>
                                @endforeach
                                @if($item->pegawaiWajib->count() > 3)
                                    <span class="text-[9px] font-bold text-[#2F5F5E] italic">
                                        +{{ $item->pegawaiWajib->count() - 3 }} lainnya
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center text-gray-400 font-bold uppercase text-[11px]">
                            Tidak ada jadwal kegiatan untuk saat ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const clock = document.getElementById('clock');
            if (clock) {
                clock.textContent = now.toLocaleTimeString('id-ID', { 
                    hour: '2-digit', minute: '2-digit', second: '2-digit' 
                }).replace(/\./g, ':');
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        function filterOPD(opdId) {
            const rows = document.querySelectorAll('.kegiatan-row');
            let visibleCount = 0;
            rows.forEach(row => {
                if (opdId === 'all' || row.getAttribute('data-opd') === opdId) {
                    row.style.display = '';
                    visibleCount++;
                    row.querySelector('.iteration-count').textContent = visibleCount;
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
@endsection