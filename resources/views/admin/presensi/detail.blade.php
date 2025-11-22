<x-admin-layout>
    <x-slot:title>
        Detail Presensi
    </x-slot:title>

    {{-- Memuat Library JS/CSS untuk Leaflet Map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>


    <div class="relative min-h-screen">
        {{-- Header Admin --}}
        <header class="bg-indigo-950 p-4 pb-16 shadow-lg">
            <div class="flex items-center space-x-3 text-white mb-4">
                <a href="{{ route('admin.presensi.index') }}">
                    <button class="p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </a>
                <h2 class="text-xl font-bold">Detail Presensi</h2>
            </div>
        </header>

        <main class="p-4 -mt-10 relative z-20">
            <div class="bg-white p-6 rounded-2xl shadow-lg space-y-6">

                {{-- Header Karyawan & Status --}}
                <div class="border-b pb-4">
                    {{-- Menggunakan nama_lengkap sesuai perbaikan Model --}}
                    <h3 class="text-2xl font-bold text-gray-800">{{ $presensi->karyawan->nama_lengkap ?? 'N/A' }}</h3>
                    <p class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('D, d M Y') }}
                    </p>
                    @php
                        $statusId = $presensi->status_presensi_id ?? 5;
                        $statusName = $presensi->status->name ?? 'Tidak Hadir';
                        $classes = [1 => ['bg' => 'bg-green-200', 'text' => 'text-green-800'], 2 => ['bg' => 'bg-yellow-200', 'text' => 'text-yellow-800'], 3 => ['bg' => 'bg-orange-200', 'text' => 'text-orange-800'], 4 => ['bg' => 'bg-purple-200', 'text' => 'text-purple-800'], 5 => ['bg' => 'bg-red-200', 'text' => 'text-red-800'], ];
                        $style = $classes[$statusId] ?? $classes[5];
                    @endphp
                    <span class="mt-2 inline-block text-sm font-bold px-3 py-1 rounded-full {{ $style['bg'] }} {{ $style['text'] }}">
                        Status: {{ $statusName }}
                    </span>
                </div>

                {{-- Kontainer Detail CI/CO --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- DETAIL CHECK-IN --}}
                    <div class="p-4 border rounded-lg shadow-md bg-gray-50">
                        <h4 class="text-lg font-bold mb-3 text-indigo-700 border-b pb-2">Check-In</h4>
                        <dl class="space-y-3 text-sm">
                            <div>
                                <dt class="font-medium text-gray-600">Waktu:</dt>
                                {{-- Menggunakan format H:i:s dan \Carbon\Carbon --}}
                                <dd class="text-gray-900 font-semibold">{{ $presensi->waktu_ci ? \Carbon\Carbon::parse($presensi->waktu_ci)->format('H:i:s') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-600">Koordinat (CI):</dt>
                                <dd class="text-gray-900">{{ $presensi->latitude_ci ?? 'N/A' }}, {{ $presensi->longitude_ci ?? 'N/A' }}</dd>
                            </div>
                            @if ($presensi->foto_ci)
                                <div>
                                    <dt class="font-medium text-gray-600">Foto Selfie:</dt>
                                    <dd class="mt-2">
                                        <img src="{{ Storage::url($presensi->foto_ci) }}" alt="Foto Check-In" class="w-full max-w-xs rounded-xl shadow-lg aspect-[3/4] object-cover">
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    {{-- DETAIL CHECK-OUT --}}
                    <div class="p-4 border rounded-lg shadow-md bg-gray-50">
                        <h4 class="text-lg font-bold mb-3 text-indigo-700 border-b pb-2">Check-Out</h4>
                        <dl class="space-y-3 text-sm">
                            <div>
                                <dt class="font-medium text-gray-600">Waktu:</dt>
                                <dd class="text-gray-900 font-semibold">{{ $presensi->waktu_co ? \Carbon\Carbon::parse($presensi->waktu_co)->format('H:i:s') : 'Belum Check-Out' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-600">Koordinat (CO):</dt>
                                <dd class="text-gray-900">{{ $presensi->latitude_co ?? 'N/A' }}, {{ $presensi->longitude_co ?? 'N/A' }}</dd>
                            </div>
                            @if ($presensi->foto_co)
                                <div>
                                    <dt class="font-medium text-gray-600">Foto Selfie:</dt>
                                    <dd class="mt-2">
                                        <img src="{{ Storage::url($presensi->foto_co) }}" alt="Foto Check-Out" class="w-full max-w-xs rounded-xl shadow-lg aspect-[3/4] object-cover">
                                    </dd>
                                </div>
                            @else
                                <div class="p-4 text-center bg-gray-100 rounded-md text-gray-500 mt-4">
                                    Belum ada data/foto Check-Out.
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Peta Lokasi (Menggabungkan logika dari template karyawan) --}}
                @php
                    // Logika: prioritaskan CO, jika tidak ada, pakai CI
                    $latitude = $presensi->latitude_co ?? $presensi->latitude_ci;
                    $longitude = $presensi->longitude_co ?? $presensi->longitude_ci;
                    $isLocationValid = ($latitude != 0 && $longitude != 0 && $latitude !== null && $longitude !== null);
                @endphp

                <div class="p-4 border rounded-lg shadow-md bg-gray-50">
                    <h4 class="text-lg font-bold mb-3 text-indigo-700 border-b pb-2">Lokasi Presensi Terekam</h4>

                    @if ($isLocationValid)
                        <div id="map" style="height: 250px; z-index: 10; border-radius: 0.5rem;"></div>
                        <p class="text-xs text-gray-500 mt-2">Lat: {{ $latitude }}, Long: {{ $longitude }}</p>
                    @else
                        <div class="bg-yellow-100 text-yellow-800 p-3 rounded-lg">
                            <p>⚠️ Lokasi tidak terekam dengan valid (koordinat 0,0 atau NULL).</p>
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    {{-- Script Peta --}}
    @if ($isLocationValid)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const lat = parseFloat("{{ $latitude }}");
                const lng = parseFloat("{{ $longitude }}");

                // Data Kantor untuk Geofence (dari Controller)
                const OFFICE_LAT = parseFloat("{{ $officeLocation['lat'] }}");
                const OFFICE_LONG = parseFloat("{{ $officeLocation['long'] }}");
                const MAX_DISTANCE_M = parseInt("{{ $officeLocation['radius'] }}");

                // Hanya inisialisasi peta jika koordinat valid
                if (lat != 0 && lng != 0) {
                    try {
                        var map = L.map('map').setView([lat, lng], 17); // Zoom level 17 untuk detail

                        // Lapisan Peta Dasar (OpenStreetMap)
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);

                        // Marker Kantor (Geofence Center)
                        L.marker([OFFICE_LAT, OFFICE_LONG]).addTo(map).bindPopup("Pusat Presensi Kantor");

                        // Lingkaran Geofence
                        L.circle([OFFICE_LAT, OFFICE_LONG], {
                            color: 'blue', fillColor: '#304FFE', fillOpacity: 0.1, radius: MAX_DISTANCE_M
                        }).addTo(map);

                        // Marker di lokasi Presensi Terekam
                        L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup("Lokasi Presensi Terekam.")
                            .openPopup();

                        // Fix agar peta muncul jika dimuat di kontainer tersembunyi
                        setTimeout(function() {
                            map.invalidateSize();
                        }, 400);

                    } catch (e) {
                        console.error("Leaflet initialization failed:", e);
                        // Fallback jika Leaflet gagal
                        document.getElementById('map').innerHTML = '<div class="text-red-500 p-4">Gagal memuat peta. Cek koneksi atau izin CDN.</div>';
                    }
                }
            });
        </script>
    @endif
</x-admin-layout>
