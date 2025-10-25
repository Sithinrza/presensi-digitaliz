<x-karyawan-layout>
    <x-slot:title>
        Log Aktivitas
    </x-slot:title>

    <div class="relative min-h-screen pb-24">
        <header class="bg-white p-4 shadow-sm sticky top-0 z-20">
            <h1 class="text-gray-800 font-bold text-lg text-center">Log Aktivitas</h1>
        </header>

        <!-- Konten Utama -->
        <main class="p-4 space-y-6">
            <section class="bg-indigo-950 p-6 rounded-2xl shadow-lg">
                <h2 class="text-lg font-semibold text-white mb-4">Tambah Aktivitas Baru</h2>
                <textarea rows="3" class="w-full p-3 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 mb-4" placeholder="Apa yang dilakukan hari ini?"></textarea>
                <button class="w-full bg-indigo-700 hover:bg-indigo-800 text-white font-semibold py-3 px-4 rounded-xl transition flex items-center justify-center space-x-2">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Simpan Aktivitas</span>
                </button>
            </section>

            <!-- Daftar Aktivitas Hari Ini -->
            <section>
                <div class="flex items-center justify-between mb-4 px-1">
                    <h2 class="text-lg font-bold text-gray-800">Aktivitas Hari Ini</h2>
                    <div class="flex items-center space-x-1 text-xs text-gray-500 font-medium">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span>Sabtu, 28 Februari 2030</span> {{-- Ganti dengan tanggal dinamis --}}
                    </div>
                </div>

                <!-- Timeline Container -->
                <div class="relative pl-8">
                    <div class="timeline-item relative pb-6">
                        <div class="timeline-dot"></div>
                        <div class="absolute left-0 top-3 text-xs font-semibold text-gray-500 -translate-x-full pr-2">14:50</div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200 ml-4">
                            <p class="text-sm text-gray-700">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor</p>
                        </div>
                    </div>
                     <div class="timeline-item relative pb-6">
                        <div class="timeline-dot"></div>
                        <div class="absolute left-0 top-3 text-xs font-semibold text-gray-500 -translate-x-full pr-2">10:50</div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200 ml-4">
                            <p class="text-sm text-gray-700">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</p>
                            <ul class="list-disc list-inside text-sm text-gray-700 mt-1">
                                <li>eiusmod tempor</li>
                                <li>consectetur adipiscing elit, sed do</li>
                            </ul>
                        </div>
                    </div>
                      <div class="timeline-item relative pb-6">
                        <div class="timeline-dot"></div>
                        <div class="absolute left-0 top-3 text-xs font-semibold text-gray-500 -translate-x-full pr-2">08:33</div>
                        <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200 ml-4">
                             <p class="text-sm text-gray-700">Lorem ipsum dolor sit amet,</p>
                             <ul class="list-disc list-inside text-sm text-gray-700 mt-1">
                                <li>Lorem ipsum</li>
                             </ul>
                        </div>
                    </div>
                    {{-- Tambahkan item timeline lain di sini --}}
                </div>
            </section>
        </main>
    </div>
</x-karyawan-layout>