<x-karyawan-layout>
    <x-slot:title>
        agenda
    </x-slot:title>
    <div class="relative min-h-screen pb-24">
        <header class="bg-indigo-950 text-white shadow-lg sticky top-0 z-30">
            <div class="container mx-auto flex items-center p-4">
                <a href="{{ route('admin.dashboard') }}" class="p-2 mr-2"> 
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-lg font-semibold flex-grow text-center mr-10">
                    Daftar Agenda
                </h1>
            </div>
        </header>

        <!-- Konten Utama (Padding diperbaiki) -->
        <main class="p-4 pt-6 relative z-20 space-y-6"> 
            <section class="bg-white pt-5 rounded-xl shadow-lg">
                 <form action="#" method="GET" class="space-y-4">
                     <div class="px-5"> 
                        <div class="flex justify-end">
                            <a href="{{ route('admin.agenda.create') }}" class="inline-flex w-auto text-white bg-indigo-800 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-3 text-center shadow-lg flex items-center justify-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                <span>Tambah Agenda Baru</span>
                            </a>
                        </div>
                        <label for="filter_tanggal" class="block mb-1 text-sm font-medium text-gray-700">Lihat Agenda Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/><path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                            </div>
                            <input type="text" id="filter_tanggal" name="tanggal" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Pilih Tanggal" value="2025-10-30">
                        </div>
                    </div>
                     <div class="px-5 pb-5">
                         <button type="submit" class="btn-indi">
                            Lihat Agenda
                        </button>
                     </div>
                 </form>
            </section>

            <!-- Daftar Agenda -->
            <section class="bg-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-between mb-4 px-1">
                    <h2 class="text-lg font-bold text-gray-800">
                        Agenda: Rabu, 29 Oktober 2025
                    </h2>
                </div>

                <!-- Kontainer Agenda -->
                <div class="space-y-4">
                    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200">
                         <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                             <span class="p-2 bg-gray-100 rounded-lg mr-2">
                                <svg class="w-5 h-5 text-indigo-950" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                             </span>
                            Evaluasi Bulanan
                         </h3>
                         <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-gray-500 pl-12">
                            <div class="flex items-center space-x-2">
                                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span>Sabtu, 28 Feb 2030</span>
                            </div>
                             <div class="flex items-center space-x-2">
                                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>09 :00 - Selesai</span>
                            </div>
                             <div class="flex items-center space-x-2">
                                 <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span>Jl. Kp Melayu</span>
                            </div>
                             <div class="flex items-center space-x-2">
                                 <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                <span>Ruang rapat lantai 2</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 mt-3 pt-3 flex justify-end space-x-2">
                            <a href="#">
                                <button class="flex items-center space-x-1 px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Edit</span>
                                </button>
                            </a>
                             <button class="flex items-center space-x-1 px-3 py-1 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200">
                         <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                             <span class="p-2 bg-gray-100 rounded-lg mr-2">
                                <svg class="w-5 h-5 text-indigo-950" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                             </span>
                            Mentoring
                         </h3>
                         <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-gray-500 pl-12">
                            <div class="flex items-center space-x-2">
                                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span>Sabtu, 28 Feb 2030</span>
                            </div>
                             <div class="flex items-center space-x-2">
                                <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>14:00 - Selesai</span>
                            </div>
                             <div class="flex items-center space-x-2">
                                 <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span>Jl. Kp Melayu</span>
                            </div>
                             <div class="flex items-center space-x-2">
                                 <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                <span>Zoom</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 mt-3 pt-3 flex justify-end space-x-2">
                             <button class="flex items-center space-x-1 px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                <span>Edit</span>
                            </button>
                             <button class="flex items-center space-x-1 px-3 py-1 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>
                     <div class="bg-transparent p-4 rounded-xl text-center text-gray-500 italic">
                       Tidak ada agenda untuk tanggal ini.
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
                defaultDate: "today" // Set default hari ini
            });
        });
     </script> 

</x-karyawan-layout>
