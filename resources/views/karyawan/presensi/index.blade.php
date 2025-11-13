<?php
// File: resources/views/webcam.blade.php
// Tampilan Presensi Karyawan dengan Geofencing & Peta Live Location
?>
<x-karyawan-layout>
    <!-- Memuat Library Webcam.js dan Leaflet CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        crossorigin=""/>

    <div class="relative min-h-screen pb-24">
        <main class="p-4 space-y-6">
            <form method="POST" action="{{ route('karyawan.presensi.store') }}" id="presensi-form">
                @csrf
                <!-- INPUT TERSEMBUNYI UNTUK DATA PENTING -->
                <input type="hidden" name="image" id="image-data">
                <input type="hidden" name="latitude" id="latitude-data">
                <input type="hidden" name="longitude" id="longitude-data">

                <section class="bg-white p-6 rounded-2xl shadow-lg text-center">
                    <!-- Info Waktu & Jadwal -->
                    <div class="bg-indigo-950 text-white p-5 rounded-xl mb-6 shadow-md">
                        <p class="text-sm text-indigo-300" id="current-date">Loading...</p>
                        <p class="text-4xl font-bold my-2" id="current-time">--:--</p>
                        <span class="text-xs bg-white/20 text-white px-3 py-1 rounded-full">Jadwal : 08:30 - 16:30</span>
                    </div>

                    <!-- PETA LEAFLET UNTUK VISUALISASI LOKASI SAAT INI -->
                    <div class="max-w-sm mx-auto mb-4">
                        <div id="live-map" style="height: 200px; border-radius: 0.5rem; display: none;"></div>
                    </div>

                    <!-- AREA STATUS LOKASI & GPS RETRY -->
                    <div class="flex items-center justify-center space-x-1 text-sm text-gray-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                        <span id="location-text">Mencari lokasi...</span>
                    </div>

                    <button type="button" id="retry-location-btn" class="hidden text-xs text-blue-600 hover:underline mb-4">
                        Coba Lagi Lokasi GPS
                    </button>
                    <!-- AKHIR AREA LOKASI -->

                    <!-- WRAPPER UNTUK KONTEN KAMERA/FOTO/PETA -->
                    <div class="max-w-sm mx-auto">

                        <!-- 1. AREA KAMERA / PLACEHOLDER -->
                        <div id="camera-area" class="mb-4 flex flex-col justify-center items-center">

                            <!-- 1a. PLACEHOLDER AWAL (Tombol Pemicu Kamera) -->
                            <button type="button" id="activate-camera-btn" class="mb-4 text-gray-500 hover:text-indigo-950 transition duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" viewBox="0 0 24 24" fill="currentColor"><path d="M5 7h1a2 2 0 0 0 2-2a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2m7 7a3 3 0 1 0 0-6a3 3 0 0 0 0 6"/></svg>
                                <p class="text-xs mt-1">Ambil foto selfie untuk check in</p>
                            </button>

                            <!-- 1b. LIVE CAMERA FEED (Awalnya tersembunyi) -->
                            <div id="my_camera" style="display: none;" class="w-full mx-auto bg-gray-200 rounded-xl overflow-hidden shadow-inner aspect-[4/3]"></div>

                            <!-- 1c. PHOTO RESULT (Awalnya tersembunyi) -->
                            <div id="results" style="display: none;" class="w-full mx-auto rounded-xl overflow-hidden shadow-lg aspect-[4/3]"></div>

                            <!-- 1d. TOMBOL CAPTURE/RETAKE (Hanya muncul saat feed/pratinjau tampil) -->
                            <button type="button" id="take-photo-btn" class="hidden bg-indigo-950 text-white font-semibold mt-4 py-3 px-4 rounded-xl shadow-md hover:bg-indigo-800 transition">
                                Ambil Foto
                            </button>

                            <button type="button" id="retake-photo-btn" class="hidden text-sm text-gray-500 mt-3 hover:text-indigo-950 transition duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.5l-2.48-2.48M15 10l-1.5-1.5M9 7l-1.5-1.5M4.82 2.18l-1.64 1.64M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2zM9 13a3 3 0 1 0 6 0a3 3 0 0 0-6 0"/></svg>
                                Foto Ulang
                            </button>

                        </div>
                    </div>
                    <!-- AKHIR WRAPPER KAMERA -->

                    <!-- TOMBOL SUBMIT PRESENSI -->
                    <button type="submit" id="submit-presensi-btn" disabled class="checkin-button w-full text-white font-semibold py-3 px-4 rounded-xl shadow-lg transition duration-200 opacity-50 cursor-not-allowed mb-3 bg-gray-400">
                        Check-In
                    </button>

                </section>
            </form>

          <section class="bg-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Riwayat Presensi Terakhir</h2>
                    <a href="#" class="flex items-center space-x-1 text-xs text-blue-600 font-medium hover:underline">
                        <span>Detail Riwayat</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
                <!-- RIWAYAT DINAMIS -->
                <div class="space-y-3">
                    @forelse ($history as $presensi)
                        @php
                            // --- LOGIKA STATUS UTAMA & WARNA ---
                            $statusId = $presensi->status_presensi_id;
                            $ringColor = 'border-gray-200 bg-gray-50'; // Default

                            // Logika untuk menentukan warna berdasarkan ID Status
                            if ($statusId == 1) {
                                $ringColor = 'border-green-400 bg-green-50/50';
                            } elseif ($statusId == 2) {
                                $ringColor = 'border-yellow-400 bg-yellow-50/50';
                            } elseif ($statusId == 3) {
                                $ringColor = 'border-orange-400 bg-orange-50/50';
                            } elseif ($statusId == 4) {
                                $ringColor = 'border-purple-400 bg-purple-50/50';
                            } elseif ($statusId == 5) {
                                $ringColor = 'border-red-400 bg-red-50/50';
                            }

                            // Penyesuaian: Jika status hari ini sedang berjalan (CO belum ada)
                            $isTodayActive = (\Carbon\Carbon::parse($presensi->tanggal)->isToday() && !$presensi->waktu_co);
                            if ($isTodayActive) {
                                $ringColor = 'border-indigo-600 bg-indigo-50/50';
                            }

                            // --- FUNGSI PEMBANTU STATUS (Digunakan untuk Dual Status) ---
                            $getPill = function($id, $name) {
                                $classes = [
                                    1 => ['bg' => 'bg-green-200', 'text' => 'text-green-800'],
                                    2 => ['bg' => 'bg-yellow-200', 'text' => 'text-yellow-800'],
                                    3 => ['bg' => 'bg-orange-200', 'text' => 'text-orange-800'],
                                    4 => ['bg' => 'bg-purple-200', 'text' => 'text-purple-800'],
                                    5 => ['bg' => 'bg-red-200', 'text' => 'text-red-800'],
                                ];
                                $pill = $classes[$id] ?? ['bg' => 'bg-gray-200', 'text' => 'text-gray-800'];
                                // Menggunakan style yang lebih kecil dan kompak
                                return "<span class='text-xs font-semibold px-2 py-0.5 rounded-full {$pill['bg']} {$pill['text']}'>{$name}</span>";
                            };

                            // Logika Dual Status
                            $isCiTerlambat = ($statusId == 2 || $statusId == 3);
                            $isCoTerlambat = ($statusId == 3);
                            $isFinalStatus = ($statusId >= 4 || $statusId == 1);

                        @endphp

                        <div class="p-4 rounded-xl border {{ $ringColor }}">
                            <div class="flex items-start justify-between mb-2">

                                <!-- KIRI: NAMA HARI DAN DUAL STATUS PILL -->
                                <div class="text-left flex-grow">
                                    <h3 class="font-semibold text-gray-800 text-sm mb-1">
                                        {{ \Carbon\Carbon::parse($presensi->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                    </h3>

                                    <!-- CONTAINER STATUS GANDA (Side-by-Side) -->
                                    <div class="flex items-center space-x-2 mt-1">

                                        @if ($isCiTerlambat)
                                            <!-- Status 2: Terlambat Check-In -->
                                            {!! $getPill(2, 'Terlambat Check-In') !!}
                                        @endif

                                        @if ($isCoTerlambat)
                                            <!-- Status 3: Terlambat Check-Out -->
                                            {!! $getPill(3, 'Terlambat Check-Out') !!}
                                        @endif

                                        @if ($isFinalStatus)
                                            <!-- Status 1 (Tepat Waktu) atau Status 4/5 (Lupa/Tidak Hadir) -->
                                            @if ($statusId == 1)
                                                 {!! $getPill(1, 'Tepat Waktu') !!}
                                            @elseif ($statusId >= 4)
                                                 {!! $getPill($statusId, $presensi->status->name ?? 'Status') !!}
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <!-- KANAN: WAKTU CI/CO (GRID ASLI) -->
                                <div class="grid grid-cols-2 gap-3 mt-0 flex-shrink-0">
                                    <!-- CI Box -->
                                    <div class="p-2 rounded-lg bg-white shadow-sm border border-gray-100">
                                        <p class="text-xs text-gray-500">Check-In</p>
                                        <p class="text-sm font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($presensi->waktu_ci)->format('H:i') }}
                                        </p>
                                    </div>

                                    <!-- CO Box -->
                                    @if ($presensi->waktu_co)
                                    <div class="p-2 rounded-lg bg-white shadow-sm border border-gray-100">
                                        <p class="text-xs text-gray-500">Check-Out</p>
                                        <p class="text-sm font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($presensi->waktu_co)->format('H:i') }}
                                        </p>
                                    </div>
                                    @else
                                    <div class="p-2 rounded-lg bg-white shadow-sm border border-gray-100 flex items-center justify-center">
                                        <p class="text-xs font-medium text-gray-400">Belum CO</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-5 text-gray-500 bg-gray-50 rounded-lg">
                            Belum ada riwayat presensi yang tercatat.
                        </div>
                    @endforelse
                </div>
            </section>
        </main>
    </div>


    <!-- SCRIPT UTAMA UNTUK KAMERA DAN LOKASI -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- VARIABEL GEOFENCE (HARUS DIGANTI) ---
            // CATATAN KRITIS: GANTI NILAI INI DENGAN KOORDINAT KANTOR YANG SEBENARNYA!
            const OFFICE_LAT = -3.2289087; // Contoh: Latitude Kantor
            const OFFICE_LONG = 114.5962882; // Contoh: Longitude Kantor
            const MAX_DISTANCE_M = 500; // Radius toleransi ditingkatkan menjadi 100m

            // ------------------------------------------

            // --- 1. SETUP ELEMENT DOM ---
            const activateCameraBtn = document.getElementById('activate-camera-btn');
            const cameraFeedDiv = document.getElementById('my_camera');
            const photoResultDiv = document.getElementById('results');
            const takePhotoButton = document.getElementById('take-photo-btn');
            const retakePhotoBtn = document.getElementById('retake-photo-btn');
            const submitButton = document.getElementById('submit-presensi-btn');
            const retryLocationBtn = document.getElementById('retry-location-btn');

            const imageDataInput = document.getElementById('image-data');
            const locationText = document.getElementById('location-text');
            const latitudeInput = document.getElementById('latitude-data');
            const longitudeInput = document.getElementById('longitude-data');
            const mapDiv = document.getElementById('live-map');

            let isPhotoTaken = false;
            let isInsideGeofence = false;
            let watchID = null; // ID untuk menghentikan watchPosition
            let map;
            let userMarker;
            let geofenceCircle;

            // --- FUNGSI UTILITY: HITUNG JARAK (HAVERSINE) ---
            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371e3; // Radius Bumi dalam meter
                const φ1 = lat1 * Math.PI / 180;
                const φ2 = lat2 * Math.PI / 180;
                const Δφ = (lat2 - lat1) * Math.PI / 180;
                const Δλ = (lon2 - lon1) * Math.PI / 180;

                const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                        Math.cos(φ1) * Math.cos(φ2) *
                        Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                return R * c; // Jarak dalam meter
            }

            // --- 2. FUNGSI LEAFLET (MAPS) ---
            function initMap(lat, lng) {
                if (map) {
                    map.remove(); // Hapus peta lama jika ada
                }
                mapDiv.style.display = 'block'; // Tampilkan elemen peta

                // Inisialisasi peta dan zoom ke lokasi karyawan
                map = L.map('live-map').setView([lat, lng], 17); // Zoom level 17

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Marker Kantor (Statik)
                L.marker([OFFICE_LAT, OFFICE_LONG])
                    .addTo(map)
                    .bindPopup("Pusat Presensi Kantor");

                // Lingkaran Geofence (Statik)
                geofenceCircle = L.circle([OFFICE_LAT, OFFICE_LONG], {
                    color: 'green',
                    fillColor: '#00cc00',
                    fillOpacity: 0.2,
                    radius: MAX_DISTANCE_M
                }).addTo(map);

                // Peta perlu di-invalidate size agar tampil sempurna
                setTimeout(() => map.invalidateSize(), 300);
            }

            function updateMap(currentLat, currentLng, inside) {
                // Inisialisasi jika belum
                if (!map) initMap(currentLat, currentLng);
                map.setView([currentLat, currentLng], 17);

                // Hapus marker lama
                if (userMarker) {
                    map.removeLayer(userMarker);
                }

                // Tambahkan marker Karyawan (Dinamis - Live Location)
                const markerColor = inside ? '#007BFF' : '#FF0000'; // Biru untuk dalam, Merah untuk luar
                userMarker = L.circleMarker([currentLat, currentLng], {
                    radius: 8,
                    color: markerColor,
                    fillColor: markerColor,
                    fillOpacity: 1
                }).addTo(map);

                userMarker.bringToFront();

                const statusText = inside ? 'Anda di dalam area presensi.' : 'Anda di luar area presensi yang diizinkan!';
                userMarker.bindPopup(`Posisi Anda: <br> ${statusText}`).openPopup();

                // Perintah penting untuk memastikan peta terlihat benar
                setTimeout(() => map.invalidateSize(), 300);
            }


            // --- 3. FUNGSI GEOFENCE & GPS (WATCH POSITION) ---
            function checkGeofence(currentLat, currentLng) {
                const distance = calculateDistance(currentLat, currentLng, OFFICE_LAT, OFFICE_LONG);

                if (distance <= MAX_DISTANCE_M) {
                    isInsideGeofence = true;
                    locationText.textContent = `Dalam Jangkauan Presensi (${distance.toFixed(1)}m)`;
                    locationText.classList.remove('text-red-500');
                    locationText.classList.add('text-green-600');
                } else {
                    isInsideGeofence = false;
                    locationText.textContent = `DI LUAR JANGKAUAN PRESENSI (${distance.toFixed(1)}m)`;
                    locationText.classList.remove('text-green-600');
                    locationText.classList.add('text-red-500');
                }

                // Update marker di peta
                updateMap(currentLat, currentLng, isInsideGeofence);

                // Aktifkan/Nonaktifkan tombol submit
                updateSubmitButtonStatus();
            }

            function updateSubmitButtonStatus() {
                // Tombol submit hanya aktif jika foto sudah diambil DAN berada di dalam Geofence
                if (isPhotoTaken && isInsideGeofence) {
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
                    submitButton.removeAttribute('disabled');
                    submitButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                    submitButton.textContent = 'Check-In Sekarang';
                } else {
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
                    submitButton.setAttribute('disabled', 'true');

                    if(isPhotoTaken) {
                        submitButton.textContent = 'Check-In Ditolak (Di Luar Area)';
                    } else {
                        submitButton.textContent = 'Check-In';
                    }
                }
            }


            function startWatchingLocation() {
                locationText.textContent = "Mencari lokasi...";
                retryLocationBtn.style.display = 'none';

                if (navigator.geolocation) {
                    // Cek jika sudah ada pemantauan, hentikan dulu
                    if (watchID !== null) {
                        navigator.geolocation.clearWatch(watchID);
                    }

                    // Meningkatkan timeout untuk akurasi yang lebih baik
                    watchID = navigator.geolocation.watchPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const long = position.coords.longitude;

                            latitudeInput.value = lat.toFixed(6);
                            longitudeInput.value = long.toFixed(6);

                            // Setiap ada perubahan posisi, peta dan Geofence diupdate
                            checkGeofence(lat, long);

                        },
                        (error) => {
                            locationText.textContent = "Gagal mendapatkan lokasi. GPS Diperlukan.";
                            locationText.classList.remove('text-green-600');
                            locationText.classList.add('text-red-500');

                            // Sembunyikan peta jika gagal
                            mapDiv.style.display = 'none';

                            // Tampilkan tombol retry dan hentikan pemantauan
                            retryLocationBtn.style.display = 'block';
                            if (watchID !== null) navigator.geolocation.clearWatch(watchID);
                            isInsideGeofence = false;
                            updateSubmitButtonStatus();
                            console.error("Geolocation Error:", error);
                        },
                        { enableHighAccuracy: true, timeout: 20000, maximumAge: 0 }
                    );
                } else {
                    locationText.textContent = "Geolocation tidak didukung oleh browser ini.";
                    mapDiv.style.display = 'none';
                    isInsideGeofence = false;
                    updateSubmitButtonStatus();
                }
            }

            // Mulai pemantauan lokasi saat halaman dimuat
            startWatchingLocation();
            retryLocationBtn.addEventListener('click', startWatchingLocation); // Event untuk tombol retry

            // --- 4. EVENT LISTENER: AKTIVASI KAMERA (Awal) ---
            activateCameraBtn.addEventListener('click', startCameraSession);

            // FUNGSI INTI UNTUK MEMULAI/MENGULANG KAMERA
            function startCameraSession() {
                // 1. Sembunyikan semua hasil/placeholder
                photoResultDiv.style.display = 'none';
                retakePhotoBtn.style.display = 'none';
                activateCameraBtn.style.display = 'none';

                // 2. Tampilkan feed kamera
                cameraFeedDiv.style.display = 'block';
                Webcam.attach( '#my_camera' );

                // 3. Tampilkan tombol ambil foto
                takePhotoButton.style.display = 'block';
            }


            // --- 5. EVENT LISTENER: AMBIL FOTO ---
            takePhotoButton.addEventListener('click', function() {
                Webcam.snap( function(data_uri) {
                    // 1. Simpan data URI ke input tersembunyi
                    imageDataInput.value = data_uri;

                    // 2. Sembunyikan feed kamera dan tombol ambil foto
                    Webcam.reset();
                    cameraFeedDiv.style.display = 'none';
                    takePhotoButton.style.display = 'none';

                    // 3. Tampilkan pratinjau hasil foto dan tombol retake
                    photoResultDiv.style.display = 'block';
                    photoResultDiv.innerHTML = '<img src="' + data_uri + '" class="w-full h-full object-cover rounded-xl shadow-lg"/>';
                    retakePhotoBtn.style.display = 'block';

                    // 4. Update status dan tombol submit
                    isPhotoTaken = true;
                    updateSubmitButtonStatus(); // Cek status submit lagi
                } );
            });

            // --- 6. EVENT LISTENER: FOTO ULANG (RETAKE) ---
            retakePhotoBtn.addEventListener('click', startCameraSession);

            // --- Logika Waktu dan Tanggal (Opsional) ---
            const updateTimeAndDate = () => {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                document.getElementById('current-time').textContent = timeString;
                document.getElementById('current-date').textContent = dateString;
            };
            updateTimeAndDate();
            setInterval(updateTimeAndDate, 1000); // Perbarui setiap detik

            // Konfigurasi Webcam
            Webcam.set({
                width: 320,
                height: 240,
                image_format: 'png',
                png_quality: 90,
                flip_horiz: true
            });

        });
    </script>
</x-karyawan-layout>
