<x-admin-layout>
    <x-slot:title>
        Profil Saya
    </x-slot:title>

    <div class="relative min-h-screen flex flex-col bg-gray-50">
        {{-- Header Profil --}}
        <header class="bg-indigo-950 p-4 pb-16 rounded-t-[2.5rem] shadow-lg relative z-10 text-center text-white">

            <div class="relative inline-block mb-2 group">
                {{-- 1. LOGIKA STATUS FOTO --}}
                @php
                    $karyawan = Auth::user()->karyawan;
                    $hasFoto = $karyawan && $karyawan->foto_profil;
                    $fotoUrl = $hasFoto ? asset('storage/' . $karyawan->foto_profil) : 'https://placehold.co/100x100?text=User';
                @endphp

                <img class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-white shadow-lg transition-transform transform group-hover:scale-105"
                     src="{{ $fotoUrl }}"
                     alt="Foto Profil">

                {{-- 2. TOMBOL INTERAKTIF --}}
                <button type="button"
                        onclick="handleProfilePictureClick({{ $hasFoto ? 'true' : 'false' }})"
                        class="absolute bottom-0 right-0 bg-white p-2 rounded-full shadow-md border border-gray-200 hover:bg-gray-100 transition text-gray-600 hover:text-indigo-600 cursor-pointer"
                        title="Kelola Foto Profil">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </button>

                {{-- 3. FORM UPDATE (Hidden) --}}
                <form id="form-ganti-foto" action="{{ route('profile.foto.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file"
                        name="foto_profil"
                        id="input-foto"
                        accept="image/jpeg,image/png,image/jpg"
                        onchange="this.form.submit()">
                </form>


                {{-- 4. FORM DELETE (Hidden) --}}
                <form id="form-hapus-foto" action="{{ route('profile.foto.delete') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>

            <h1 class="text-xl font-bold mt-2">{{ Auth::user()->name }}</h1>

            <div class="flex items-center justify-center gap-2 text-sm text-indigo-200 mt-1">
                <span class="bg-indigo-900/50 px-3 py-0.5 rounded-full border border-indigo-800">
                    {{ optional(Auth::user()->roles->first())->name ?? 'Karyawan' }}
                </span>
            </div>
            <p class="text-sm text-indigo-300 mt-1">
                {{ data_get(Auth::user(), 'karyawan.jabatan.name', 'Belum ada jabatan') }}
            </p>
        </header>

        {{-- Konten Menu --}}
        <main class="flex-grow p-4 -mt-8 relative z-20 space-y-6">

            {{-- Alert Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                    <strong class="font-bold">Berhasil!</strong> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                    <strong class="font-bold">Gagal!</strong> {{ session('error') }}
                </div>
            @endif

            <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100">
                <h2 class="text-xs font-bold text-gray-400 mb-4 uppercase tracking-wider">Akun</h2>
                <a href="{{ route('admin.profile.detail') }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 transition group border border-transparent hover:border-indigo-100">
                    <div class="flex items-center space-x-4">
                        <div class="p-2 bg-white rounded-lg shadow-sm group-hover:bg-indigo-100 text-gray-500 group-hover:text-indigo-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-base font-semibold text-gray-700 group-hover:text-indigo-800">Informasi Akun</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100">
                 <h2 class="text-xs font-bold text-gray-400 mb-4 uppercase tracking-wider">Pengaturan</h2>
                 <div class="space-y-3">
                    {{-- Notifikasi --}}
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-transparent">
                        <div class="flex items-center space-x-4">
                            <div class="p-2 bg-white rounded-lg shadow-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <span class="text-base font-semibold text-gray-700">Notifikasi</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                          <input type="checkbox" value="" class="sr-only peer" checked>
                          <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    {{-- Ganti Password --}}
                    <a href="#" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 transition group border border-transparent hover:border-indigo-100">
                        <div class="flex items-center space-x-4">
                            <div class="p-2 bg-white rounded-lg shadow-sm text-gray-500 group-hover:text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 32 32" class="fill-current">
                                    <path d="M21 2a8.998 8.998 0 0 0-8.612 11.612L2 24v6h6l10.388-10.388A9 9 0 1 0 21 2m0 16a7 7 0 0 1-2.032-.302l-1.147-.348l-.847.847l-3.181 3.181L12.414 20L11 21.414l1.379 1.379l-1.586 1.586L9.414 23L8 24.414l1.379 1.379L7.172 28H4v-3.172l9.802-9.802l.848-.847l-.348-1.147A7 7 0 1 1 21 18"/><circle cx="22" cy="10" r="2"/>
                                </svg>
                            </div>
                            <span class="text-base font-semibold text-gray-700 group-hover:text-indigo-800">Ganti Kata Sandi</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                 </div>
            </div>

           <div class="mt-auto pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3.5 text-sm font-bold text-red-600 bg-red-50 rounded-xl hover:bg-red-100 focus:ring-4 focus:ring-red-200 transition border border-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" class="text-red-500">
                        <path fill="currentColor" d="m17 8l-1.41 1.41L17.17 11H9v2h8.17l-1.58 1.58L17 16l4-4zM5 5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h7v-2H5z"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
        </main>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL CUSTOM TAILWIND (Menu Pilihan) --}}
    {{-- ========================================== --}}
    <div id="modal-foto-profil" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xs">

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Kelola Foto Profil</h3>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-4 py-4 sm:px-6 space-y-3">
                        {{-- Tombol Ubah --}}
                        <button type="button" onclick="triggerGantiFoto()" class="w-full flex items-center justify-between p-3 rounded-lg border border-indigo-100 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="font-medium">Ubah Foto</span>
                            </div>
                            <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>

                        {{-- Tombol Hapus --}}
                        <button type="button" onclick="triggerHapusFoto()" class="w-full flex items-center justify-between p-3 rounded-lg border border-red-100 bg-red-50 text-red-700 hover:bg-red-100 transition">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="font-medium">Hapus Foto</span>
                            </div>
                            <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>

                        {{-- Tombol Batal --}}
                        <button type="button" onclick="closeModal()" class="w-full mt-2 inline-flex justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- TAMBAHKAN LIBRARY SWEETALERT --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // --- Fungsi Modal & Logika ---

            const modal = document.getElementById('modal-foto-profil');

            function openModal() {
                modal.classList.remove('hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
            }

            // Fungsi Utama yg dipanggil tombol pensil
            function handleProfilePictureClick(hasFoto) {
                if (hasFoto) {
                    openModal();
                } else {
                    document.getElementById('input-foto').click();
                }
            }

            // Aksi Ubah Foto (dari dalam Modal)
            function triggerGantiFoto() {
                closeModal();
                document.getElementById('input-foto').click();
            }

            // Aksi Hapus Foto -> PAKE SWEETALERT DI SINI
            function triggerHapusFoto() {
                // 1. Tutup modal pilihan dulu supaya bersih
                closeModal();

                // 2. Munculkan SweetAlert Konfirmasi
                Swal.fire({
                    title: 'Hapus Foto Profil?',
                    text: "Foto Anda akan dihapus secara permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Merah
                    cancelButtonColor: '#6b7280', // Abu-abu
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika user klik Ya, submit form
                        document.getElementById('form-hapus-foto').submit();
                    }
                });
            }
        </script>
    @endpush
</x-admin-layout>
