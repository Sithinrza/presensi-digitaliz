<x-admin-layout>
    <x-slot:title>
        Dashboard Admin
    </x-slot:title>
    <div class="relative pb-24">
        <div class="bg-white p-4 shadow-sm sticky top-0 z-20">
            <div class="flex items-center space-x-3">
                <img class="w-10 h-10 rounded-full object-cover" src="https://placehold.co/40x40" alt="Foto Admin">
                <div>
                    <h1 class="text-gray-800 font-bold text-lg">Admin</h1>
                </div>
            </div>
        </div>

        <div class="bg-indigo-950 p-4 pt-8 pb-12 -mt-1 rounded-t-[3rem] relative z-10">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-4 h-20 rounded-xl shadow relative flex items-end">
                    <span class="absolute -top-3 left-2 flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 text-white font-bold text-sm border-2 border-indigo-950">
                        {{ $totalKaryawan }}
                    </span>
                    <p class="font-semibold text-gray-800">Total Karyawan</p>
                </div>
                <div class="bg-white p-4 h-20 rounded-xl shadow relative flex items-end">
                    <span class="absolute -top-3 left-2 flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-white font-bold text-sm border-2 border-indigo-950">
                        33
                    </span>
                    <p class="font-semibold text-gray-800">Hadir Hari ini</p>
                </div>
                <div class="bg-white p-4 h-20 rounded-xl shadow relative flex items-end">
                    <span class="absolute -top-3 left-2 flex h-8 w-8 items-center justify-center rounded-full bg-yellow-500 text-white font-bold text-sm border-2 border-indigo-950">
                        4
                    </span>
                    <p class="font-semibold text-gray-800">Terlambat</p>
                </div>
                <div class="bg-white p-4 h-20 rounded-xl shadow relative flex items-end">
                    <span class="absolute -top-3 left-2 flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-white font-bold text-sm border-2 border-indigo-950">
                        3
                    </span>
                    <p class="font-semibold text-gray-800">Laporan Report Baru</p>
                </div>
            </div>
        </div>

        <div class="p-4 -mt-8 relative z-10">
             <div class="bg-white p-5 rounded-2xl shadow-lg">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Menu</h2>

                <div class="grid grid-cols-3 md:grid-cols-4 gap-4 text-center">
                    <a href="{{ route('admin.agenda.index') }}" class="flex flex-col items-center justify-center p-3 bg-gray-100 rounded-xl hover:bg-gray-200 transition aspect-square">
                        <svg class="w-8 h-8 mb-1 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7h10M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                        </svg>
                        <span class="text-xs font-semibold text-gray-700 mt-1">Agenda</span>
                    </a>
                    <a href="{{ route('admin.presensi.index') }}" class="flex flex-col items-center justify-center p-3 bg-gray-100 rounded-xl hover:bg-gray-200 transition aspect-square">
                         <svg class="w-8 h-8 mb-1 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0a9 9 0 0 1 18 0Z"/>
                        </svg>
                        <span class="text-xs font-semibold text-gray-700 mt-1">Riwayat Presensi</span>
                    </a>
                    <a href="#" class="flex flex-col items-center justify-center p-3 bg-gray-100 rounded-xl hover:bg-gray-200 transition aspect-square">
                        <svg class="w-8 h-8 mb-1 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                        </svg>
                        <span class="text-xs font-semibold text-gray-700 mt-1">Laporan Report</span>
                    </a>
                    <a href="{{ route('admin.log.index') }}" class="flex flex-col items-center justify-center p-3 bg-gray-100 rounded-xl hover:bg-gray-200 transition aspect-square">
                        <svg class="w-8 h-8 mb-1 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M9.255 14.005c.966 0 1.75.783 1.75 1.75v3.498a1.75 1.75 0 0 1-1.75 1.75H3.75A1.75 1.75 0 0 1 2 19.254v-3.498c0-.967.784-1.75 1.75-1.75zm10.995 0c.966 0 1.75.783 1.75 1.75v3.498a1.75 1.75 0 0 1-1.75 1.75h-5.505a1.75 1.75 0 0 1-1.75-1.75v-3.498c0-.967.784-1.75 1.75-1.75zm-10.995 1.5H3.75a.25.25 0 0 0-.25.25v3.498c0 .139.112.25.25.25h5.505a.25.25 0 0 0 .25-.25v-3.498a.25.25 0 0 0-.25-.25m10.995 0h-5.505a.25.25 0 0 0-.25.25v3.498c0 .139.112.25.25.25h5.505a.25.25 0 0 0 .25-.25v-3.498a.25.25 0 0 0-.25-.25M20.25 3c.966 0 1.75.784 1.75 1.75v5.5A1.75 1.75 0 0 1 20.25 12H3.75A1.75 1.75 0 0 1 2 10.25v-5.5a1.75 1.75 0 0 1 1.606-1.744L3.75 3zm0 1.5H3.75l-.057.007a.25.25 0 0 0-.193.243v5.5c0 .138.112.25.25.25h16.5a.25.25 0 0 0 .25-.25v-5.5a.25.25 0 0 0-.25-.25"/>
                        </svg>
                        <span class="text-xs font-semibold text-gray-700 mt-1">Log Aktifitas</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
