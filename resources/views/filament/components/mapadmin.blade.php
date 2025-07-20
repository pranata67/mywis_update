{{-- Hapus CDN jQuery jika tidak digunakan di tempat lain, karena Alpine.js sudah cukup --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div
    x-data="mapPicker({
        state: $wire.entangle('{{ $getStatePath() }}'),
        defaultLat: -7.4723,
        defaultLng: 112.4333
    })"
    x-init="initMap()"
    wire:ignore
    {{ $attributes->merge($getExtraAttributes())->class(['rounded-xl border border-gray-300 dark:border-gray-600']) }}
>
    <div x-ref="map" class="h-96 rounded-xl z-10"></div>
</div>

<script>
    function mapPicker(config) {
        return {
            state: config.state,
            map: null,
            marker: null,
            defaultLat: config.defaultLat,
            defaultLng: config.defaultLng,

            initMap() {
                // Beri sedikit waktu agar div map-nya siap dirender oleh browser
                setTimeout(() => {
                    let lat = this.defaultLat;
                    let lng = this.defaultLng;

                    // Jika state (koordinat) sudah ada, gunakan itu
                    if (this.state) {
                        const coords = this.state.split(',').map(s => parseFloat(s.trim()));
                        if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
                            lat = coords[0];
                            lng = coords[1];
                        }
                    }

                    this.map = L.map(this.$refs.map).setView([lat, lng], 13);

                    L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                        maxZoom: 20,
                        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                    }).addTo(this.map);

                    this.marker = L.marker([lat, lng], {
                        draggable: true
                    }).addTo(this.map);

                    // Event saat marker selesai digeser
                    this.marker.on('dragend', (event) => {
                        const position = this.marker.getLatLng();
                        // Update state Livewire secara langsung
                        this.state = `${position.lat}, ${position.lng}`;
                    });

                }, 50); // delay 50ms sudah cukup

                // Pantau perubahan pada state dari luar (misal: saat input text diubah)
                this.$watch('state', (newState) => {
                    const newCoords = newState.split(',').map(s => parseFloat(s.trim()));
                    if (newCoords.length === 2 && !isNaN(newCoords[0]) && !isNaN(newCoords[1])) {
                        const newLatLng = L.latLng(newCoords[0], newCoords[1]);
                        // Pindahkan view peta dan marker ke koordinat baru
                        this.map.setView(newLatLng, this.map.getZoom());
                        this.marker.setLatLng(newLatLng);
                    }
                });
            }
        }
    }
</script>