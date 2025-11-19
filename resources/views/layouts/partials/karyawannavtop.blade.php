
<header class="bg-white p-4 shadow-sm sticky top-0 z-20">
    <div class="flex items-center space-x-3">
        <img class="w-10 h-10 rounded-full object-cover" src="https://placehold.co/40x40" alt="Foto Profil Karyawan">
        <div>
            <h1 class="text-gray-800 font-bold text-lg">{{ Auth::user()->name }}</h1>
        </div>
    </div>
</header>
