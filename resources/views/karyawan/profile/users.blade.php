<x-karyawan-layout>
    <x-slot:title>
        user
    </x-slot:title>
    <div class="relative min-h-screen flex flex-col">
        <header class="bg-indigo-950 p-4 pb-16 rounded-t-[2.5rem] shadow-lg relative z-10 text-center text-white">
            <div class="relative inline-block mb-2">
                <img class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-white shadow-lg" src="https://placehold.co/100x100" alt="Foto Profil">
                <button class="profile-edit-button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </button>
            </div>
            <h1 class="text-xl font-bold">{{ Auth::user()->name }}</h1>
            <p class="text-sm text-indigo-300">
                {{ optional(Auth::user()->roles->first())->name }} 
                - 
                {{ data_get(Auth::user(), 'karyawan.jabatan.name') }}
            </p>
        </header>

        <main class="flex-grow p-4 -mt-8 relative z-20 space-y-6">
            <div class="bg-white p-5 rounded-2xl shadow-lg">
                <h2 class="text-base font-semibold text-gray-500 mb-3 uppercase">Akun</h2>
                <a href="{{ route('karyawan.profile.detail') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-base font-medium text-gray-700">Informasi Akun</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-lg">
                 <h2 class="text-base font-semibold text-gray-500 mb-3 uppercase">Pengaturan</h2>
                 <div class="space-y-2">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-8">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="text-base font-medium text-gray-700">Notifikasi</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                          <input type="checkbox" value="" class="sr-only peer" checked>
                          <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <a href="#" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 32 32">
                                <path fill="#000" d="M21 2a8.998 8.998 0 0 0-8.612 11.612L2 24v6h6l10.388-10.388A9 9 0 1 0 21 2m0 16a7 7 0 0 1-2.032-.302l-1.147-.348l-.847.847l-3.181 3.181L12.414 20L11 21.414l1.379 1.379l-1.586 1.586L9.414 23L8 24.414l1.379 1.379L7.172 28H4v-3.172l9.802-9.802l.848-.847l-.348-1.147A7 7 0 1 1 21 18"/><circle cx="22" cy="10" r="2" fill="#000"/>
                            </svg>
                            <span class="text-base font-medium text-gray-700">Ganti Kata Sandi</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                 </div>
            </div>

           <div class="mt-auto p-6">
            {{-- Form ini akan mengirimkan permintaan POST ke rute 'logoutt' --}}
            <form method="POST" action="{{ route('logoutt') }}">

                {{-- WAJIB: Menyertakan CSRF token untuk keamanan --}}
                @csrf

                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 text-sm font-medium text-red-600 bg-red-100 rounded-lg hover:bg-red-200 focus:ring-4 focus:ring-red-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="m17 8l-1.41 1.41L17.17 11H9v2h8.17l-1.58 1.58L17 16l4-4zM5 5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h7v-2H5z"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
        </main>
    </div>
</x-karyawan-layout>
