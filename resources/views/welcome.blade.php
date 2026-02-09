@extends('layouts.app')

@section('title', 'Beranda - SIKAP SUMENEP')

@section('content')
    {{-- SEARCH --}}
    <div class="relative mb-12 group">
        <i class="fa-solid fa-search absolute left-6 top-1/2 -translate-y-1/2 text-[#2F5F5E] text-xl"></i>
        <input type="text" id="searchInput" placeholder="Cari agenda kegiatan hari ini..."
               class="w-full bg-white border border-[#D6D1C4] rounded-3xl py-6 pl-16 pr-6 text-xl focus:border-[#2F5F5E] outline-none transition-all shadow-xl placeholder:text-gray-400">
    </div>

    <div class="flex items-center gap-4 mb-8">
        <div class="h-[2px] w-12 bg-[#D6D1C4]"></div>
        <h2 class="text-sm font-black uppercase tracking-[0.3em] text-[#2F5F5E]">Agenda Terkini</h2>
    </div>

    <div id="kegiatanContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($kegiatan as $item)
    <div class="kegiatan-card bg-white rounded-[2.5rem] overflow-hidden flex flex-col shadow-lg border border-[#D6D1C4]/30 hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
        <div class="p-8">
            <div class="flex items-center gap-5 mb-8">
                <div class="bg-[#2F5F5E] w-14 h-14 rounded-2xl flex items-center justify-center font-black text-2xl shadow-lg shrink-0 text-white">
                    {{ $loop->iteration }}
                </div>
                <h3 class="nama-kegiatan font-extrabold text-lg leading-tight uppercase tracking-tight text-[#2F5F5E]">
                    {{ $item->nama_kegiatan }}
                </h3>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-5 bg-[#F0EFE9] p-5 rounded-[1.5rem] border border-[#D6D1C4]/50">
                    <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center shadow-sm text-[#2F5F5E]">
                        <i class="fa-regular fa-clock text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Waktu</p>
                        <p class="text-sm font-bold text-[#2F5F5E]">{{ date('H:i', strtotime($item->waktu)) }} WIB</p>
                    </div>
                </div>

                <div class="flex items-center gap-5 bg-[#F0EFE9] p-5 rounded-[1.5rem] border border-[#D6D1C4]/50">
                    <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center shadow-sm text-[#2F5F5E]">
                        <i class="fa-solid fa-location-dot text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Lokasi</p>
                        <p class="text-sm font-bold text-[#2F5F5E] line-clamp-1">{{ $item->lokasi }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-8 pb-8 mt-auto">
            <a href="{{ url('/hadir/'.$item->id_kegiatan) }}" 
               class="bg-gradient-to-r from-[#2F5F5E] to-[#5B4636] hover:brightness-110 text-white text-center py-5 rounded-2xl font-black text-xs tracking-[0.2em] uppercase transition-all flex items-center justify-center gap-3 shadow-lg shadow-[#2F5F5E]/20">
                <i class="fa-solid fa-signature text-lg"></i> ISI DAFTAR HADIR
            </a>
        </div>
    </div>
    @empty
        <div class="col-span-full text-center py-20 opacity-50">
            <i class="fa-solid fa-calendar-xmark text-6xl mb-4 text-[#2F5F5E]"></i>
            <p class="font-bold text-[#2F5F5E]">Belum ada agenda lintas OPD untuk hari ini.</p>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let cards = document.querySelectorAll('.kegiatan-card');
        cards.forEach(card => {
            let title = card.querySelector('.nama-kegiatan').innerText.toLowerCase();
            card.style.display = title.includes(filter) ? "" : "none";
        });
    });
</script>
@endpush