<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ($getState() as $foto)
        <div class="max-w-xs mx-auto">
            <img 
                src="{{ asset('storage/' . $foto->path) }}" 
                class="w-full h-32 object-cover rounded-xl shadow-md"
                alt="Foto Dokumentasi"
            >
        </div>
    @endforeach
</div>