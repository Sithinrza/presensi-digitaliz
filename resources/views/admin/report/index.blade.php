<x-admin-layout>
    <x-slot:title>
        report
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
                <h2 class="text-xl font-bold">Laporan Harian</h2>
            </div>
        </header>
        <!-- Konten Utama -->
        <main class="p-4 -mt-10 relative z-20 space-y-6">

            <section class="bg-white p-5 rounded-xl shadow-lg">
                 <form action="#" method="GET" class="space-y-4">
                     <div>
                        <label for="filter_tanggal" class="block mb-1 text-sm font-medium text-gray-700">Filter Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/><path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                            </div>
                            <input type="text" id="filter_tanggal" name="tanggal" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Pilih Tanggal" value="2025-10-30">
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
                            <input type="text" id="search_karyawan" name="search" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Masukkan nama karyawan..." value="">
                        </div>
                     </div>
                     <button type="submit" class="w-full text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Terapkan Filter
                    </button>
                 </form>
            </section>

            <!-- Daftar Laporan -->
            <section class="bg-white p-4 rounded-2xl shadow-lg">
                <div class="flex items-center justify-between mb-4 px-1">
                    <h2 class="text-lg font-bold text-gray-800">
                        Daftar Laporan
                    </h2>
                     <span class="text-sm font-medium text-gray-500">3 Laporan</span>
                </div>

                <!-- Kontainer Laporan -->
                <div class="space-y-3">
                    
                    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 space-y-3">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="https://placehold.co/40x40" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Hoshi</p>
                                    <p class="text-xs font-semibold text-gray-700">Desain Halaman Login</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-2">
                                <p class="text-sm font-bold text-gray-800">14:50</p>
                                <p class="text-xs text-gray-500">29 Okt 2025</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 pl-12">Menyelesaikan implementasi desain untuk halaman login utama.</p>
                        <div class="flex items-center justify-between pl-12">
                            <div class="flex items-center space-x-4">
                                <a href="#" target="_blank" class="text-xs text-blue-600 hover:underline inline-flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                                    <span>Link Figma</span>
                                </a>
                                <a href="#" target="_blank" class="text-xs text-green-600 hover:underline inline-flex items-center space-x-1">
                                     <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.122 2.122l7.81-7.81" /></svg>
                                    <span>File Draft.zip</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 space-y-3">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="https://placehold.co/40x40" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Abadi</p>
                                    <p class="text-xs font-semibold text-gray-700">Follow Up Client</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-2">
                                <p class="text-sm font-bold text-gray-800">10:50</p>
                                 <p class="text-xs text-gray-500">29 Okt 2025</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 pl-12">Melakukan follow up via email kepada Client A dan Client B.</p>
                         <div class="flex items-center justify-between pl-12">
                            <span class="text-xs text-gray-400 italic">Tidak ada lampiran</span>
                        </div>
                    </div>

                   <div class="bg-transparent p-4 rounded-xl text-center text-gray-500 italic">
                       Tidak ada laporan ditemukan untuk filter ini.
                    </div> 

                </div>

                <div class="mt-6 flex justify-center">
                    <nav aria-label="Pagination">
                      <ul class="inline-flex items-center -space-x-px h-8 text-sm">
                        <li><a href="#" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">Prev</a></li>
                        <li><a href="#" aria-current="page" class="z-10 flex items-center justify-center px-3 h-8 leading-tight text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700">1</a></li>
                        <li><a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">2</a></li>
                        <li><a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">Next</a></li>
                      </ul>
                    </nav>
                </div>
            </section>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterInput = document.getElementById('filter_tanggal');
            const filterForm = document.getElementById('filter-form');

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

            // Logika untuk membersihkan input tanggal saat tombol "Lihat Semua Agenda" diklik
            const allAgendaLink = document.querySelector('a[href*="tanggal=all"]');
            if (allAgendaLink) {
                allAgendaLink.addEventListener('click', function(e) {
                    // Kosongkan filter tanggal di Flatpickr saat pindah ke mode 'all'
                    flatpickr("#filter_tanggal").clear();
                });
            }
        });
    </script>
</x-admin-layout>