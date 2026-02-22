@extends('layouts.app')

@section('content')

<style>
    /* Custom Scrollbar agar selaras dengan tema hijau */
    #kegiatanContainer::-webkit-scrollbar {
        width: 6px;
    }
    #kegiatanContainer::-webkit-scrollbar-track {
        background: transparent;
    }
    #kegiatanContainer::-webkit-scrollbar-thumb {
        background: #20B2AA33;
        border-radius: 10px;
    }
    #kegiatanContainer::-webkit-scrollbar-thumb:hover {
        background: #20B2AA;
    }

    /* Animasi Pop-Up Halus */
    .kegiatan-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
</style>

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
    <div class="flex items-center gap-2 mb-4">
        <div class="h-[2px] w-8 bg-[#20B2AA] rounded-full"></div>
        <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-[#20B2AA]">
            Agenda Terkini
        </h2>
    </div>

    {{-- GRID KEGIATAN --}}
    <div id="kegiatanContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[550px] overflow-y-auto pr-2 py-2">

        @php 
            $noUmum = 1; 
            $noInternal = 1;
        @endphp

        @forelse($kegiatan as $item)
            @php
                $waktuSelesai = \Carbon\Carbon::parse($item->tanggal . ' ' . $item->waktu_selesai);
                $isExpired = \Carbon\Carbon::now()->greaterThan($waktuSelesai);
                $isInternal = (isset($item->akses_kegiatan) && strtolower($item->akses_kegiatan) !== 'lintas opd');
            @endphp

        {{-- KEGIATAN CARD DENGAN ANIMASI POP UP --}}
        <div class="kegiatan-card bg-white rounded-[1.2rem] overflow-hidden flex flex-col
                    border-2 border-[#20B2AA]/10 hover:border-[#20B2AA] hover:-translate-y-2 hover:shadow-2xl hover:shadow-[#20B2AA]/20 
                    transition-all group cursor-default shadow-sm"
             data-search-name="{{ strtolower($item->nama_kegiatan) }}"
             data-internal="{{ $isInternal ? 'true' : 'false' }}"
             style="{{ $isInternal ? 'display: none !important;' : 'display: flex !important;' }}">

            <div class="p-4 pb-2">

                {{-- HEADER CARD --}}
                <div class="flex items-start gap-2 mb-3 pb-3 border-b border-[#20B2AA]/10">
                    <div class="bg-[#20B2AA] w-9 h-9 rounded-lg flex-shrink-0 flex items-center justify-center
                                font-black text-xs text-white transition-transform group-hover:rotate-12 group-hover:scale-110 shadow-md shadow-[#20B2AA]/20">
                        @if($isInternal)
                            {{ $noInternal++ }}
                        @else
                            {{ $noUmum++ }}
                        @endif
                    </div>

                    <div class="flex-1">
                        <h3 class="nama-kegiatan font-extrabold text-[13px] uppercase text-[#20B2AA] leading-tight line-clamp-2">
                            {{ $item->nama_kegiatan }}
                        </h3>
                        @if($isInternal)
                            <span class="text-[7px] font-bold bg-[#f0f9f8] text-[#1a9690] px-1.5 py-0.5 rounded mt-1 inline-block border border-[#20B2AA]/20 uppercase">INTERNAL</span>
                        @endif
                    </div>
                </div>

                {{-- INFO LIST --}}
                <div class="space-y-1.5">
                    {{-- WAKTU --}}
                    <div class="flex items-center gap-2 bg-[#f0f9f8] border border-[#20B2AA]/10 p-2 rounded-lg group-hover:bg-white transition-colors">
                        <div class="w-7 h-7 bg-white border border-[#20B2AA]/10 rounded flex-shrink-0 flex items-center justify-center text-[#20B2AA]">
                            <i class="fa-regular fa-calendar-check text-[10px]"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[8px] font-black text-[#0f5a56] uppercase tracking-tighter">Waktu</p>
                            <p class="text-[10px] font-bold text-[#20B2AA]">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-[8px] font-extrabold text-[#20B2AA]/80">
                                {{ substr($item->waktu_mulai,0,5) }} - {{ substr($item->waktu_selesai,0,5) }} WIB
                            </p>
                        </div>
                    </div>

                    {{-- PENYELENGGARA --}}
                    <div class="flex items-center gap-2 bg-[#f0f9f8] border border-[#20B2AA]/10 p-2 rounded-lg group-hover:bg-white transition-colors">
                        <div class="w-7 h-7 bg-white border border-[#20B2AA]/10 rounded flex-shrink-0 flex items-center justify-center text-[#20B2AA]">
                            <i class="fa-solid fa-building-shield text-[10px]"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[8px] font-black text-[#0f5a56] uppercase tracking-tighter">Penyelenggara</p>
                            <p class="text-[10px] font-bold text-[#20B2AA] line-clamp-1">
                                {{ $item->opd->nama_opd ?? 'OPD Tidak Diketahui' }}
                            </p>
                        </div>
                    </div>

                    {{-- LOKASI --}}
                    <div class="flex items-center gap-2 bg-[#f0f9f8] border border-[#20B2AA]/10 p-2 rounded-lg group-hover:bg-white transition-colors">
                        <div class="w-7 h-7 bg-white border border-[#20B2AA]/10 rounded flex-shrink-0 flex items-center justify-center text-[#20B2AA]">
                            <i class="fa-solid fa-location-dot text-[10px]"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[8px] font-black text-[#0f5a56] uppercase tracking-tighter">Lokasi</p>
                            <p class="text-[10px] font-bold text-[#20B2AA] line-clamp-1">
                                {{ $item->lokasi }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="px-4 pb-4 mt-auto pt-2 border-t border-dashed border-[#20B2AA]/20">
                @if($isExpired)
                    <button type="button" onclick="showExpiredAlert()"
                        class="w-full bg-[#f0f9f8] border border-[#20B2AA]/30 text-[#1a9690]/60
                               text-center py-2 rounded-lg font-black text-[9px] tracking-wider uppercase 
                               flex justify-center gap-2 transition-all cursor-not-allowed">
                        <i class="fa-solid fa-clock-rotate-left"></i> SESI BERAKHIR
                    </button>
                @else
                    <a href="{{ url('/hadir/'.$item->id_kegiatan) }}"
                        class="bg-[#20B2AA] text-white hover:bg-[#1a9690] active:bg-[#0f5a56]
                               text-center py-2 rounded-lg font-black text-[9px] tracking-wider uppercase 
                               flex justify-center gap-2 transition-all duration-200 active:scale-95 group-hover:scale-[1.02] shadow-sm">
                        <i class="fa-solid fa-signature"></i> DAFTAR HADIR
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showExpiredAlert() {
        Swal.fire({
            title: 'SESI BERAKHIR',
            text: 'Maaf, waktu pengisian daftar hadir untuk kegiatan ini sudah ditutup.',
            icon: 'error',
            confirmButtonColor: '#20B2AA',
            confirmButtonText: 'MENGERTI'
        });
    }

    document.getElementById('searchInput').addEventListener('input', function() {
        let searchTerm = this.value.toLowerCase().trim();
        let cards = document.querySelectorAll('.kegiatan-card');
        
        cards.forEach(card => {
            let searchName = card.getAttribute('data-search-name');
            let isInternal = card.getAttribute('data-internal') === 'true';

            if (searchTerm.length > 0) {
                if (searchName.includes(searchTerm)) {
                    card.style.setProperty('display', 'flex', 'important');
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            } else {
                if (isInternal) {
                    card.style.setProperty('display', 'none', 'important');
                } else {
                    card.style.setProperty('display', 'flex', 'important');
                }
            }
        });
    });
</script>

@endsection