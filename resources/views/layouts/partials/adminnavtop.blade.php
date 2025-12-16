<header class="bg-white p-4 shadow-sm sticky top-0 z-20">
    <div class="flex items-center justify-between"> {{-- Ubah layout jadi justify-between --}}
        
        {{-- BAGIAN KIRI: FOTO & NAMA --}}
        <div class="flex items-center space-x-3">
            @php
                // Logika Foto Profil (Sama seperti sebelumnya)
                $fotoProfil = 'https://placehold.co/40x40?text=User'; 
                if (Auth::user()->karyawan && Auth::user()->karyawan->foto_profil) {
                    $fotoProfil = asset('storage/' . Auth::user()->karyawan->foto_profil);
                }
            @endphp
            
            <img class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm" 
                 src="{{ $fotoProfil }}" 
                 alt="Foto Profil">
                 
            <div>
                <h1 class="text-gray-800 font-bold text-lg leading-tight">{{ Auth::user()->name }}</h1>
            </div>
        </div>

        {{-- BAGIAN KANAN: TOMBOL LOGOUT --}}
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors duration-200 group"
                        title="Keluar / Logout">
                    
                    {{-- Ikon Logout (Pintu Keluar) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>

    </div>
</header>