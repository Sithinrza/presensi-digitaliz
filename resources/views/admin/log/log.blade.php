<x-admin-layout>
    <x-slot:title>
        log
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
                 <form action="#" method="GET" class="space-y-4"> 
                     <div>
                        <label for="filter_tanggal" class="block mb-1 text-sm font-medium text-gray-700">Filter Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/><path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                            </div>
                            <input type="text" id="filter_tanggal" name="tanggal" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Pilih Tanggal" value="2025-10-29"> {{-- Contoh Value --}}
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
                            <input type="text" id="search_karyawan" name="nama_karyawan" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Masukkan nama karyawan...">
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
                        Aktivitas Rabu, 29 Oktober 2025 
                    </h2>
                </div>

                <div class="space-y-3">
                    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 space-y-2">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                   <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Hoshi</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Menyelesaikan desain halaman login.</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-2">
                                <p class="text-sm font-bold text-gray-800">14:50</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pl-12">
                            <span class="text-xs text-gray-400 italic">Tidak ada lampiran</span>
                            <a href="#" class="text-xs text-blue-600 font-medium hover:underline">Detail</a>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 space-y-2">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Abadi</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Melakukan follow up client A.</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-2">
                                <p class="text-sm font-bold text-gray-800">10:50</p>
                            </div>
                        </div>
                         <div class="flex items-center justify-between pl-12">
                            <span class="text-xs text-gray-400 italic">Tidak ada lampiran</span>
                            <a href="#" class="text-xs text-blue-600 font-medium hover:underline">Detail</a>
                        </div>
                    </div>

                     {{-- Contoh Kartu Log 3 --}}
                     <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 space-y-2">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Hoshi</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Memulai pengerjaan halaman dashboard.</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-2">
                                <p class="text-sm font-bold text-gray-800">08:33</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pl-12">
                             <a href="#" class="text-xs text-green-600 hover:underline inline-flex items-center space-x-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.122 2.122l7.81-7.81" />
                                </svg>
                                <span>Lihat File Draft</span>
                             </a>
                            <a href="#" class="text-xs text-blue-600 font-medium hover:underline">Detail</a>
                        </div>
                    </div>

                     <div class="pt-4 text-center text-gray-500 italic">
                       Tidak ada aktivitas ditemukan untuk filter ini.
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Flatpickr untuk filter tanggal
            flatpickr("#filter_tanggal", {
                dateFormat: "Y-m-d", // Format standar YYYY-MM-DD
                altInput: true,
                altFormat: "j F Y", // Format tampilan
                // Mengambil tanggal dari URL atau default hari ini
                defaultDate: new URLSearchParams(window.location.search).get('tanggal') || "today" 
            });
            // Anda bisa tambahkan JS lain di sini jika perlu
        });
    </script>
</x-admin-layout>