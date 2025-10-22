<x-app-layouts>
    <x-slot:title>
        Dashboard Admin
    </x-slot:title>
    <div class="relative pb-24">
        <div class="bg-white p-4 shadow-md sticky top-0 z-20">
            <div class="flex items-center space-x-3">
                <img class="w-10 h-10 rounded-full object-cover" src="img/user.svg" alt="Foto Admin">
                <div>
                    <h1 class="text-gray-800 font-bold text-lg">Admin</h1>
                </div>
            </div>
        </div>

        <div class="bg-indigo-950 p-4 pt-8 -mt-1 rounded-t-3xl relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-3 rounded-xl shadow relative flex flex-col justify-end">
                    <span class="absolute -top-2 -right-15 flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 text-white font-bold text-sm border-2 border-white">
                        50
                    </span>
                    <p class="mt-4 font-semibold text-gray-800">Total Karyawan</p>
                </div>
                <div class="bg-white p-3 rounded-xl shadow relative">
                    <span class="absolute -top-2 -right-15 flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-white font-bold text-sm border-2 border-white">
                        33
                    </span>
                    <p class="mt-4 font-semibold text-gray-800">Hadir Hari ini</p>
                </div>
                <div class="bg-white p-3 rounded-xl shadow relative">
                    <span class="absolute -top-2 -right-15 flex h-8 w-8 items-center justify-center rounded-full bg-yellow-500 text-white font-bold text-sm border-2 border-white">
                        4
                    </span>
                    <p class="mt-4 font-semibold text-gray-800">Terlambat</p>
                </div>
                <div class="bg-white p-3 rounded-xl shadow relative">
                    <span class="absolute -top-2 -right-15 flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white font-bold text-sm border-2 border-white">
                        3
                    </span>
                    <p class="mt-4 font-semibold text-gray-800">Laporan Report Baru</p>
                </div>
            </div>
        </div>
    
    <main class="p-4 -mt-4">
        <div class="bg-white p-5 rounded-3xl shadow-lg">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Menu</h2>
            <div class="grid grid-cols-3 md:grid-cols-2 gap-4 text-center">
                <a href="#" class="flex flex-col items-center justify-center p-3 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                    <svg class="w-8 h-8 mb-1 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7h10M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                    </svg>
                    <span class="text-xs font-semibold text-gray-700">Jadwal</span>
                </a>
                <a href="{{ route('presensi.riwayat') }}" class="flex flex-col items-center justify-center p-3 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                     <svg class="w-8 h-8 mb-1 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0a9 9 0 0 1 18 0Z"/>
                    </svg>
                    <span class="text-xs font-semibold text-gray-700">Riwayat Presensi</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-3 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                    <svg class="w-8 h-8 mb-1 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                    </svg>
                    <span class="text-xs font-semibold text-gray-700">Laporan Report</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center p-3 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                    <svg class="w-8 h-8 mb-1 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                         <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-4-6h8"/>
                         <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3H8a1 1 0 0 0-1 1v2m10-3h2a1 1 0 0 1 1 1v2m-2 14h2a1 1 0 0 0 1-1v-2M4 19h2a1 1 0 0 0 1-1v-2"/>
                         <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 3h2a1 1 0 0 1 1 1v2"/>
                    </svg>
                    <span class="text-xs font-semibold text-gray-700">Log Aktifitas</span>
                </a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</x-app-layout>