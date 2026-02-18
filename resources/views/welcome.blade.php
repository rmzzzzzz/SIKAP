@extends('layouts.app')

@section('content')

{{-- CARD BESAR --}}
<div class="bg-white border-2 border-[#20B2AA]/20 rounded-[2rem] p-5 md:p-6 max-w-6xl mx-auto shadow-md">

    {{-- SEARCH --}}
    <div class="relative mb-6 group">
        <i class="fa-solid fa-search absolute left-5 top-1/2 -translate-y-1/2 text-[#20B2AA] text-base transition-colors"></i>
        <input type="text" id="searchInput" placeholder="Cari agenda kegiatan..."
            class="w-full bg-[#f0f9f8] border-2 border-[#20B2AA]/10 rounded-2xl py-3 pl-12 pr-5 text-base
                   focus:border-[#20B2AA] focus:bg-white outline-none transition-all shadow-none font-medium text-[#1a9690]">
    </div>

    {{-- JUDUL --}}
    <div class="flex items-center gap-2 mb-5">
        <div class="h-[2px] w-8 bg-[#20B2AA] rounded-full"></div>
        <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#20B2AA]">
            Agenda Terkini
        </h2>
    </div>

    {{-- GRID KEGIATAN --}}
    <div id="kegiatanContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        @forelse($kegiatan as $item)
            @php
                $waktuSelesai = \Carbon\Carbon::parse($item->tanggal . ' ' . $item->waktu_selesai);
                $isExpired = \Carbon\Carbon::now()->greaterThan($waktuSelesai);
            @endphp

        {{-- KEGIATAN CARD --}}
        <div class="kegiatan-card bg-white rounded-[1.5rem] overflow-hidden flex flex-col
                    border-2 border-[#20B2AA]/10 hover:border-[#20B2AA] transition-all duration-300 group shadow-sm">

            <div class="p-5">

                {{-- HEADER CARD --}}
                <div class="flex items-start gap-3 mb-4">
                    <div class="bg-[#20B2AA] w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center
                                font-black text-sm text-white transition-transform group-hover:scale-105 shadow-md shadow-[#20B2AA]/20">
                        {{ $loop->iteration }}
                    </div>

                    <div class="flex-1">
                        <h3 class="nama-kegiatan font-extrabold text-sm uppercase text-[#20B2AA] leading-tight line-clamp-2">
                            {{ $item->nama_kegiatan }}
                        </h3>
                    </div>
                </div>

                {{-- INFO LIST (3 LIST) --}}
                <div class="space-y-2">
                    {{-- 1. TANGGAL & WAKTU --}}
                    <div class="flex items-center gap-3 bg-[#f0f9f8] border border-[#20B2AA]/10 p-3 rounded-xl">
                        <div class="w-8 h-8 bg-white border border-[#20B2AA]/10 rounded-lg flex-shrink-0 flex items-center justify-center text-[#20B2AA]">
                            <i class="fa-regular fa-calendar-check text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-[#1a9690]">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-[9px] font-bold text-[#20B2AA] opacity-70 text-nowrap">
                                {{ substr($item->waktu_mulai,0,5) }} - {{ substr($item->waktu_selesai,0,5) }} WIB
                            </p>
                        </div>
                    </div>

                    {{-- 2. PIC --}}
                    <div class="flex items-center gap-3 bg-[#f0f9f8] border border-[#20B2AA]/10 p-3 rounded-xl">
                        <div class="w-8 h-8 bg-white border border-[#20B2AA]/10 rounded-lg flex-shrink-0 flex items-center justify-center text-[#20B2AA]">
                            <i class="fa-solid fa-user-tie text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[9px] font-black text-[#20B2AA]/50 uppercase tracking-tighter">Penanggung Jawab</p>
                            <p class="text-[11px] font-bold text-[#1a9690] line-clamp-1">
                                {{ $item->pegawai->nama ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- 3. LOKASI --}}
                    <div class="flex items-center gap-3 bg-[#f0f9f8] border border-[#20B2AA]/10 p-3 rounded-xl">
                        <div class="w-8 h-8 bg-white border border-[#20B2AA]/10 rounded-lg flex-shrink-0 flex items-center justify-center text-[#20B2AA]">
                            <i class="fa-solid fa-location-dot text-xs"></i>
                        </div>
                        <div class="flex-1">
                             <p class="text-[9px] font-black text-[#20B2AA]/50 uppercase tracking-tighter">Lokasi</p>
                            <p class="text-[11px] font-bold text-[#1a9690] line-clamp-1">
                                {{ $item->lokasi }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="px-5 pb-5 mt-auto">
                @if($isExpired)
                    {{-- Tampilan Tombol Selesai (Sama dengan tombol aktif namun memicu alert) --}}
                    <button type="button" onclick="showExpiredAlert()"
                        class="w-full bg-[#f0f9f8] border-2 border-[#20B2AA] text-[#20B2AA] hover:bg-[#20B2AA] hover:text-white
                               text-center py-2.5 rounded-xl font-black text-[10px] tracking-[0.15em] uppercase 
                               flex justify-center gap-2 transition-all duration-300 active:scale-95 shadow-sm hover:shadow-[#20B2AA]/20">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        SESI BERAKHIR
                    </button>
                @else
                    <a href="{{ url('/hadir/'.$item->id_kegiatan) }}"
                        class="bg-[#f0f9f8] border-2 border-[#20B2AA] text-[#20B2AA] hover:bg-[#20B2AA] hover:text-white
                               text-center py-2.5 rounded-xl font-black text-[10px] tracking-[0.15em] uppercase 
                               flex justify-center gap-2 transition-all duration-300 active:scale-95 shadow-sm hover:shadow-[#20B2AA]/20">
                        <i class="fa-solid fa-signature"></i>
                        DAFTAR HADIR
                    </a>
                @endif
            </div>
        </div>

        @empty
        <div class="col-span-full text-center py-10">
            <p class="font-bold text-[#1a9690]/30 text-xs tracking-widest uppercase">Kosong</p>
        </div>
        @endforelse
    </div>
</div>

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showExpiredAlert() {
        Swal.fire({
            title: 'SESI BERAKHIR',
            text: 'Maaf, waktu pengisian daftar hadir untuk kegiatan ini sudah ditutup.',
            icon: 'error',
            confirmButtonColor: '#20B2AA',
            confirmButtonText: 'MENGERTI',
            customClass: {
                popup: 'rounded-[1.5rem]',
                confirmButton: 'rounded-xl font-bold tracking-widest text-[10px] py-3 px-8'
            }
        });
    }

    document.getElementById('searchInput').addEventListener('keyup', function() {
        let searchTerm = this.value.toLowerCase();
        let cards = document.querySelectorAll('.kegiatan-card');
        
        cards.forEach(card => {
            let title = card.querySelector('.nama-kegiatan').innerText.toLowerCase();
            if(title.includes(searchTerm)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>

@endsection