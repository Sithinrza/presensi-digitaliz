<x-karyawan-layout>
    <x-slot:title>
        User Detail
    </x-slot:title>
    {{-- admin.profile.detail.blade.php --}}

    @if (session('success'))
        <div class="p-3 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-3 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-3 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            Gagal menyimpan! Terdapat kesalahan validasi di bawah.
        </div>
    @endif
    <div class="relative flex-grow pb-10">
        <header class="bg-indigo-950 p-4 pb-20 rounded-t-[3rem] shadow-lg relative z-10 text-center text-white">
            <div class="relative inline-block mb-3">
                <img class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-white shadow-lg" src="https://placehold.co/100x100" alt="Foto Profil">
                <div class="profile-verified-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <h1 class="text-xl font-bold">{{ $karyawan->nama_lengkap ?? Auth::user()->name }}</h1>
            <p class="text-sm text-indigo-300">
                {{ $karyawan->jabatan?->name ?? 'Jabatan' }} -
                {{ $karyawan->posisi?->name ?? 'Posisi' }}
            </p>
        </header>

        <!-- Konten Detail Data Pegawai -->
        <main class="p-4 -mt-12 relative z-20 space-y-5">
            <form action="{{ route('karyawan.profile.update', $karyawan->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

                       <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-base font-bold text-gray-800 mb-5">Data Pegawai</h2>
                <div class="space-y-4">
                    <!-- ID Pegawai -->
                    {{-- <div>
                        <label class="block mb-0.5 text-xs font-medium text-gray-500">ID Pegawai</label>
                        <input type="text" value="123456789" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-default" readonly>
                    </div> --}}

                    {{-- catatan
                    tambah akun email & password(sementara readonly/disable aja dlu), tgl bergabung(readonly), status_karyawan(readonly), divisi(readonly) --}}
                    <!-- NIP -->
                    <div>
                        <label class="block mb-0.5 text-xs font-medium text-gray-500">NIP</label>
                        <input type="text" value="{{ $karyawan->nip ?? 'NIP' }}" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-default" readonly>
                    </div>
                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block mb-0.5 text-xs font-medium text-gray-500">Tempat Lahir</label>
                        <input type="text" value="Banjarmasin" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-default" readonly>
                    </div>
                    <!-- Tanggal Lahir -->
                    <div class="relative">
                         <label class="block mb-0.5 text-xs font-medium text-gray-500">Tanggal Lahir</label>
                         <input type="text" value="08-01-2005" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 cursor-default" readonly>
                         <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none top-5">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/>
                                <path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                         </div>
                    </div>
                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block mb-0.5 text-xs font-medium text-gray-500">Jenis Kelamin</label>
                        <input type="text" value="Laki-Laki" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-default" readonly>
                    </div>
                    <!-- Agama -->
                    <div>
                        <label class="block mb-0.5 text-xs font-medium text-gray-500">Agama</label>
                        {{-- Select dibuat terlihat seperti input readonly --}}
                        <div class="relative">
                             <select class="appearance-none bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-default" disabled>
                                <option>Kristen Protestan</option>
                            </select>
                             <div class="absolute inset-y-0 end-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                             </div>
                        </div>
                    </div>
                    <!-- Alamat -->
                    <div>
                        <label class="block mb-0.5 text-xs font-medium text-gray-500">Alamat</label>
                        <textarea rows="3" name="alamat" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 cursor-default">
                            {{ $karyawan->alamat ?? 'alamat' }}
                        </textarea>
                    </div>
                    <!-- Pendidikan Terakhir -->
                    <div>
                        <label class="block mb-0.5 text-xs font-medium text-gray-500">Pendidikan Terakhir</label>
                         <div class="relative">
                            <select name="pendidikan_terakhir_id" class="appearance-none bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-default">
                               @foreach($pendidikans as $pendidikan)
                                    <option value="{{ $pendidikan->id }}"
                                        @if(old('pendidikan_terakhir_id', $karyawan->pendidikan_terakhir_id) == $pendidikan->id)
                                            selected
                                        @endif
                                    >
                                        {{ $pendidikan->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pendidikan_terakhir_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                             <div class="absolute inset-y-0 end-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                             </div>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-0.5 text-xs font-medium text-gray-500">No Telepon</label>
                        <input type="tel" value="{{ $karyawan->no_telepon ?? 'No_Telp' }}" name="no_telepon" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-default">
                    </div>
                </div>
            </div>

             <!-- Tombol Edit Data -->
            <div class="px-0 pt-4"> <!-- Padding atas untuk jarak -->
                <button class="w-full text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-3 text-center shadow-md">
                    Edit Data
                </button>
            </div>
            </form>

        </main>
    </div>
</x-karyawan-layout>
