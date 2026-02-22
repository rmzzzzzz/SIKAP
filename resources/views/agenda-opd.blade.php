@extends('layouts.app')

@section('title', 'Jadwal Kegiatan OPD - SIKAP SUMENEP')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --primary-teal: #20B2AA; 
        --secondary-teal: #1a9690; 
        --bg-mint: #F0F9F8; 
        --dark-text: #374151;
        --border-soft: #B2E2E0; 
    }

    .table-container { 
        font-family: 'Inter', sans-serif;
        background: white; 
        border-radius: 1.25rem; 
        overflow: hidden; 
        border: 2px solid var(--primary-teal); 
        box-shadow: 0 4px 20px rgba(32, 178, 170, 0.08); 
    }

    table { 
        width: 100%; 
        border-collapse: collapse; 
        table-layout: auto; 
    }

    thead { 
        background: var(--primary-teal); 
        color: #ffffff; 
    }

    th { 
        padding: 1.1rem 1rem; 
        text-transform: uppercase; 
        font-size: 0.75rem; 
        letter-spacing: 0.08em; 
        font-weight: 800;
        border-right: 1px solid rgba(255, 255, 255, 0.3);
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    td { 
        padding: 1rem; 
        border-bottom: 1px solid var(--border-soft); 
        border-right: 1px solid var(--border-soft); 
        font-size: 0.813rem; 
        color: var(--dark-text);
        vertical-align: middle;
        line-height: 1.5;
        text-transform: capitalize;
        text-align: center;
    }
    
    td:last-child, th:last-child { border-right: none; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background-color: var(--bg-mint); } 

    .kegiatan-title { 
        color: var(--primary-teal); 
        font-weight: 700; 
        font-size: 0.813rem;
        text-transform: capitalize;
        min-width: 150px;
    }

    .waktu-text { 
        font-weight: 800; 
        color: var(--primary-teal); 
        font-size: 0.813rem; 
        white-space: nowrap;
    }

    .lokasi-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
    }

    .lokasi-text { 
        font-size: 0.75rem; 
        color: var(--secondary-teal); 
        font-weight: 500;
        opacity: 0.8;
        text-transform: capitalize;
    }

    .pic-name { 
        color: var(--primary-teal); 
        font-weight: 700; 
        font-size: 0.75rem; 
        text-transform: capitalize;
        white-space: nowrap; 
    }

    .opd-badge { 
        color: var(--secondary-teal); 
        font-size: 0.75rem; 
        font-weight: 800; 
        text-transform: capitalize;
        white-space: nowrap;
    }

    .participant-list {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        min-width: 140px;
    }

    .participant-item {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--secondary-teal);
        background: white;
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid var(--border-soft);
        text-transform: capitalize;
        width: fit-content;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-extrabold uppercase text-[#20B2AA] tracking-tight">Jadwal Kegiatan Hari Ini</h1>
        <div class="inline-block px-4 py-1 mt-1 rounded-full bg-[#F0F9F8] border border-[#B2E2E0]"> 
            <p class="text-[#1a9690] font-bold uppercase tracking-widest text-[10px]">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} | <span id="clock">00:00:00</span> WIB
            </p>
        </div>
    </div>

    <div class="flex justify-end mb-5">
        <select id="opd-filter" onchange="filterOPD(this.value)" 
            class="w-full md:w-72 p-3 rounded-xl bg-white border-2 border-[#B2E2E0] focus:border-[#20B2AA] outline-none font-bold text-[11px] uppercase cursor-pointer shadow-sm text-[#1a9690] transition-all">
            <option value="all">🔍 SEMUA UNIT KERJA / OPD</option>
            @foreach($list_opd as $o)
                <option value="{{ $o->id_opd }}">{{ $o->nama_opd }}</option>
            @endforeach
        </select>
    </div>

    <div class="table-container mb-10">
        <div class="overflow-x-auto">
            <table id="table-kegiatan">
                <thead>
                    <tr>
                        <th style="width: 1%;">No</th>
                        <th>Kegiatan</th>
                        <th>Waktu</th>
                        <th>Tempat</th>
                        <th>PIC</th>
                        <th>Unit Kerja</th>
                        <th>Daftar Peserta</th>
                    </tr>
                </thead>
                <tbody id="kegiatan-body">
                    @forelse($kegiatan as $index => $item)
                    <tr class="kegiatan-row" data-opd="{{ $item->opd_id }}">
                        <td class="font-bold text-gray-400 iteration-count">{{ $index + 1 }}</td>
                        <td><div class="kegiatan-title">{{ $item->nama_kegiatan }}</div></td>
                        <td>
                            <span class="waktu-text">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}</span>
                        </td>
                        <td>
                            <div class="lokasi-container">
                                <i class="fa-solid fa-location-dot text-[#20B2AA] text-[10px]"></i>
                                <span class="lokasi-text">{{ $item->lokasi }}</span>
                            </div>
                        </td>
                        <td><span class="pic-name">{{ $item->pegawai->nama ?? '-' }}</span></td>
                        <td><span class="opd-badge">{{ $item->opd->nama_opd ?? 'Umum' }}</span></td>
                        <td>
                            <div class="participant-list">
                                {{-- KUNCI: Hanya ambil 2 nama (take 2) --}}
                                @foreach($item->pegawaiWajib->take(2) as $peserta)
                                    <div class="participant-item truncate">{{ $peserta->nama }}</div>
                                @endforeach
                                
                                {{-- KUNCI: Hitung sisa jika lebih dari 2 --}}
                                @if($item->pegawaiWajib->count() > 2)
                                    <span class="text-[9px] font-bold text-[#20B2AA] italic px-1" style="text-transform: none; white-space: nowrap;">
                                        +{{ $item->pegawaiWajib->count() - 2 }} lainnya
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20">
                            <i class="fa-solid fa-calendar-xmark text-4xl text-[#B2E2E0] mb-3 block text-center"></i>
                            <p class="text-[#1a9690] opacity-40 font-bold uppercase text-[11px] text-center">Tidak ada jadwal kegiatan untuk saat ini</p>
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