<x-admin-layout>
    <x-slot:title>
        rekap
    </x-slot:title>

    <div class="relative pb-24">
        <div class="bg-white p-4 shadow-md sticky top-0 z-20">
            <div class="flex items-center space-x-3">
                <img class="w-10 h-10 rounded-full object-cover" src="https://placehold.co/40x40" alt="Foto Admin">
                <div>
                    <h1 class="text-gray-800 font-bold text-lg">Admin</h1>
                </div>
            </div>
        </div>

        <header class="bg-indigo-950 p-4 pb-16 rounded-t-[2.5rem] shadow-lg relative z-10 -mt-1">

            <div class="flex items-center space-x-3 text-white mb-4">
                <button class="p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h2 class="text-xl font-bold">Rekap Hari Ini</h2>
            </div>
            <div class="mt-6 grid grid-cols-3 gap-3 text-center">
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -right-15 flex h-7 w-7 items-center justify-center rounded-full bg-blue-500 text-white font-bold text-xs border-2 border-indigo-950">
                        50
                    </span>
                    <p class="text-base font-semibold text-gray-700 mt-5">Total Karyawan</p>
                </div>
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -right-15 flex h-7 w-7 items-center justify-center rounded-full bg-green-500 text-white font-bold text-xs border-2 border-indigo-950">
                        50
                    </span>
                    <p class="text-base font-semibold text-gray-700 mt-5">Presensi Tepat Waktu</p>
                </div>
                 <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -right-15 flex h-7 w-7 items-center justify-center rounded-full bg-yellow-500 text-white font-bold text-sm border-2 border-indigo-950">
                        50
                    </span>
                    <p class="text-base font-semibold text-gray-700 mt-5">Terlambat Check-in</p>
                </div>
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -right-15 flex h-7 w-7 items-center justify-center rounded-full bg-yellow-500 text-white font-bold text-sm border-2 border-indigo-950">
                        50
                    </span>
                    <p class="text-base font-semibold text-gray-700 mt-5">Terlambat Check-Out</p>
                </div>
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -right-15 flex h-7 w-7 items-center justify-center rounded-full bg-purple-500 text-white font-bold text-sm border-2 border-indigo-950">
                        50
                    </span>
                    <p class="text-base font-semibold text-gray-700 mt-5">Lupa Check-out</p>
                </div>
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -right-15 flex h-7 w-7 items-center justify-center rounded-full bg-red-500 text-white font-bold text-sm border-2 border-indigo-950">
                        33
                    </span>
                    <p class="text-base font-semibold text-gray-700 mt-5">Tidak Hadir</p>
                </div>
            </div>
        </header>

        <!-- Konten Daftar Presensi -->
        <main class="p-4 -mt-10 relative z-20">
            <div class="bg-white p-4 rounded-2xl shadow-lg">
                <!-- Header Daftar -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-800">Presensi</h3>
                    <button class="flex items-center space-x-1 px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Ekspor</span>
                    </button>
                </div>

                <!-- Tabs & Date Picker -->
                <div class="mb-4 space-y-3">
                    <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
                        <ul class="flex flex-wrap -mb-px">
                            <li class="me-2">
                                <a href="#" class="inline-block p-3 border-b-2 border-blue-600 text-blue-600 rounded-t-lg active" aria-current="page">Harian</a>
                            </li>
                            <li class="me-2">
                                <a href="#" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Mingguan</a>
                            </li>
                            <li class="me-2">
                                <a href="#" class="inline-block p-3 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Bulanan</a>
                            </li>
                        </ul>
                    </div>
                    <div class="relative">
                         <input type="text" value="Sabtu, 28 Februari 2030" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-4 p-2.5" placeholder="Pilih tanggal">
                         <div class="absolute inset-y-0 end-0 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/>
                                <path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                         </div>
                    </div>
                </div>

                <!-- Filter & Search -->
                <div class="space-y-3 mb-4">
                    <div class="flex space-x-2 overflow-x-auto pb-2">
                        <button class="px-3 py-1 text-xs text-gray-700 bg-gray-100 rounded-full whitespace-nowrap">Semua</button>
                        <button class="px-3 py-1 text-xs text-green-700 bg-green-100 rounded-full whitespace-nowrap">Tepat Waktu</button>
                        <button class="px-3 py-1 text-xs text-yellow-700 bg-yellow-100 rounded-full whitespace-nowrap">Terlambat Check-in</button>
                        <button class="px-3 py-1 text-xs text-orange-700 bg-orange-100 rounded-full whitespace-nowrap">Terlambat Check-Out</button>
                         <button class="px-3 py-1 text-xs text-purple-700 bg-purple-100 rounded-full whitespace-nowrap">Lupa Check-Out</button>
                         <button class="px-3 py-1 text-xs text-red-700 bg-red-100 rounded-full whitespace-nowrap">Tidak Hadir</button>
                    </div>
                    <div class="relative">
                        <input type="text" placeholder="Cari nama karyawan" class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>
                </div>

                <!-- Daftar Karyawan (Tampilan Baru Sesuai Figma) -->
                <div class="space-y-3">
                     <div class="bg-white p-3 rounded-xl shadow-md border border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-black rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-sm">Abadi</p>
                                <span class="text-[10px] font-semibold text-purple-600 bg-purple-100 px-2 py-0.5 rounded-full">Lupa Check-Out</span>
                            </div>
                        </div>
                        <a href="#" class="text-xs text-blue-600 font-medium">Detail</a>
                    </div>
                    <div class="bg-white p-3 rounded-xl shadow-md border border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-black rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-sm">Budi</p>
                                <span class="text-[10px] font-semibold text-green-600 bg-green-100 px-2 py-0.5 rounded-full">Tepat Waktu</span>
                            </div>
                        </div>
                         <a href="#" class="text-xs text-blue-600 font-medium">Detail</a>
                    </div>
                    <div class="bg-white p-3 rounded-xl shadow-md border border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-black rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-sm">Hoshi</p>
                                <span class="text-[10px] font-semibold text-yellow-600 bg-yellow-100 px-2 py-0.5 rounded-full">Terlambat Check-in</span>
                            </div>
                        </div>
                         <a href="#" class="text-xs text-blue-600 font-medium">Detail</a>
                    </div>

                </div>
            </div>
        </main>
    </div>
</x-admin-layout>
