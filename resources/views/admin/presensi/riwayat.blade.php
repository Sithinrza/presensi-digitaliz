<x-admin-layout>
    <x-slot:title>
        Presensi Karyawan
    </x-slot:title>

    <div class="relative min-h-screen pb-24">

        @php
            // --- 1. HITUNG REKAPITULASI DARI DATA YANG ADA DI HALAMAN INI ---
            $totalKaryawan = $allKaryawan->count(); // Menggunakan semua karyawan aktif dari Controller

            // Inisialisasi hitungan status
            $recap = [
                1 => 0, // Tepat Waktu
                2 => 0, // Terlambat Check-In
                3 => 0, // Terlambat Check-Out
                4 => 0, // Lupa Check-Out
                5 => 0, // Tidak Hadir (Hanya yang tercatat)
            ];

            // Hitung status dari hasil query yang difilter
            foreach ($presensiHistory as $item) {
                if (isset($recap[$item->status_presensi_id])) {
                    $recap[$item->status_presensi_id]++;
                }
            }

            // Hitung total presensi terhitung (yang punya record)
            $totalPresensiRecorded = array_sum($recap);

            // Tentukan Tanggal Hari Ini untuk Header
            \Carbon\Carbon::setLocale('id');
            $todayDateFormatted = \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y');

        @endphp

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

            {{-- Statistik Header Disesuaikan dengan Status ID --}}
            <div class="mt-6 grid grid-cols-3 gap-3 text-center">

                {{-- Total Karyawan (Static/Max) --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-blue-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $totalKaryawan }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Total Karyawan</p>
                </div>

                {{-- Tepat Waktu (Status ID: 1) --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-green-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $recap[1] ?? 0 }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Tepat Waktu</p>
                </div>

                {{-- Terlambat Check-in (Status ID: 2) --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-yellow-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $recap[2] ?? 0 }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Terlambat Check-in</p>
                </div>

                {{-- Terlambat Check-Out (Status ID: 3) --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-orange-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $recap[3] ?? 0 }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Terlambat Check-Out</p>
                </div>

                {{-- Lupa Check-out (Status ID: 4) --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-purple-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $recap[4] ?? 0 }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Lupa Check-Out</p>
                </div>

                {{-- Tidak Hadir (Status ID: 5) --}}
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-red-500 text-white font-bold text-xs border-2 border-indigo-950">
                        {{ $recap[5] ?? 0 }}
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Tidak Hadir</p>
                </div>
            </div>
        </header>

        <main class="p-4 -mt-10 relative z-20">
            <div class="bg-white p-4 rounded-2xl shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-gray-800">Riwayat Presensi</h3>
                        <p class="text-xs text-gray-500">{{ $todayDateFormatted }}</p>
                    </div>
                    <a href="{{ route('admin.presensi.rekap') }}">
                        <button class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">
                            Rekap
                        </button>
                    </a>
                </div>

                {{-- --- 2. FORM FILTER --- --}}
                <form method="GET" action="{{ route('admin.presensi.index') }}" class="space-y-3 mb-4">

                    {{-- Filter Status (Pill Buttons) --}}
                    <div class="flex space-x-2 overflow-x-auto pb-2">
                        <button type="submit" name="status_id" value=""
                                class="px-3 py-1 text-xs whitespace-nowrap rounded-full
                                {{ empty($statusId) ? 'bg-indigo-600 text-white' : 'text-gray-700 bg-gray-100' }}">
                            Semua ({{ $totalPresensiRecorded }})
                        </button>
                        @foreach ($statuses as $id => $name)
                            @php
                                $pillClass = '';
                                if ($id == 1) $pillClass = 'text-green-700 bg-green-100';
                                elseif ($id == 2) $pillClass = 'text-yellow-700 bg-yellow-100';
                                elseif ($id == 3) $pillClass = 'text-orange-700 bg-orange-100';
                                elseif ($id == 4) $pillClass = 'text-purple-700 bg-purple-100';
                                elseif ($id == 5) $pillClass = 'text-red-700 bg-red-100';

                                if ($statusId == $id) {
                                    $pillClass = 'bg-indigo-600 text-white';
                                }
                            @endphp
                            <button type="submit" name="status_id" value="{{ $id }}"
                                    class="px-3 py-1 text-xs whitespace-nowrap rounded-full {{ $pillClass }}">
                                {{ $name }} ({{ $recap[$id] ?? 0 }})
                            </button>
                        @endforeach
                    </div>

                    {{-- Filter Tanggal dan Pencarian Nama --}}
                    <div class="flex space-x-2">
                         <input type="date" name="start_date" value="{{ $startDate }}"
                               class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                               onchange="this.form.submit()">
                        <input type="date" name="end_date" value="{{ $endDate }}"
                               class="w-full text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                               onchange="this.form.submit()">
                    </div>

                    <div class="relative">
                        <input type="text" name="name" value="{{ $searchName }}" placeholder="Cari nama karyawan"
                               class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        {{-- Tombol Submit Search (jika enter tidak ditekan) --}}
                        <button type="submit" class="hidden"></button>

                        @if ($searchName || $statusId || $startDate != \Carbon\Carbon::now()->startOfMonth()->toDateString() || $endDate != \Carbon\Carbon::now()->endOfMonth()->toDateString())
                            <a href="{{ route('admin.presensi.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-red-500" title="Reset Filter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
                            </a>
                        @endif
                    </div>
                </form>

                {{-- --- 3. DAFTAR RIWAYAT DINAMIS --- --}}
                <div class="space-y-3">
                    @forelse ($presensiHistory as $presensi)
                        @php
                            $statusId = $presensi->status_presensi_id;
                            $statusName = $statuses[$statusId] ?? 'Tidak Diketahui';
                            $pillClass = '';

                            if ($statusId == 1) $pillClass = 'text-green-600 bg-green-100';
                            elseif ($statusId == 2) $pillClass = 'text-yellow-600 bg-yellow-100';
                            elseif ($statusId == 3) $pillClass = 'text-orange-600 bg-orange-100';
                            elseif ($statusId == 4) $pillClass = 'text-purple-600 bg-purple-100';
                            elseif ($statusId == 5) $pillClass = 'text-red-600 bg-red-100';
                        @endphp

                        <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 space-y-3">
                            <div class="flex items-start justify-between">
                                {{-- KIRI: NAMA & STATUS --}}
                                <div class="flex items-center space-x-3">
                                    <div class="shrink-0 w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center">
                                        {{-- Placeholder Avatar/Initial --}}
                                        <span class="text-white font-bold">{{ strtoupper(substr($presensi->karyawan->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $presensi->karyawan->name }}</p>
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $pillClass }}">{{ $statusName }}</span>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ \Carbon\Carbon::parse($presensi->tanggal)->isoFormat('dddd, D MMM Y') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- KANAN: WAKTU CI/CO --}}
                                <div class="text-right flex space-x-2 text-sm">
                                    <div class="p-1 rounded-lg bg-gray-50 border border-gray-100 text-center">
                                        <p class="text-xs text-gray-500">CI</p>
                                        <p class="font-bold text-gray-800">{{ $presensi->waktu_ci ? \Carbon\Carbon::parse($presensi->waktu_ci)->format('H:i') : '--' }}</p>
                                    </div>
                                    <div class="p-1 rounded-lg bg-gray-50 border border-gray-100 text-center">
                                        <p class="text-xs text-gray-500">CO</p>
                                        <p class="font-bold text-gray-800">{{ $presensi->waktu_co ? \Carbon\Carbon::parse($presensi->waktu_co)->format('H:i') : '--' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- LOKASI DAN TOMBOL DETAIL --}}
                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                <div class="flex items-center space-x-2 text-sm text-gray-500 max-w-[60%] truncate">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                    {{-- Hanya menampilkan Latitude CI sebagai placeholder lokasi cepat --}}
                                    <span>Lat CI: {{ $presensi->latitude_ci ? substr($presensi->latitude_ci, 0, 8) . '...' : 'N/A' }}</span>
                                </div>

                                <a href="{{ route('admin.presensi.detail', ['id' => $presensi->id]) }}">
                                    <button class="flex items-center space-x-2 text-sm text-indigo-600 font-medium px-3 py-1 bg-indigo-50 rounded-lg hover:bg-indigo-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            <path d="M5 7h1a2 2 0 0 0 2-2a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2"/>
                                            <path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0-6 0"/>
                                        </g></svg>
                                        <span>Detail</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-5 text-gray-500 bg-gray-50 rounded-lg">
                            Tidak ada data presensi yang ditemukan untuk filter ini.
                        </div>
                    @endforelse
                </div>

                {{-- --- 4. PAGINATION --- --}}
                <div class="mt-6">
                    {{ $presensiHistory->links() }}
                </div>

            </div>
        </main>
    </div>

</x-admin-layout>
