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
        <div class="kegiatan-card glass-card rounded-[3rem] overflow-hidden flex flex-col justify-between hover:border-[#2F5F5E] transition-all duration-500 hover:-translate-y-2 shadow-xl">
            <div class="p-10">
                <div class="flex items-start gap-6">
                    <div class="bg-gradient-to-tr from-[#2F5F5E] to-[#3E7A78] w-16 h-16 rounded-2xl flex items-center justify-center font-black text-2xl shadow-lg shrink-0 text-white">
                        {{ $loop->iteration }}
                    </div>
                    <div class="flex-1">
                        <h3 class="nama-kegiatan font-extrabold text-xl mb-6 leading-tight uppercase tracking-tight italic text-[#2F5F5E]">
                            {{ $item->nama_kegiatan }}
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 bg-[#F0EFE9] p-3 rounded-xl">
                                <i class="fa-regular fa-clock text-[#2F5F5E] text-lg"></i>
                                <div>
                                    <p class="text-[10px] text-gray-500 uppercase font-bold">Waktu</p>
                                    <p class="text-sm font-semibold text-gray-700">{{ date('H:i', strtotime($item->waktu)) }} WIB</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 bg-[#F0EFE9] p-3 rounded-xl">
                                <i class="fa-solid fa-location-dot text-[#2F5F5E] text-lg"></i>
                                <div>
                                    <p class="text-[10px] text-gray-500 uppercase font-bold">Lokasi</p>
                                    <p class="text-sm font-semibold text-gray-700">{{ $item->lokasi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ url('/hadir/'.$item->id_kegiatan) }}" class="bg-gradient-to-r from-[#2F5F5E] to-[#5B4636] hover:from-[#3A6F6E] hover:to-[#6A5242] text-center py-6 font-black text-sm tracking-[0.2em] uppercase transition-all flex items-center justify-center gap-3 text-white">
                ISI DAFTAR HADIR <i class="fa-solid fa-signature text-xl"></i>
            </a>
        </div>
        @empty
            <div class="col-span-full text-center py-20 opacity-50">
                <i class="fa-solid fa-calendar-xmark text-6xl mb-4"></i>
                <p class="font-bold">Belum ada agenda lintas OPD untuk hari ini.</p>
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