<header class="bg-white p-4 shadow-sm sticky top-0 z-30">
    <div class="flex items-center justify-between">

        <div class="relative">

            <button onclick="toggleUserMenu()" class="flex items-center space-x-3 focus:outline-none group">
                @php
                    $fotoProfil = 'https://placehold.co/40x40?text=User';
                    if (Auth::user()->karyawan && Auth::user()->karyawan->foto_profil) {
                        $fotoProfil = asset('storage/' . Auth::user()->karyawan->foto_profil);
                    }
                @endphp

                <img class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm transition-transform group-hover:scale-105"
                     src="{{ $fotoProfil }}"
                     alt="Foto Profil">

                     {{-- WRAPPER TEKS: Agar Nama & Divisi jadi Atas-Bawah --}}
                    <div class="flex flex-col items-start justify-center">
                        {{-- Nama User --}}
                        <span class="text-gray-800 font-bold text-sm leading-tight line-clamp-1">
                            {{ Auth::user()->name }}
                        </span>
                        
                        {{-- Divisi --}}
                        <span class="text-xs text-gray-500 font-medium leading-tight">
                            {{ Auth::user()->karyawan?->divisi?->name ?? 'Karyawan' }}
                        </span>
                    </div>
            </button>

            <div id="user-dropdown" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 transform origin-top-left transition-all duration-200">
                {{-- <div class="px-4 py-2 border-b border-gray-100 md:hidden">
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                </div> --}}
                <div class="border-t border-gray-100 my-1"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar / Logout</span>
                    </button>
                </form>
            </div>

        </div>

    </div>
</header>

<script>
    function toggleUserMenu() {
        const menu = document.getElementById('user-dropdown');
        const chevron = document.getElementById('chevron-icon');

        menu.classList.toggle('hidden');

        if (menu.classList.contains('hidden')) {
            chevron.classList.remove('rotate-180');
        } else {
            chevron.classList.add('rotate-180');
        }
    }

    document.addEventListener('click', function(event) {
        const menu = document.getElementById('user-dropdown');
        const button = document.querySelector('button[onclick="toggleUserMenu()"]');

        // Jika yang diklik BUKAN tombol DAN BUKAN menu dropdown
        if (!button.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
            document.getElementById('chevron-icon').classList.remove('rotate-180');
        }
    });
</script>
