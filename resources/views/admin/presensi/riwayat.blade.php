<x-admin-layout>
    <x-slot:title>
        Presensi Karyawan
    </x-slot:title>

    <div class="relative pb-24">

        <header class="bg-indigo-950 p-4 pb-16 rounded-t-[2.5rem] shadow-lg relative z-10 -mt-1">
            <div class="flex items-center space-x-3 text-white mb-4">
                <a href="{{ route('admin.dashboard') }}">
                    <button class="p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </a>
                <h2 class="text-xl font-bold">Presensi Karyawan</h2>
            </div>

            {{-- STATISTIK DINAMIS --}}
            <div class="mt-6 grid grid-cols-3 gap-3 text-center">
                {{-- Total Karyawan --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-blue-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $totalKaryawan }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Total Karyawan</p>
                </div>
                {{-- Tepat Waktu (ID 1) --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-green-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $stats[1] ?? 0 }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Presensi Tepat Waktu</p>
                </div>
                {{-- Terlambat Check-in (ID 2) --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-yellow-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $stats[2] ?? 0 }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Terlambat Check-in</p>
                </div>
                {{-- Terlambat Check-Out (ID 3) --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-orange-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $stats[3] ?? 0 }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Terlambat Check-Out</p>
                </div>
                {{-- Lupa Check-out (ID 4) --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-purple-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $stats[4] ?? 0 }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Lupa Check-out</p>
                </div>
                {{-- Tidak Hadir (ID 5) --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-red-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $stats[5] ?? 0 }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Tidak Hadir</p>
                </div>
            </div>
        </header>

        <main class="p-4 -mt-10 relative z-20">
            <div class="bg-white p-4 rounded-2xl shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-gray-800">Status Presensi</h3>
                        <p class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($tanggal_filter)->translatedFormat('l, d F Y') }}
                        </p>
                    </div>
                </div>

                {{-- FORM FILTER --}}
                <form method="GET" action="{{ route('admin.presensi.index') }}" class="space-y-3 mb-4">
                    {{-- Filter Status (digantikan dengan tombol filter) --}}
                    <div class="flex space-x-2 overflow-x-auto pb-2">
                        @php
                            $statusClasses = [
                                null => ['text' => 'text-gray-700', 'bg' => 'bg-gray-100'],
                                1 => ['text' => 'text-green-700', 'bg' => 'bg-green-100'],
                                2 => ['text' => 'text-yellow-700', 'bg' => 'bg-yellow-100'],
                                3 => ['text' => 'text-orange-700', 'bg' => 'bg-orange-100'],
                                4 => ['text' => 'text-purple-700', 'bg' => 'bg-purple-100'],
                                5 => ['text' => 'text-red-700', 'bg' => 'bg-red-100'],
                            ];
                        @endphp

                        <button type="submit" name="status" value="" class="px-3 py-1 text-xs {{ $status_filter == null ? 'bg-indigo-600 text-white' : 'text-gray-700 bg-gray-100' }} rounded-full whitespace-nowrap hover:bg-gray-200">Semua</button>

                        @foreach ($statuses as $id => $name)
                            @if ($id < 5) {{-- Tampilkan Tepat Waktu, Terlambat CI, Terlambat CO, Lupa CO --}}
                            @php
                                $class = $statusClasses[$id] ?? $statusClasses[null];
                                $isActive = $status_filter == $id;
                            @endphp
                            <button type="submit" name="status" value="{{ $id }}"
                                class="px-3 py-1 text-xs whitespace-nowrap rounded-full font-semibold
                                {{ $isActive ? 'bg-indigo-600 text-white' : $class['text'] . ' ' . $class['bg'] . ' hover:' . $class['bg'] . '/80' }}">
                                {{ $name }}
                            </button>
                            @endif
                        @endforeach
                         {{-- Tombol Tidak Hadir (ID 5) --}}
                        @php
                            $class = $statusClasses[5];
                            $isActive = $status_filter == 5;
                        @endphp
                        <button type="submit" name="status" value="5"
                            class="px-3 py-1 text-xs whitespace-nowrap rounded-full font-semibold
                            {{ $isActive ? 'bg-indigo-600 text-white' : $class['text'] . ' ' . $class['bg'] . ' hover:' . $class['bg'] . '/80' }}">
                            Tidak Hadir
                        </button>

                    </div>

                    {{-- Input Pencarian Nama & Tanggal --}}
                    <div class="flex space-x-2">
                        <div class="relative flex-grow">
                            <input type="text" name="nama" placeholder="Cari nama karyawan" value="{{ $nama_filter }}"
                                class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                        </div>
                            <div class="flex items-center space-x-0 border border-gray-300 rounded-lg p-0.5 max-w-[150px] md:max-w-none">
                                    {{-- Tombol Panah KIRI (Tanggal Mundur) --}}
                                <button type="button" onclick="changeDate(-1)" class="p-1 text-gray-600 hover:bg-gray-100 rounded-l-lg transition duration-150">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                                </button>

                                {{-- Input Tanggal (Diperlukan untuk date picker dan data submit) --}}
                                <input type="date" id="tanggal_input" name="tanggal" value="{{ $tanggal_filter }}"
                                    onchange="this.form.submit()"
                                    class="px-1 py-1 text-sm border-0 text-black text-center focus:ring-transparent focus:border-transparent max-w-[100px] md:max-w-[120px] bg-transparent">

                                {{-- Tombol Panah KANAN (Tanggal Maju) --}}
                                <button type="button" onclick="changeDate(1)" class="p-1 text-gray-600 hover:bg-gray-100 rounded-r-lg transition duration-150">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </button>

                            </div>
                        <button type="submit" class="p-2 bg-indigo-600 text-white rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </button>
                    </div>
                </form>


                {{-- DAFTAR PRESENSI --}}
                <div class="space-y-3">
                    {{-- Kita me-loop objek KARYAWAN sekarang, bukan objek presensi --}}
                    @forelse ($presensi_list as $karyawan)
                        @php
                            $presensi = $karyawan->presensi_detail; // Data presensi yang sudah di-map (atau default status NULL/5)

                            // Gunakan variabel dari objek presensi_detail
                            $statusCiId = $presensi->status_ci_id;
                            $statusCoId = $presensi->status_co_id;
                            $karyawanName = $karyawan->nama_lengkap ?? 'N/A';
                            // Tentukan apakah statusnya netral/kosong (NULL)
                            $isNetral = is_null($presensi->status_presensi_id);
                            $waktu_ci = $presensi->waktu_ci ? \Carbon\Carbon::parse($presensi->waktu_ci)->format('H:i') : '--';
                            $waktu_co = $presensi->waktu_co ? \Carbon\Carbon::parse($presensi->waktu_co)->format('H:i') : '--';


                            // Logika Warna Pill
                            $classes = [
                                1 => ['text' => 'text-green-600', 'bg' => 'bg-green-100'],
                                2 => ['text' => 'text-yellow-600', 'bg' => 'bg-yellow-100'],
                                3 => ['text' => 'text-orange-600', 'bg' => 'bg-orange-100'],
                                4 => ['text' => 'text-purple-600', 'bg' => 'bg-purple-100'],
                                5 => ['text' => 'text-red-600', 'bg' => 'bg-red-100'],
                                6 => ['text' => 'text-gray-600', 'bg' => 'bg-gray-200'],
                            ];

                            // Fungsi untuk membuat Pill Status
                            $makePill = function ($id, $label) use ($classes, $statuses) {
                                $style = $classes[$id] ?? $classes[5];
                                $name = $statuses[$id] ?? 'N/A';
                                return "<span class='text-xs font-semibold {$style['text']} {$style['bg']} px-2 py-0.5 rounded-full'>{$name} ({$label})</span>";
                            };

                            // Tampilan untuk status netral
                            $pillNetral = "<span class='text-xs font-semibold text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full'>Belum Presensi</span>";

                            // Buat Pill untuk Check-In (CI) dan Check-Out (CO)
                            $pillCI = $makePill($statusCiId, 'CI');
                            // 🚨 Perbaikan: Jika status CO adalah null, tampilkan sebagai Belum CO (netral)
                            $pillCO = $statusCoId === null ? "<span class='text-xs font-semibold text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full'>Belum CO</span>" : $makePill($statusCoId, 'CO');


                        @endphp

                        <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 space-y-3">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-3">
                                    {{-- Avatar/Ikon --}}
                                    <div class="shrink-0 w-12 h-12 bg-black rounded-full flex items-center justify-center">
                                         <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $karyawanName }}</p>

                                        {{-- 🚀 AREA TAMPILAN DUA STATUS --}}
                                        <div class="flex flex-wrap gap-1 mt-0.5">
                                            @if ($isNetral)
                                                {{-- Jika status NULL, tampilkan netral --}}
                                                {!! $pillNetral !!}
                                            @else
                                                {{-- Tampilkan Status Check-In --}}
                                                {!! $pillCI !!}

                                                {{-- Tampilkan Status Check-Out hanya jika sudah CI --}}
                                                @if ($presensi->waktu_ci)
                                                     {!! $pillCO !!}
                                                @endif
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                {{-- Waktu Presensi --}}
                                @if ($presensi->waktu_co)
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ $waktu_co }}</p>
                                        <p class="text-xs text-gray-500">Check-Out</p>
                                    </div>
                                @elseif ($presensi->waktu_ci)
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ $waktu_ci }}</p>
                                        <p class="text-xs text-indigo-500 font-semibold">Check-In</p>
                                    </div>
                                @else
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">--</p>
                                        <p class="text-xs text-gray-500">Belum Presensi</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Lokasi dan Tombol Detail --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                    <span>{{ $presensi->latitude_ci ? 'Lokasi Tersedia' : 'Lokasi Tidak Tersedia' }}</span>
                                </div>

                                {{-- Tombol Detail hanya muncul jika ada ID presensi yang valid --}}
                                @if ($presensi->id)
                                    <a href="{{ route('admin.presensi.detail', $presensi->id) }}" class="flex items-center space-x-2 text-sm text-gray-600 font-medium px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            <path d="M5 7h1a2 2 0 0 0 2-2a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2"/>
                                            <path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0-6 0"/>
                                        </g></svg>
                                        <span>Detail</span>
                                    </a>
                                @else
                                    <button disabled class="flex items-center space-x-2 text-sm text-gray-400 font-medium px-3 py-1 bg-gray-100 rounded-lg cursor-not-allowed">
                                        <span>Tidak Ada Data</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-5 text-gray-500 bg-gray-50 rounded-lg">
                            Tidak ada data presensi yang ditemukan pada tanggal ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <script>
    // Ambil form dan input tanggal
    const form = document.querySelector('form[action="{{ route('admin.presensi.index') }}"]');
    const tanggalInput = document.getElementById('tanggal_input');

    /**
     * Mengubah tanggal dan mengirimkan form.
     * @param {number} days - Jumlah hari untuk maju (+1) atau mundur (-1).
     */
    function changeDate(days) {
        // Ambil tanggal saat ini dari input
        const currentDateString = tanggalInput.value;
        const currentDate = new Date(currentDateString + 'T00:00:00'); // Tambahkan T00:00:00 untuk menghindari masalah zona waktu

        // Hitung tanggal baru
        currentDate.setDate(currentDate.getDate() + days);

        // Format tanggal baru menjadi YYYY-MM-DD
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');

        const newDateString = `${year}-${month}-${day}`;

        // Set nilai baru ke input tanggal
        tanggalInput.value = newDateString;

        // Kirim form secara otomatis
        form.submit();
    }
</script>
</x-admin-layout>
