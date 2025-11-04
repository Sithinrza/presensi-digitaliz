<x-karyawan-layout>
    <x-slot:title>
        Jadwal
    </x-slot:title>

    <div class="relative min-h-screen pb-24 bg-gray-100">
        <!-- Header Profil -->
        <header class="bg-white p-4 shadow-sm sticky top-0 z-20">
            <div class="flex items-center space-x-3">
                <img class="w-10 h-10 rounded-full object-cover" 
                     src="{{ Auth::user()->profile_photo_url ?? 'https://placehold.co/40x40/7F9CF5/FFFFFF?text=' . strtoupper(substr(Auth::user()->name, 0, 1)) }}" 
                     alt="Foto {{ Auth::user()->name }}">
                <div>
                    <h1 class="text-gray-800 font-bold text-lg">{{ Auth::user()->name }}</h1>
                </div>
            </div>
        </header>
         <div class="bg-indigo-950 p-4 pt-8 pb-24 -mt-1 rounded-t-[3rem] relative z-10">
         </div>
        <main class="p-4 -mt-20 relative z-10 space-y-6">
            <section class="bg-white p-5 rounded-2xl shadow-lg">
                
                <input type="text" id="karyawan-calendar" class="hidden">
            </section>
            <section id="agenda-section" class="space-y-3">
                 <h2 class="text-lg font-bold text-gray-800 px-1">Agenda Hari Ini</h2>
                 
                 <div id="agenda-list" class="space-y-3">
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
                                <span>09:00 - Selesai</span>
                            </div>
                             <div class="flex items-center space-x-2">
                                 <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span>Jl. Kp Melayu</span>
                            </div>
                             <div class="flex items-center space-x-2">
                                <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path d="M2.992 4.018A2 2 0 014.99 2h10.02a2 2 0 011.998 2.018L17 14a3 3 0 11-6 0L11 8l-3 6a3 3 0 11-6 0L2 4.018A.001.001 0 012.992 4zm1.01 4h12V4H4v4z" /></svg>
                                <span>Ruang Rapat</span>
                            </div>
                         </div>
                    </div>

                    <!-- Card Agenda 2 (Ikon diperbarui) -->
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
                    </div>
                 </div>
            </section>
        </main>
    </div>

    {{-- <style>
        /* --- Styling Datepicker Flowbite agar angka hitam --- */
        .datepicker-cell {
            color: #000 !important; /* ubah semua angka jadi hitam */
        }
        .datepicker-cell.focused, 
        .datepicker-cell.selected {
            color: #fff !important; /* angka di tanggal aktif tetap putih */
            background-color: #4338ca !important; /* biru tua untuk tanggal aktif */
        }
        .datepicker-cell.disabled {
            color: #d1d5db !important; /* angka tanggal non-aktif abu-abu */
        }
    </style> --}}
</x-karyawan-layout>
