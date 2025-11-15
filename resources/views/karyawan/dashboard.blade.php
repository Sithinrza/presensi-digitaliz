<x-karyawan-layout>
    <x-slot:title>
        Dashboard Karyawan
    </x-slot:title>

    <div class="relative min-h-screen pb-24">
        <main class="p-4 space-y-6">
            <section class="bg-indigo-950 p-6 rounded-2xl shadow-lg text-center ">
                <p class="mb-4 text-white">Anda belum presensi hari ini</p>
                <button class="w-full bg-white text-indigo-950 font-semibold py-3 px-4 rounded-xl hover:bg-gray-200 transition">
                    Presensi
                </button>
            </section>

            <section class="bg-white p-5 rounded-2xl shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Agenda Hari Ini</h2>
                    <button class="flex items-center space-x-1 text-xs text-gray-500 font-medium bg-gray-100 px-2 py-1 rounded-md hover:bg-gray-200">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Lihat Semua</span>
                    </button>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-950 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                        <p class="text-sm font-medium text-gray-700">Rapat dengan tim</p>
                    </div>
                     <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-950 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                        </svg>
                        <p class="text-sm font-medium text-gray-700">Mentoring anak magang</p>
                    </div>
                </div>
            </section>

            <section class="bg-white p-5 rounded-2xl shadow-lg flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Daily Report</h2>
                    <p class="text-xs text-gray-500">Rangkum aktifitas hari ini</p>
                </div>
                 <a href="{{ route('karyawan.report.index') }}">
                    <button class="px-4 py-2 bg-indigo-950 text-white text-sm font-semibold rounded-lg hover:bg-indigo-800 transition whitespace-nowrap">
                        Buat Laporan
                    </button>
                </a>
            </section>

             <section class="bg-white p-5 rounded-2xl shadow-lg">
                 <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Log Aktivitas Hari Ini</h2>
                    <button class="flex items-center space-x-1 text-xs text-gray-500 font-medium bg-gray-100 px-2 py-1 rounded-md hover:bg-gray-200">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                         </svg>
                        <span>Detail</span>
                    </button>
                </div>
                 <div class="space-y-4">
                     <div class="flex items-center space-x-3">
                         <div class="bg-gray-100 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                         </div>
                         <div>
                            <p class="text-sm font-medium text-gray-800">Membuat Halaman Login da...</p>
                            <p class="text-xs text-gray-500">26 menit yang lalu</p>
                         </div>
                     </div>
                     <div class="flex items-center space-x-3">
                         <div class="bg-gray-100 p-2 rounded-full">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                         </div>
                         <div>
                            <p class="text-sm font-medium text-gray-800">Rapat dengan TIM</p>
                            <p class="text-xs text-gray-500">1 jam yang lalu</p>
                         </div>
                     </div>
                     <div class="flex items-center space-x-3">
                         <div class="bg-gray-100 p-2 rounded-full">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                         </div>
                         <div>
                            <p class="text-sm font-medium text-gray-800">Mentoring anak magang</p>
                            <p class="text-xs text-gray-500">2 jam yang lalu</p> {{-- Asumsi waktu --}}
                         </div>
                     </div>
                 </div>
             </section>

        </main>
    </div>
</x-karyawan-layout>
