<div
    wire:ignore
    x-data="{
        map: null,
        marker: null,
        mounted: false,

        mount() {
            if (this.mounted) return
            this.mounted = true

            console.log('INIT MAP JALAN')

            const lat = $wire.get('data.latitude') ?? -7.1575
            const lng = $wire.get('data.longitude') ?? 113.4825

            this.map = L.map(this.$refs.map).setView([lat, lng], 13)

            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                { attribution: '&copy; OpenStreetMap contributors' }
            ).addTo(this.map)

            this.marker = L.marker([lat, lng]).addTo(this.map)

            this.map.on('click', (e) => {
                this.marker.setLatLng(e.latlng)
                $wire.set('data.latitude', e.latlng.lat)
                $wire.set('data.longitude', e.latlng.lng)
            })

            this.map.invalidateSize()
        }
    }"
    x-effect="mount()"
    class="w-full border rounded-lg"
    style="height:400px"
>
    <div x-ref="map" class="w-full h-full"></div>
</div>
