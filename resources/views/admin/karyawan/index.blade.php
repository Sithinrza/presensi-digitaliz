<x layouts.app>
    <x-slot:title>
        Karyawan
    </x-slot:title>

        <header class="bg-indigo-950 text-white shadow-lg sticky top-0 z-40">
            <div class="container mx-auto flex items-center justify-between p-4">
                <button type="button" class="p-2 rounded-full hover:bg-white/20 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h1 class="text-lg font-semibold">
                    Data Karyawan
                </h1>
                <a href="{{ route('admin.karyawan.create') }}" class="flex items-center space-x-1 p-2 rounded-lg hover:bg-white/20 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M14 2H6c-1.11 0-2 .89-2 2v16c0 1.11.89 2 2 2h7.81c-.53-.91-.81-1.95-.81-3c0-3.31 2.69-6 6-6c.34 0 .67.03 1 .08V8zm-1 7V3.5L18.5 9zm10 11h-3v3h-2v-3h-3v-2h3v-3h2v3h3z"/>
                    </svg>
                    <span>Add</span>
                </a>
            </div>
        </header>

        <main class="p-4 space-y-3">
            <a href="detailpegawai.html" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-md hover:bg-gray-50 transition active:scale-95">
                <div class="flex items-center space-x-4">
                    <img class="w-10 h-10 rounded-full object-cover bg-gray-200" src="img/user.svg" alt="Foto Profil Abadi">
                    <div>
                        <p class="font-semibold text-gray-800">Abadi</p>
                        <p class="text-sm text-gray-500">Head of Marketing</p>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <a href="#" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-md hover:bg-gray-50 transition active:scale-95">
                <div class="flex items-center space-x-4">
                    <img class="w-10 h-10 rounded-full object-cover bg-gray-200" src="img/user.svg" alt="Foto Profil Ida">
                    <div>
                        <p class="font-semibold text-gray-800">Ida</p>
                        <p class="text-sm text-gray-500">Head of Programmer</p>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <a href="#" class="flex items-center justify-between p-4 bg-white rounded-xl shadow-md hover:bg-gray-50 transition active:scale-95">
                <div class="flex items-center space-x-4">
                    <img class="w-10 h-10 rounded-full object-cover bg-gray-200" src="img/user.svg" alt="Foto Profil Hoshi">
                    <div>
                        <p class="font-semibold text-gray-800">Hoshi</p>
                        <p class="text-sm text-gray-500">Head of UI/UX</p>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </main>
</x layouts.app>
