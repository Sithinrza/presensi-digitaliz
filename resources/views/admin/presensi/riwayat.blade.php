<x-admin-layout>
    <x-slot:title>
        Presensi Karyawan
    </x-slot:title>

    <div class="relative pb-24">

        <header class="bg-indigo-950 p-4 pb-16 rounded-t-[2.5rem] shadow-lg relative z-10 -mt-1">
            <div class="flex items-center space-x-3 text-white mb-4">
                <a href="{{ route('admin.dashboard') }}"> 
                    <button class="p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </a>
                <h2 class="text-xl font-bold">Presensi Karyawan</h2>
            </div>
            <div class="mt-6 grid grid-cols-3 gap-3 text-center">
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-blue-500 text-white font-bold text-xs border-2 border-indigo-950">
                        50
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Total Karyawan</p>
                </div>
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-green-500 text-white font-bold text-xs border-2 border-indigo-950">
                        50 
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Presensi Tepat Waktu</p>
                </div>
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-yellow-500 text-white font-bold text-xs border-2 border-indigo-950">
                        50
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Terlambat Check-in</p>
                </div>
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-yellow-500 text-white font-bold text-xs border-2 border-indigo-950">
                        50
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Terlambat Check-Out</p>
                </div>
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-purple-500 text-white font-bold text-xs border-2 border-indigo-950">
                        50
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Lupa Check-out</p>
                </div>
                <div class="bg-white p-2 rounded-xl shadow relative">
                    <span class="absolute -top-2 -left-2 flex h-7 w-7 items-center justify-center rounded-full bg-red-500 text-white font-bold text-xs border-2 border-indigo-950">
                        33
                    </span>
                    <p class="text-xs font-semibold text-gray-700 mt-5">Tidak Hadir</p>
                </div>
            </div>
        </header>

        <main class="p-4 -mt-10 relative z-20">
            <div class="bg-white p-4 rounded-2xl shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-gray-800">Status Presensi</h3>
                        <p class="text-xs text-gray-500">Sabtu, 26 Februari 2020</p>
                    </div>
                    <a href="{{ route('admin.presensi.rekap') }}">
                        <button class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">
                            Rekap
                        </button>
                    </a>
                </div>
                <div class="space-y-3 mb-4">
                    <div class="flex space-x-2 overflow-x-auto pb-2">
                        <button class="px-3 py-1 text-xs text-gray-700 bg-gray-100 rounded-full whitespace-nowrap">Semua</button>
                        <button class="px-3 py-1 text-xs text-green-700 bg-green-100 rounded-full whitespace-nowrap">Tepat Waktu</button>
                        <button class="px-3 py-1 text-xs text-yellow-700 bg-yellow-100 rounded-full whitespace-nowrap">Terlambat Check-in</button>
                        <button class="px-3 py-1 text-xs text-orange-700 bg-orange-100 rounded-full whitespace-nowrap">Terlambat Check-Out</button>
                        <button class="px-3 py-1 text-xs text-purple-700 bg-purple-100 rounded-full whitespace-nowrap">Lupa Check-Out</button>
                        <button class="px-3 py-1 text-xs text-red-700 bg-red-100 rounded-full whitespace-nowrap">Tidak Presensi</button>
                    </div>
                    <div class="relative">
                        <input type="text" placeholder="Cari nama karyawan" class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 space-y-3">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="shrink-0 w-12 h-12 bg-black rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Ida</p>
                                    <span class="text-xs font-semibold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">Tidak Hadir</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                <span>Jl. Tatah Belayung</span>
                            </div>
                            <button class="flex items-center space-x-2 text-sm text-gray-600 font-medium px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                    <path d="M5 7h1a2 2 0 0 0 2-2a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2"/><path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0-6 0"/></g>
                                </svg>
                                <span>Detail</span>
                            </button>
                        </div>
                    </div>

                     <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 space-y-3">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="shrink-0 w-12 h-12 bg-black rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Abadi</p>
                                    <span class="text-xs font-semibold text-purple-600 bg-purple-100 px-2 py-0.5 rounded-full">Lupa Check-Out</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-500">Check-Out</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                             <div class="flex items-center space-x-2 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                <span>Jl. Tatah Belayung</span>
                            </div>
                            <button class="flex items-center space-x-2 text-sm text-gray-600 font-medium px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                    <path d="M5 7h1a2 2 0 0 0 2-2a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2"/><path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0-6 0"/></g>
                                </svg>
                                <span>Detail</span>
                            </button>
                        </div>
                    </div>

                     <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 space-y-3">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="shrink-0 w-12 h-12 bg-black rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Lala</p>
                                    <span class="text-xs font-semibold text-yellow-600 bg-yellow-100 px-2 py-0.5 rounded-full">Terlambat Check-Out</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-800">19.43</p>
                                <p class="text-xs text-gray-500">Check-Out</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                <span>Jl. Tatah Belayung</span>
                            </div>
                            <button class="flex items-center space-x-2 text-sm text-gray-600 font-medium px-3 py-1 bg-gray-200 rounded-lg hover:bg-gray-300">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                    <path d="M5 7h1a2 2 0 0 0 2-2a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2"/><path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0-6 0"/></g>
                                </svg>
                                <span>Detail</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</x-admin-layout>
