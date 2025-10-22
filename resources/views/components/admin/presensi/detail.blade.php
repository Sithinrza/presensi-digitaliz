<x-app-layout>
    <x-slot:title>
        detail
    </x-slot:title>
    <header class="bg-indigo-950 text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto flex items-center space-x-3 p-4">
            <button type="button" class="p-1">
                <a href="riwayat_presensi.html">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </button>
            <div>
                 <h1 class="text-lg font-semibold">
                    Detail Presensi
                </h1>
                <p class="text-xs text-indigo-300">Abadi - Sabtu 28 Februari 2030</p>
            </div>
        </div>
    </header>

    <main class="p-4 space-y-6 md:max-w-lg md:mx-auto">
        <section>
            <h2 class="text-md font-semibold text-gray-700 mb-2">Bukti Foto Selfie</h2>
            <div class="bg-white rounded-xl shadow-md overflow-hidden p-4">
                <img src="https://placehold.co/600x400/E81414/white?text=Foto+Selfie" alt="Bukti Foto Selfie" class="w-full h-auto object-cover rounded-lg">
            </div>
        </section>

        <section>
             <h2 class="text-md font-semibold text-gray-700 mb-2">Peta Lokasi</h2>
             <div class="bg-white rounded-xl shadow-md overflow-hidden p-4">
                <img src="https://placehold.co/600x300/cccccc/ffffff?text=Peta+Lokasi" alt="Peta Lokasi" class="w-full h-auto object-cover rounded-lg border">
             </div>
        </section>

        <section>
             <h2 class="text-md font-semibold text-gray-700 mb-2">Rincian Kehadiran</h2>
             <div class="bg-white rounded-xl shadow-md p-5">
                 <div class="flex items-center justify-between border-b pb-3 mb-3">
                     <span class="text-sm text-gray-500">Status Hari Ini</span>
                     <span class="text-sm font-medium text-green-600 bg-green-100 px-3 py-1 rounded-full">Tepat Waktu</span>
                 </div>
                 <div class="flex items-center justify-between mb-1">
                     <span class="text-sm text-gray-500">Check-In</span>
                     <span class="text-sm font-semibold text-gray-800">08:00</span>
                 </div>
                  <div class="flex items-center justify-between">
                     <span class="text-sm text-gray-500">Check-Out</span>
                     <span class="text-sm font-semibold text-gray-800">17:00</span> <!-- Asumsi checkout pukul 17:00 -->
                 </div>
             </div>
        </section>

    </main>
</x-app-layout>