<x-karyawan-layout>
    {{-- Memuat Library JS/CSS untuk Leaflet Map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <div class="relative min-h-screen pb-16">

        {{-- Header Karyawan --}}
        <header class="bg-white p-4 pb-1 shadow-md sticky top-0 z-30">
            <div class="flex items-center space-x-3 text-gray-800">
                {{-- Tombol Kembali ke Halaman Presensi Utama --}}
                <a href="{{ route('karyawan.presensi.index') }}">
                    <button class="p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </a>
                <h2 class="text-xl font-bold">Detail Presensi</h2>
            </div>
        </header>

        <main class="p-4 relative z-20">
            <div class="bg-white p-6 rounded-2xl shadow-lg space-y-6">

                {{-- Header Status Presensi --}}
                <div class="border-b pb-4">
                    <h3 class="text-xl font-bold text-gray-800">Presensi Tanggal:</h3>
                    <p class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('D, d M Y') }}
                    </p>
                    @php
                        // Logika Status dari Controller Karyawan
                        $statusId = $presensi->status_presensi_id ?? 5;
                        $statusName = $presensi->status->name ?? 'Tidak Hadir';
                        $classes = [1 => ['bg' => 'bg-green-200', 'text' => 'text-green-800'], 2 => ['bg' => 'bg-yellow-200', 'text' => 'text-yellow-800'], 3 => ['bg' => 'bg-orange-200', 'text' => 'text-orange-800'], 4 => ['bg' => 'bg-purple-200', 'text' => 'text-purple-800'], 5 => ['bg' => 'bg-red-200', 'text' => 'text-red-800'], ];
                        $style = $classes[$statusId] ?? $classes[5];
                    @endphp
                    <span class="mt-2 inline-block text-sm font-bold px-3 py-1 rounded-full {{ $style['bg'] }} {{ $style['text'] }}">
                        Status Akhir: {{ $statusName }}
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
                                        {{-- Menggunakan $presensi->foto_ci --}}
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
                                        {{-- Menggunakan $presensi->foto_co --}}
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

                {{-- AREA PETA LOKASI (Dua Peta Terpisah) --}}
                <div class="space-y-6">
                    @php
                        // Ambil koordinat CI
                        $latCi = $presensi->latitude_ci;
                        $longCi = $presensi->longitude_ci;
                        $isCiLocationValid = ($latCi != 0 && $longCi != 0 && $latCi !== null);

                        // Ambil koordinat CO
                        $latCo = $presensi->latitude_co;
                        $longCo = $presensi->longitude_co;
                        $isCoLocationValid = ($latCo != 0 && $longCo != 0 && $latCo !== null && $presensi->waktu_co); // Hanya valid jika waktu CO ada

                        // Data Kantor (sesuaikan dengan Controller Karyawan Anda)
                        $officeLocation = [
                            'lat' => -3.3286345, //wls
                            'long' => 114.6074828, //wls
                            'radius' => 500, //wls

                            // 'lat' => -3.2289087, //gibs
                            // 'long' => 114.5962882, //gibs
                            // 'radius' => 500, //gibs
                        ];
                    @endphp

                    {{-- 1. PETA LOKASI CHECK-IN (CI) --}}
                    <div class="p-4 border rounded-lg shadow-md bg-gray-50">
                        <h4 class="text-lg font-bold mb-3 text-indigo-700 border-b pb-2">Peta Lokasi Check-In</h4>
                        @if ($isCiLocationValid)
                            <div id="map-ci" style="height: 250px; z-index: 10; border-radius: 0.5rem;"></div>
                            <p class="text-xs text-gray-500 mt-2">Koordinat CI: Lat {{ $latCi }}, Long {{ $longCi }}</p>
                        @else
                            <div class="bg-yellow-100 text-yellow-800 p-3 rounded-lg">
                                <p>⚠️ Lokasi Check-In tidak terekam.</p>
                            </div>
                        @endif
                    </div>

                    {{-- 2. PETA LOKASI CHECK-OUT (CO) --}}
                    <div class="p-4 border rounded-lg shadow-md bg-gray-50">
                        <h4 class="text-lg font-bold mb-3 text-indigo-700 border-b pb-2">Peta Lokasi Check-Out</h4>
                        @if ($isCoLocationValid)
                            <div id="map-co" style="height: 250px; z-index: 10; border-radius: 0.5rem;"></div>
                            <p class="text-xs text-gray-500 mt-2">Koordinat CO: Lat {{ $latCo }}, Long {{ $longCo }}</p>
                        @else
                            <div class="bg-yellow-100 text-yellow-800 p-3 rounded-lg">
                                <p>⚠️ Lokasi Check-Out belum terekam atau tidak valid.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- Script Peta --}}
    @if ($isCiLocationValid || $isCoLocationValid)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Data Kantor
                const OFFICE_LAT = {{ $officeLocation['lat'] }};
                const OFFICE_LONG = {{ $officeLocation['long'] }};
                const MAX_DISTANCE_M = {{ $officeLocation['radius'] }};

                function initMap(mapId, lat, lng, type) {
                    if (lat == null || lng == null) return;

                    try {
                        var map = L.map(mapId).setView([lat, lng], 17);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19, attribution: '© OpenStreetMap contributors'
                        }).addTo(map);

                        // Marker Kantor (Geofence Center)
                        L.marker([OFFICE_LAT, OFFICE_LONG]).addTo(map).bindPopup("Pusat Presensi Kantor");

                        // Lingkaran Geofence
                        L.circle([OFFICE_LAT, OFFICE_LONG], {
                            color: 'blue', fillColor: '#304FFE', fillOpacity: 0.1, radius: MAX_DISTANCE_M
                        }).addTo(map);

                        // Marker di lokasi Presensi Terekam (Marker user)
                        const markerColor = (type === 'ci') ? 'green' : 'red';

                        // Menggunakan marker bawaan Leaflet untuk kesederhanaan
                        L.marker([lat, lng])
                        .addTo(map)
                        .bindPopup(`Lokasi ${type.toUpperCase()} Terekam.`)
                        .openPopup();

                        setTimeout(function() {
                            map.invalidateSize();
                        }, 400);

                    } catch (e) {
                        console.error("Leaflet initialization failed for " + mapId + ":", e);
                    }
                }

                // Inisialisasi Peta Check-In
                @if ($isCiLocationValid)
                    initMap('map-ci', {{ $latCi }}, {{ $longCi }}, 'ci');
                @endif

                // Inisialisasi Peta Check-Out
                @if ($isCoLocationValid)
                    initMap('map-co', {{ $latCo }}, {{ $longCo }}, 'co');
                @endif
            });
        </script>
    @endif
</x-karyawan-layout>
