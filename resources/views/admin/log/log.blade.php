<x-admin-layout>
    <x-slot:title>
        Log
    </x-slot:title>

    <div class="relative min-h-screen pb-24"> <!-- Padding bawah untuk nav bottom -->
        <header class="bg-indigo-950 text-white shadow-lg sticky top-0 z-40">
            <div class="container mx-auto flex items-center p-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-lg font-semibold flex-grow text-center mr-10">
                    Log Aktivitas Karyawan
                </h1>
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="p-4 space-y-6">
            <section class="bg-white p-5 rounded-xl shadow-md">
                <form action="{{ route('admin.log.index') }}" method="GET" id="filter-form" class="space-y-4">
                     <div class="flex-grow">
                                <label for="filter_tanggal" class="sr-only">Filter Berdasarkan Tanggal Spesifik</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/><path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                                    </div>
                                    <input type="text"
                                        id="filter_tanggal"
                                        name="tanggal"
                                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5"
                                        placeholder="Pilih Tanggal Spesifik"
                                        value="{{ request('tanggal') !== 'all' ? request('tanggal', now()->format('d/m/Y')) : '' }}">
                                </div>
                            </div>

                    <div>
                        <label for="search_karyawan" class="block mb-1 text-sm font-medium text-gray-700">Cari Karyawan</label>
                        <div class="relative">
                            {{-- IKON SEARCH DIKEMBALIKAN --}}
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" id="search_karyawan" name="nama_karyawan"
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5"
                                placeholder="Masukkan nama karyawan..."
                                {{-- Pastikan menggunakan variabel $filterNama --}}
                                value="{{ $filterNama ?? '' }}">
                        </div>
                    </div>
                    <button type="submit" class="w-full text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Terapkan Filter
                    </button>
                </form>
            </section>

            <!-- Daftar Aktivitas (Tampilan Kartu Baru) -->
            <section>
                <div class="flex items-center justify-between mb-4 px-1">
                    <h2 class="text-lg font-bold text-gray-800">
                        Aktivitas {{ $displayDate }}
                    </h2>
                </div>

                <div class="space-y-6">

                    @forelse ($groupedLogs as $karyawanId => $activities)

                        @php
                            // Ambil data karyawan dari log pertama
                            $karyawan = $activities->first()->presensi->karyawan ?? null;
                            $karyawanName = $karyawan->user->name ?? 'Karyawan Tidak Dikenal';

                            // --- LOGIKA FOTO PROFIL BARU ---
                            $initials = $karyawanName ? substr($karyawanName, 0, 1) : 'U';
                            $fotoUrl = 'https://placehold.co/100x100?text=' . $initials; // Default

                            if ($karyawan && $karyawan->foto_profil) {
                                $fotoUrl = asset('storage/' . $karyawan->foto_profil);
                            }
                            // -------------------------------
                        @endphp

                        {{-- START: KARTU BESAR UNTUK SATU KARYAWAN --}}
                        <div class="bg-white p-4 rounded-xl shadow-lg border-t-4 border-indigo-600">

                            {{-- HEADER KARYAWAN --}}
                            <div class="flex items-center space-x-3 mb-4 pb-3 border-b border-gray-100">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full overflow-hidden border border-gray-200">
                                    <img src="{{ $fotoUrl }}" 
                                         alt="{{ $karyawanName }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <h3 class="font-bold text-lg text-gray-900">{{ $karyawanName }}</h3>
                            </div>

                            <div class="space-y-4">
                                {{-- INNER LOOP: SEMUA AKTIVITAS KARYAWAN --}}
                                @foreach ($activities as $log)
                                    <div class="border-l-4 pl-3 py-1 border-gray-200">

                                        {{-- Waktu dan Catatan Log --}}
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">{{ $log->catatan_log }}</p>
                                            </div>

                                            {{-- Waktu Log --}}
                                            <p class="text-sm font-bold text-indigo-500 flex-shrink-0 ml-4">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}</p>
                                        </div>

                                    </div>
                                @endforeach
                                {{-- END INNER LOOP --}}
                            </div>
                        </div>
                        {{-- END: KARTU BESAR UNTUK SATU KARYAWAN --}}

                    @empty
                        {{-- Tampilkan pesan ini jika tidak ada log ditemukan --}}
                        <div class="pt-4 text-center text-gray-500 italic">
                        Tidak ada aktivitas ditemukan untuk filter ini.
                        </div>
                    @endforelse
                </div>
            </section>
        </main>
    </div>

    {{-- Flatpickr harus dimuat sebelum script ini --}}

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterInput = document.getElementById('filter_tanggal');
            const filterForm = document.getElementById('filter-form');
            // Mengambil nilai 'tanggal' dari URL query parameter jika ada
             flatpickr(filterInput, {
                dateFormat: "d/m/Y", // Format standar YYYY-MM-DD yang dikirim ke server
                altInput: true,
                altFormat: "j F Y", // Format tampilan
                // defaultDate hanya diisi jika filter tidak 'all'
                defaultDate: filterInput.value || 'today',

                // --- FUNGSI AUTO SUBMIT ---
                onClose: function(selectedDates, dateStr, instance) {
                    // Cek jika nilai input berubah
                    if (dateStr && dateStr !== instance.originalValue) {
                        filterForm.submit(); // Otomatis submit form
                    }
                }
            });

            // NOTE: Flatpickr defaultDate harus berupa string YYYY-MM-DD atau Date object.
            // Dengan mengambil dari URL, kita memastikan formatnya sudah YYYY-MM-DD.
        });
    </script>
</x-admin-layout>
