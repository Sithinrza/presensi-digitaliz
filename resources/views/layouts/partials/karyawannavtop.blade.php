
<header class="bg-white p-4 shadow-sm sticky top-0 z-20">
    <div class="flex items-center space-x-3">
        @php
            // 1. Set gambar default (jika tidak ada foto)
            $fotoProfil = 'https://placehold.co/40x40?text=User'; 
            
            // 2. Cek apakah user punya data karyawan DAN punya 'foto_profil'
            if (Auth::user()->karyawan && Auth::user()->karyawan->foto_profil) {
                // 3. Gunakan foto dari storage
                $fotoProfil = asset('storage/' . Auth::user()->karyawan->foto_profil);
            }
        @endphp
        <img class="w-10 h-10 rounded-full object-cover border border-gray-200" 
             src="{{ $fotoProfil }}" 
             alt="Foto Profil Karyawan">
        <div>
            <h1 class="text-gray-800 font-bold text-lg">{{ Auth::user()->name }}</h1>
        </div>
    </div>
</header>
