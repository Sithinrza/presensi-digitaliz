<?php
// File: resources/views/photo.blade.php
// Halaman Konfirmasi Presensi dengan Peta Lokasi
?>
<x-karyawan-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        crossorigin=""></script>

    <main class="p-4 space-y-6 text-center min-h-screen flex flex-col items-center">
        <h1 class="text-3xl font-bold text-green-600 mt-6">Presensi Berhasil!</h1>
        <p class="text-gray-700">Terima kasih, {{ $user->name }}. Data presensi Anda telah tersimpan.</p>

        <div class="w-full max-w-sm mx-auto p-4 bg-white rounded-xl shadow-lg">
            <h2 class="text-lg font-semibold mb-3 text-gray-800">Detail Presensi</h2>

            @php
                // Tentukan path foto (Utamakan foto CO jika ada, jika tidak, pakai CI)
                $fotoFile = $presensi->foto_co ?? $presensi->foto_ci;
                $photoPath = $fotoFile ? Storage::url($fotoFile) : 'https://placehold.co/400x300?text=Foto+Tidak+Ditemukan';

                // Ambil waktu yang relevan
                $waktuPresensi = $presensi->waktu_co ?? $presensi->waktu_ci;
            @endphp

            <img src="{{ $photoPath }}" alt="Foto Presensi" class="w-full h-auto rounded-lg shadow-md border-4 border-green-500/50 object-cover aspect-[4/3]">

            <p class="mt-4 text-sm font-semibold text-gray-800">
                Waktu: {{ \Carbon\Carbon::parse($waktuPresensi)->format('H:i:s') }}
                <span class="text-xs text-gray-500">({{ \Carbon\Carbon::parse($presensi->tanggal)->format('d M Y') }})</span>
            </p>
            <p class="text-sm font-medium text-gray-600">
                Status: {{ $presensi->status->name ?? 'N/A' }}
            </p>
        </div>

        @php
            // Gunakan koordinat CO jika ada, jika tidak, pakai CI
            $latitude = $presensi->latitude_co ?? $presensi->latitude_ci;
            $longitude = $presensi->longitude_co ?? $presensi->longitude_ci;
        @endphp

        <div class="w-full max-w-sm mx-auto p-4 bg-white rounded-xl shadow-lg">
            <h2 class="text-lg font-semibold mb-3 text-gray-800">Lokasi Terekam</h2>

            @if ($latitude != 0 && $longitude != 0)
                <div id="map" style="height: 250px; border-radius: 0.5rem;"></div>
                <p class="text-xs text-gray-500 mt-2">Lat: {{ $latitude }}, Long: {{ $longitude }}</p>
            @else
                <div class="bg-yellow-100 text-yellow-800 p-3 rounded-lg">
                    <p>⚠️ Lokasi tidak terekam dengan valid (koordinat 0,0 atau NULL).</p>
                </div>
            @endif
        </div>

        <a href="{{ route('karyawan.presensi.index') }}" class="bg-indigo-950 text-white font-semibold py-3 px-6 rounded-xl shadow-md transition hover:bg-indigo-800">
            Kembali ke Halaman Presensi
        </a>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lat = {{ $latitude }};
            const lng = {{ $longitude }};

            // Hanya inisialisasi peta jika koordinat valid
            if (lat != 0 && lng != 0) {
                try {
                    var map = L.map('map').setView([lat, lng], 17); // Zoom level 17 untuk detail

                    // Lapisan Peta Dasar (OpenStreetMap)
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);

                    // Marker di lokasi Presensi
                    L.marker([lat, lng])
                        .addTo(map)
                        .bindPopup("Lokasi Presensi Anda Terekam.")
                        .openPopup();

                     // Fix agar peta muncul jika dimuat di kontainer tersembunyi
                    setTimeout(function() {
                        map.invalidateSize();
                    }, 400);

                } catch (e) {
                    console.error("Leaflet initialization failed:", e);
                    // Jika gagal, tampilkan pesan fallback
                    document.getElementById('map').innerHTML = '<div class="text-red-500 p-4">Gagal memuat peta. Cek koneksi atau izin CDN.</div>';
                }
            }
        });
    </script>
</x-karyawan-layout>
