<x-karyawan-layout>
    {{-- Memuat Library JS/CSS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <style>
        /* KRITIS: CSS untuk menimpa dimensi internal Webcam.js */
        #my_camera video, #my_camera canvas {
            width: 100% !important;
            height: 100% !important;
            max-width: 100%;

            object-fit: cover;
            border-radius: 1rem;
            display: block !important;
        }


        #results {
           min-height: 100%;
            background-color: #ffffff;

            padding: 10px;
        }

        #results img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
            border-radius: 1rem;

            margin: 0 auto !important;
            display: block !important;

        }


    </style>

    <div class="relative min-h-screen pb-24">


        <main class="p-4 space-y-6">

            {{-- AREA PESAN FLASH --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </span>
                </div>
            @endif

            <form method="POST" action="{{ route('karyawan.presensi.store') }}" id="presensi-form">
                @csrf
                <input type="hidden" name="image" id="image-data">
                <input type="hidden" name="latitude" id="latitude-data">
                <input type="hidden" name="longitude" id="longitude-data">

                <section class="bg-white p-6 rounded-2xl shadow-lg text-center">
                    {{-- Info Waktu & Jadwal DINAMIS --}}
                    <div class="bg-indigo-950 text-white p-5 rounded-xl mb-6 shadow-md">
                        <p class="text-sm text-indigo-300" id="current-date">Loading...</p>
                        <p class="text-4xl font-bold my-2" id="current-time">--:--</p>

                        @if ($isWorkingDay)
                            <span class="text-xs bg-white/20 text-white px-3 py-1 rounded-full">
                                Jadwal : {{ \Carbon\Carbon::parse($shiftStart)->format('H:i') }} - {{ \Carbon\Carbon::parse($shiftEnd)->format('H:i') }}
                            </span>
                        @else
                            <span class="text-xs bg-red-500 text-white px-3 py-1 rounded-full">
                                HARI LIBUR / TIDAK ADA JADWAL KERJA
                            </span>
                        @endif
                    </div>

                    {{-- PESAN JIKA SUDAH CHECK IN/OUT --}}
                    @if (isset($assumptionError) && $assumptionError)
                        @php
                            $errorClass = (Str::contains($assumptionError, 'Tidak Hadir')) ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700';
                        @endphp
                        <div class="p-4 {{ $errorClass }} rounded-lg font-semibold mb-4">
                             {{ $assumptionError }}
                            <p class="text-xs font-normal mt-1">Anda tidak dapat Check-In/Check-Out lagi hari ini.</p>
                        </div>
                    @endif

                    {{-- PESAN JIKA SUDAH CHECK IN/OUT (Database Status) --}}
                    @if ($isCoDone && !isset($assumptionError))
                        <div class="p-4 bg-green-100 text-green-700 rounded-lg font-semibold mb-4">
                            ✅ Anda sudah Check-In dan Check-Out hari ini.
                        </div>

                    @elseif ($isCiDone && !$isCoDone)
                        <div class="p-4 bg-yellow-100 text-yellow-700 rounded-lg font-semibold mb-4">
                            ⏳ Anda sudah Check-In pukul {{ \Carbon\Carbon::parse($presensiHariIni->waktu_ci)->format('H:i') }}. Lakukan Check-Out setelah {{ \Carbon\Carbon::parse($shiftEnd)->format('H:i') }}.
                        </div>
                    @endif


                    {{-- PETA LEAFLET --}}
                    <div class="max-w-sm mx-auto mb-4">
                        <div id="live-map" style="height: 200px; z-index: 10; border-radius: 0.5rem; display: none;"></div>
                    </div>

                    {{-- AREA STATUS LOKASI & GPS RETRY --}}
                    <div class="flex items-center justify-center space-x-1 text-sm text-gray-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                        <span id="location-text">Mencari lokasi...</span>
                    </div>

                    <button type="button" id="retry-location-btn" class="hidden text-xs text-blue-600 hover:underline mb-4">
                        Coba Lagi Lokasi GPS
                    </button>
                    <div class="max-w-sm mx-auto">
                        <div id="camera-area" class="mb-4 flex flex-col justify-center items-center">

                            @if (!$isCoDone && $isWorkingDay)
                                <button type="button" id="activate-camera-btn" class="mb-4 text-gray-500 hover:text-indigo-950 transition duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" viewBox="0 0 24 24" fill="currentColor"><path d="M5 7h1a2 2 0 0 0 2-2a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2m7 7a3 3 0 1 0 0-6a3 3 0 0 0 0 6"/></svg>
                                    <p class="text-xs mt-1">Ambil foto selfie untuk {{ $isCiDone ? 'Check Out' : 'Check In' }}</p>
                                </button>
                            @else
                                <div class="mb-4 text-gray-500 p-4 border border-dashed rounded-lg">
                                    <p class="text-sm">Tidak perlu presensi saat ini.</p>
                                </div>
                            @endif

                                                        {{-- Container Kamera Live Feed --}}
                            <div id="my_camera" style="display: none;"
                                class="w-full mx-auto bg-gray-200 rounded-xl overflow-hidden shadow-inner aspect-[3/4] max-w-md"></div>

                            {{-- Container Hasil Foto Jepretan --}}
                            <div id="results" style="display: none;"
                                class="w-full mx-auto rounded-xl overflow-hidden shadow-lg aspect-[3/4] max-w-md"></div>

                            <button type="button" id="take-photo-btn" class="hidden bg-indigo-950 text-white font-semibold mt-4 py-3 px-4 rounded-xl shadow-md hover:bg-indigo-800 transition">
                                Ambil Foto
                            </button>

                            <button type="button" id="retake-photo-btn" class="hidden text-sm text-gray-500 mt-3 hover:text-indigo-950 transition duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.5l-2.48-2.48M15 10l-1.5-1.5M9 7l-1.5-1.5M4.82 2.18l-1.64 1.64M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2zM9 13a3 3 0 1 0 6 0a3 3 0 0 0-6 0"/></svg>
                                Foto Ulang
                            </button>
                        </div>
                    </div>
                    @if (!$isCoDone && $isWorkingDay)
                        <button type="submit" id="submit-presensi-btn" disabled class="checkin-button w-full text-white font-semibold py-3 px-4 rounded-xl shadow-lg transition duration-200 opacity-50 cursor-not-allowed mb-3 bg-gray-400">
                            {{ $isCiDone ? 'Check-Out' : 'Check-In' }}
                        </button>
                    @else
                        <button type="button" disabled class="w-full text-white font-semibold py-3 px-4 rounded-xl shadow-lg mb-3 bg-gray-300 cursor-not-allowed">
                            Selesai
                        </button>
                    @endif

                </section>
            </form>

            {{-- RIWAYAT PRESENSI --}}
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
                <div class="space-y-3">
                    @forelse ($history as $presensi)
                        @php
                            $statusId = $presensi->status_presensi_id;
                            $ringColor = 'border-gray-200 bg-gray-50';
                            if ($statusId == 1) { $ringColor = 'border-green-400 bg-green-50/50'; }
                            if ($statusId == 2) { $ringColor = 'border-yellow-400 bg-yellow-50/50'; }
                            if ($statusId == 3) { $ringColor = 'border-orange-400 bg-orange-50/50'; }
                            if ($statusId == 4) { $ringColor = 'border-purple-400 bg-purple-50/50'; }
                            if ($statusId == 5) { $ringColor = 'border-red-400 bg-red-50/50'; }

                            $isTodayActive = (\Carbon\Carbon::parse($presensi->tanggal)->isToday() && !$presensi->waktu_co);
                            if ($isTodayActive) { $ringColor = 'border-indigo-600 bg-indigo-50/50'; }

                            $getPill = function($id, $name) {
                                $classes = [
                                    1 => ['bg' => 'bg-green-200', 'text' => 'text-green-800'],
                                    2 => ['bg' => 'bg-yellow-200', 'text' => 'text-yellow-800'],
                                    3 => ['bg' => 'bg-orange-200', 'text' => 'text-orange-800'],
                                    4 => ['bg' => 'bg-purple-200', 'text' => 'text-purple-800'],
                                    5 => ['bg' => 'bg-red-200', 'text' => 'text-red-800'],
                                ];
                                $pill = $classes[$id] ?? ['bg' => 'bg-gray-200', 'text' => 'text-gray-800'];
                                return "<span class='text-xs font-semibold px-2 py-0.5 rounded-full {$pill['bg']} {$pill['text']}'>{$name}</span>";
                            };

                            $isCiTerlambat = ($statusId == 2 || $statusId == 3);
                            $isCoTerlambat = ($statusId == 3);
                            $isFinalStatus = ($statusId >= 4 || $statusId == 1);
                        @endphp

                        <div class="p-4 rounded-xl border {{ $ringColor }}">
                            <div class="flex items-start justify-between mb-2">

                                <div class="text-left flex-grow">
                                    <h3 class="font-semibold text-gray-800 text-sm mb-1">
                                        {{ \Carbon\Carbon::parse($presensi->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                    </h3>

                                    <div class="flex items-center space-x-2 mt-1 flex-wrap">

                                        @if ($statusId == 3)
                                            {{-- ID 3: Terlambat Check-Out (Implies CI juga sudah Terlambat/Dianggap Terlambat) --}}
                                            {!! $getPill(2, 'Terlambat Check-In') !!}
                                            {!! $getPill(3, 'Terlambat Check-Out') !!}

                                        @elseif ($statusId == 2)
                                            {{-- ID 2: Terlambat Check-In (Status Akhir jika CO Tepat Waktu) --}}
                                            {!! $getPill(2, 'Terlambat Check-In') !!}

                                        @elseif ($statusId == 1)
                                            {{-- ID 1: Tepat Waktu (CI dan CO Tepat Waktu) --}}
                                            {!! $getPill(1, 'Tepat Waktu') !!}

                                        @elseif ($statusId == 4)
                                            {{-- ID 4: Lupa Check-Out (Tambahkan Terlambat Check-In jika waktu CI menunjukkan keterlambatan) --}}

                                            {{-- Asumsi: Jika status akhir Lupa Check-Out (ID 4),
                                                dan Check-In-nya memang terlambat (seperti kasus 20:59), tampilkan kedua pill. --}}

                                            {{-- **KRITIS: Tampilkan Pill Terlambat Check-In** --}}
                                            {!! $getPill(2, 'Terlambat Check-In') !!}

                                            {{-- **KRITIS: Tampilkan Pill Lupa Check-Out** --}}
                                            {!! $getPill(4, 'Lupa Check-Out') !!}

                                        @elseif ($statusId == 5)
                                            {{-- ID 5: Tidak Hadir --}}
                                            {!! $getPill(5, 'Tidak Hadir') !!}
                                        @endif

                                        {{-- Anda mungkin ingin menambahkan logika untuk status default atau belum lengkap di sini --}}

                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 mt-0 flex-shrink-0">
                                    {{-- CI Box --}}
                                    <div class="p-2 rounded-lg bg-white shadow-sm border border-gray-100">
                                        <p class="text-xs text-gray-500">Check-In</p>
                                        <p class="text-sm font-bold text-gray-800">
                                            {{ $presensi->waktu_ci ? \Carbon\Carbon::parse($presensi->waktu_ci)->format('H:i') : '--' }}
                                        </p>
                                    </div>

                                    {{-- CO Box --}}
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- VARIABEL GEOFENCE ---
           // const OFFICE_LAT = -3.2289087; GIBS
            const OFFICE_LAT = -3.2759928;
           // const OFFICE_LONG = 114.5962882; GIBS
            const OFFICE_LONG = 114.5969432;
            const MAX_DISTANCE_M = 500;

            const isCiDone = @json($isCiDone);
            const isCoDone = @json($isCoDone);
            const isWorkingDay = @json($isWorkingDay);

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

            const submitButtonExists = !!submitButton;

            let isPhotoTaken = false;
            let isInsideGeofence = false;
            let watchID = null;
            let map;
            let userMarker;
            let geofenceCircle;

            // --- FUNGSI UTILITY: HITUNG JARAK (HAVERSINE) ---
            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371e3;
                const φ1 = lat1 * Math.PI / 180;
                const φ2 = lat2 * Math.PI / 180;
                const Δφ = (lat2 - lat1) * Math.PI / 180;
                const Δλ = (lon2 - lon1) * Math.PI / 180;

                const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                            Math.cos(φ1) * Math.cos(φ2) *
                            Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                return R * c;
            }

            // --- FUNGSI LEAFLET (MAPS) --- (Lengkap)
            function initMap(lat, lng) {
                if (map) { map.remove(); }
                mapDiv.style.display = 'block';
                map = L.map('live-map').setView([lat, lng], 17);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19, attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                L.marker([OFFICE_LAT, OFFICE_LONG]).addTo(map).bindPopup("Pusat Presensi Kantor");
                geofenceCircle = L.circle([OFFICE_LAT, OFFICE_LONG], {
                    color: 'green', fillColor: '#00cc00', fillOpacity: 0.2, radius: MAX_DISTANCE_M
                }).addTo(map);
                setTimeout(() => map.invalidateSize(), 300);
            }

            function updateMap(currentLat, currentLng, inside) {
                if (!map) initMap(currentLat, currentLng);
                map.setView([currentLat, currentLng], 17);
                if (userMarker) { map.removeLayer(userMarker); }
                const markerColor = inside ? '#007BFF' : '#FF0000';
                userMarker = L.circleMarker([currentLat, currentLng], {
                    radius: 8, color: markerColor, fillColor: markerColor, fillOpacity: 1
                }).addTo(map);
                userMarker.bringToFront();
                const statusText = inside ? 'Anda di dalam area presensi.' : 'Anda di luar area presensi yang diizinkan!';
                userMarker.bindPopup(`Posisi Anda: <br> ${statusText}`).openPopup();
                setTimeout(() => map.invalidateSize(), 300);
            }

            function checkGeofence(currentLat, currentLng) {
                const distance = calculateDistance(currentLat, currentLng, OFFICE_LAT, OFFICE_LONG);
                if (distance <= MAX_DISTANCE_M) {
                    isInsideGeofence = true;
                    locationText.textContent = `Dalam Jangkauan Presensi (${distance.toFixed(1)}m)`;
                    locationText.classList.remove('text-red-500', 'text-gray-500');
                    locationText.classList.add('text-green-600');
                } else {
                    isInsideGeofence = false;
                    locationText.textContent = `DI LUAR JANGKAUAN PRESENSI (${distance.toFixed(1)}m)`;
                    locationText.classList.remove('text-green-600', 'text-gray-500');
                    locationText.classList.add('text-red-500');
                }
                updateMap(currentLat, currentLng, isInsideGeofence);
                updateSubmitButtonStatus();
            }

            function updateSubmitButtonStatus() {
                if (!submitButtonExists) return;
                const actionText = isCiDone ? 'Check-Out' : 'Check-In';
                if (isPhotoTaken && isInsideGeofence) {
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
                    submitButton.removeAttribute('disabled');
                    submitButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                    submitButton.textContent = `${actionText} Sekarang`;
                } else {
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-400');
                    submitButton.setAttribute('disabled', 'true');
                    if(isPhotoTaken && !isInsideGeofence) {
                        submitButton.textContent = `${actionText} Ditolak (Di Luar Area)`;
                    } else if (isPhotoTaken && !isWorkingDay) {
                        submitButton.textContent = `${actionText} Ditolak (Hari Libur)`;
                    } else {
                        submitButton.textContent = actionText;
                    }
                }
            }

            function startWatchingLocation() {
                locationText.textContent = "Mencari lokasi...";
                locationText.classList.remove('text-red-500', 'text-green-600');
                locationText.classList.add('text-gray-500');
                retryLocationBtn.style.display = 'none';

                if (navigator.geolocation) {
                    if (watchID !== null) { navigator.geolocation.clearWatch(watchID); }
                    watchID = navigator.geolocation.watchPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const long = position.coords.longitude;
                            latitudeInput.value = lat.toFixed(6);
                            longitudeInput.value = long.toFixed(6);
                            checkGeofence(lat, long);
                        },
                        (error) => {
                            locationText.textContent = "Gagal mendapatkan lokasi. GPS Diperlukan.";
                            locationText.classList.remove('text-green-600', 'text-gray-500');
                            locationText.classList.add('text-red-500');
                            mapDiv.style.display = 'none';
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
            // --- END FUNGSI GPS/Maps ---

            // FUNGSI KRITIS: Mengoreksi Dimensi Kamera (Solusi untuk bug zoom)
            function correctWebcamScale() {
                const video = document.querySelector('#my_camera video');

                if (video) {
                    const containerWidth = cameraFeedDiv.clientWidth;
                    const desiredRatio = 3 / 4; // Coba rasio 4:3 (3/4)
                    const desiredHeight = containerWidth * desiredRatio;

                    // Paksa dimensi video/canvas sesuai container untuk mencegah zoom
                    video.style.width = containerWidth + 'px';
                    video.style.height = desiredHeight + 'px';

                    const canvas = document.querySelector('#my_camera canvas');
                    if (canvas) {
                         canvas.style.width = containerWidth + 'px';
                         canvas.style.height = desiredHeight + 'px';
                    }
                } else {
                    // Jika elemen belum dimuat, coba lagi setelah jeda
                    setTimeout(correctWebcamScale, 100);
                }
            }


            // Mulai pemantauan lokasi saat halaman dimuat
            if (isWorkingDay && !isCoDone) {
                startWatchingLocation();
            } else {
                locationText.textContent = isWorkingDay ? "Presensi sudah selesai hari ini." : "Bukan hari kerja.";
            }

            retryLocationBtn.addEventListener('click', startWatchingLocation);

            // --- 4. EVENT LISTENER: AKTIVASI KAMERA (Awal) ---
            if(activateCameraBtn) {
                activateCameraBtn.addEventListener('click', startCameraSession);
            }


            // FUNGSI INTI UNTUK MEMULAI/MENGULANG KAMERA
            function startCameraSession() {
                photoResultDiv.style.display = 'none';
                retakePhotoBtn.style.display = 'none';
                if(activateCameraBtn) activateCameraBtn.style.display = 'none';

                cameraFeedDiv.style.display = 'block';
                Webcam.attach( '#my_camera' );

                // KRITIS: Panggil fungsi koreksi dimensi setelah attach selesai
                setTimeout(correctWebcamScale, 500);

                takePhotoButton.style.display = 'block';
            }


            // --- 5. EVENT LISTENER: AMBIL FOTO (SOLUSI STABIL) ---
            takePhotoButton.addEventListener('click', function() {
                Webcam.snap( function(data_uri) {

                    imageDataInput.value = data_uri;

                    Webcam.reset();
                    cameraFeedDiv.style.display = 'none';
                    takePhotoButton.style.display = 'none';

                    // 3. Tampilkan pratinjau hasil foto (Metode Inject Stabil)
                    photoResultDiv.style.display = 'none';
                    photoResultDiv.innerHTML = '';

                    const imgTag = '<img id="preview-snapshot" src="" style="width:100%; height:100%; object-fit:cover; border-radius: 1rem;"/>';
                    photoResultDiv.innerHTML = imgTag;

                    photoResultDiv.style.display = 'block';
                    retakePhotoBtn.style.display = 'block';

                    // Mengisi SRC gambar setelah jeda (KRITIS untuk mobile rendering)
                    setTimeout(() => {
                        const previewImg = document.getElementById('preview-snapshot');
                        if (previewImg) {
                            previewImg.src = data_uri;
                        }
                    }, 100);

                    // 4. Update status dan tombol submit
                    isPhotoTaken = true;
                    updateSubmitButtonStatus();
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
            setInterval(updateTimeAndDate, 1000);

            // Konfigurasi Webcam (Mengurangi ukuran data untuk stabilitas)
            Webcam.set({
                width: 320, // Ukuran rendering internal yang lebih kecil
                height: 426,
                image_format: 'jpeg', // Menggunakan JPEG (lebih kecil dari PNG)
                jpeg_quality: 70,     // Kualitas 70%
                flip_horiz: true,
                image_mode: 'canvas',
            });

        });
    </script>
</x-karyawan-layout>
