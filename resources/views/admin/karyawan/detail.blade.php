<x-admin-layout>
    <x-slot:title>
        Detail {{ $karyawan->nama_lengkap ?? 'Karyawan' }}
    </x-slot:title>

    <main class="bg-white min-h-screen">
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-lg overflow-hidden">

                <header class="bg-indigo-950 text-white p-4 flex items-center justify-between">
                    <h2 class="font-semibold">Detail Data Karyawan</h2>
                    <button type="button" class="p-1 rounded-full hover:bg-white/20">
                        <a href="{{ route('admin.karyawan.index') }}">
                            {{-- Ganti ikon panah bawah menjadi 'x' untuk menutup modal --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </button>
                </header>

                <main class="p-6 max-h-[80vh] overflow-y-auto"> {{-- Buat konten bisa di-scroll --}}
                    <div class="flex flex-col items-center text-center mb-6">
                        <div class="p-2 bg-gray-200 rounded-full mb-3">
                            @if ($karyawan->user?->profile_photo_url)
                                <img src="{{ $karyawan->user->profile_photo_url }}" alt="Foto" class="w-16 h-16 rounded-full object-cover">
                            @else
                                <svg class="h-8 w-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex items-center space-x-1">
                            <h3 class="text-xl font-bold text-gray-800">{{ $karyawan->nama_lengkap ?? '-' }}</h3>
                            @if ($karyawan->status_karyawan === 'Aktif')
                                <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500">{{ $karyawan->jabatan?->name ?? 'Jabatan Tidak Diketahui' }}</p>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500 col-span-1">Nama Lengkap</span>
                            <span class="text-gray-800 col-span-2">: {{ $karyawan->nama_lengkap ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500 col-span-1">NIP</span>
                            <span class="text-gray-800 col-span-2">: {{ $karyawan->nip ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500 col-span-1">Email</span>
                            <span class="text-gray-800 col-span-2">: {{ $karyawan->user?->email ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500 col-span-1">Jenis Kelamin</span>
                            <span class="text-gray-800 col-span-2">: {{ $karyawan->jenis_kelamin ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500 col-span-1">Agama</span>
                            <span class="text-gray-800 col-span-2">: {{ $karyawan->agama?->name ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500 col-span-1">Tempat Lahir</span>
                            <span class="text-gray-800 col-span-2">: {{ $karyawan->tempat_lahir ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500 col-span-1">Tanggal Lahir</span>
                            <span class="text-gray-800 col-span-2">: {{ $karyawan->tanggal_lahir ? \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('d - m - Y') : '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500 col-span-1">Pendidikan</span>
                            <span class="text-gray-800 col-span-2">: {{ $karyawan->pendidikanTerakhir?->name ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500 col-span-1">Alamat</span>
                            <span class="text-gray-800 col-span-2">: {{ $karyawan->alamat ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500 col-span-1">Nomor Telp</span>
                            <span class="text-gray-800 col-span-2">: {{ $karyawan->no_telepon ?? '-' }}</span>
                        </div>
                         <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500 col-span-1">Bergabung</span>
                            <span class="text-gray-800 col-span-2">: {{ $karyawan->tanggal_bergabung ? \Carbon\Carbon::parse($karyawan->tanggal_bergabung)->format('d M Y') : '-' }}</span>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="mt-8 flex items-center justify-center space-x-4">
                        <a href="{{ route('admin.karyawan.edit', $karyawan->id) }}" class="flex-1">
                            <button class="w-full flex items-center space-x-2 justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                                 <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                 <span>Edit</span>
                            </button>
                        </a>
                        {{-- Ganti onsubmit agar memanggil fungsi JS dari app.js --}}
                        <form id="delete-form-{{ $karyawan->id }}" action="{{ route('admin.karyawan.destroy', $karyawan->id) }}" method="POST" class="flex-1" onsubmit="event.preventDefault(); confirmDelete('delete-form-{{ $karyawan->id }}', '{{ $karyawan->nama_lengkap }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" {{-- Ganti type="button" menjadi "submit" agar form bekerja --}}
                                    class="w-full flex items-center space-x-2 justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>
                </main>
            </div>
        </div>
    </main> {{-- Penutup main.bg-white --}}

    @push('scripts')
    <script>
        function confirmDelete(formId, nama) {
            Swal.fire({
                title: `Hapus ${nama}?`,
                text: "Data ini akan dihapus secara permanen dan tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
        </script>
    {{-- Pastikan SweetAlert2 & fungsi confirmDelete dimuat di layout utama (app.blade.php atau app.js) --}}
    @endpush

</x-admin-layout>

