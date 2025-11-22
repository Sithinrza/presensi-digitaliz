<x-admin-layout>
    <x-slot:title>
        Laporan Harian
    </x-slot:title>

    <div class="relative min-h-screen pb-24">
        <header class="bg-indigo-950 p-4 pb-16 rounded-b-[2.5rem] shadow-lg relative z-10 -mt-1">
            <!-- Judul Halaman -->
            <div class="flex items-center space-x-3 text-white mb-4">
                <a href="{{ route('admin.dashboard') }}" class="p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-xl font-bold">Laporan Harian Karyawan</h2>
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="p-4 -mt-10 relative z-20 space-y-6">

            <section class="bg-white p-5 rounded-xl shadow-lg">
                {{-- Form Filter --}}
                <form action="{{ route('admin.report.index') }}" method="GET" id="filter-form" class="space-y-4">
                    <div>
                        <label for="filter_tanggal" class="block mb-1 text-sm font-medium text-gray-700">Filter Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/><path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                            </div>
                            <input type="text" id="filter_tanggal" name="tanggal"
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5"
                                placeholder="Pilih Tanggal"
                                value="{{ $selected_date ?? '' }}">
                        </div>
                    </div>
                    <div>
                        <label for="search_karyawan" class="block mb-1 text-sm font-medium text-gray-700">Cari Karyawan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" id="search_karyawan" name="search"
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5"
                                placeholder="Masukkan nama karyawan..."
                                value="{{ $search_query ?? '' }}">
                        </div>
                    </div>
                    <button type="submit" class="w-full text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Cari
                    </button>
                    {{-- Tombol Reset Filter --}}
                    {{-- @if (($selected_date ?? false) || ($search_query ?? false))
                        <a href="{{ route('admin.report.index') }}" class="w-full block text-center text-sm font-medium text-gray-600 hover:text-indigo-700 mt-2">
                            Reset Semua Filter
                        </a>
                    @endif --}}
                </form>
            </section>

            <!-- Daftar Laporan -->
            <section class="bg-white p-4 rounded-2xl shadow-lg">
                <div class="flex items-center justify-between mb-4 px-1">
                    <h2 class="text-lg font-bold text-gray-800">
                        Daftar Laporan
                    </h2>
                    <span class="text-sm font-medium text-gray-500">{{ $reports->total() }} Laporan</span>
                </div>

                <!-- Kontainer Laporan -->
                <div class="space-y-3">

                    @forelse ($reports as $report)
                        @php
                            // Ambil lampiran yang relevan
                            $linkAttachment = $report->attachments->where('type', 'link')->first();
                            $fileAttachment = $report->attachments->where('type', 'file')->first();
                        @endphp
                        <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 space-y-3">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-3">
                                    {{-- Tampilkan Avatar Karyawan --}}
                                    <img src="{{ $report->employee->profile_photo_url ?? 'https://placehold.co/40x40' }}" class="w-10 h-10 rounded-full object-cover">
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $report->employee->name ?? 'N/A' }}</p>
                                        <p class="text-xs font-semibold text-gray-700">{{ $report->title }}</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0 ml-2">
                                    {{-- Tampilkan Waktu dan Tanggal --}}
                                    <p class="text-sm font-bold text-gray-800">{{ $report->report_date->format('H:i') }}</p>
                                    <p class="text-xs text-gray-500">{{ $report->report_date->isoFormat('D MMM YYYY') }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 pl-12">{{ $report->description }}</p>
                            <div class="flex items-center justify-between pl-12">
                                <div class="flex items-center space-x-4">

                                    {{-- Link Attachment --}}
                                    @if ($linkAttachment)
                                        <a href="{{ $linkAttachment->url_or_path }}" target="_blank" class="text-xs text-blue-600 hover:underline inline-flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                                            <span>Link Lampiran</span>
                                        </a>
                                    @endif

                                    {{-- File Attachment --}}
                                    @if ($fileAttachment)
                                        <a href="{{ Storage::url($fileAttachment->url_or_path) }}" target="_blank" class="text-xs text-green-600 hover:underline inline-flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.122 2.122l7.81-7.81" /></svg>
                                            <span>File: {{ $fileAttachment->filename }}</span>
                                        </a>
                                    @endif

                                    @if (!$linkAttachment && !$fileAttachment)
                                        <span class="text-xs text-gray-400 italic">Tidak ada lampiran</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-transparent p-4 rounded-xl text-center text-gray-500 italic">
                            Tidak ada laporan ditemukan untuk filter ini.
                        </div>
                    @endforelse

                </div>

                {{-- Pagination --}}
                @if ($reports->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $reports->links('pagination::tailwind') }}
                    </div>
                @endif
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterInput = document.getElementById('filter_tanggal');
            const filterForm = document.getElementById('filter-form'); // Ambil referensi form

            // Ambil nilai filter saat ini (format YYYY-MM-DD)
            const initialDateValue = filterInput.value;

            flatpickr(filterInput, {
                // Format yang dikirim ke Controller
                dateFormat: "Y-m-d",
                // Format tampilan
                altInput: true,
                altFormat: "j F Y",
                // Atur defaultDate
                defaultDate: initialDateValue || null,

                // --- PERBAIKAN: Tambahkan Auto-Submit ---
                onClose: function(selectedDates, dateStr, instance) {
                    // Cek jika tanggal dipilih atau diubah
                    if (dateStr) {
                        filterForm.submit(); // Otomatis submit form GET
                    }
                }
            });
        });
    </script>
</x-admin-layout>
