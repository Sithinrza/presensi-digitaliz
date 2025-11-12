<x-admin-layout>
    <x-slot:title>
        Tambah
    </x-slot:title>
    <header class="bg-indigo-950 text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto flex items-center p-4">
            <button type="button" class="p-2">
                <a href="{{ route('admin.karyawan.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </button>
            <h1 class="text-lg font-semibold flex-grow text-center">
                Tambah Data Karyawan
            </h1>

        </div>
    </header>
    <main class="p-4 space-y-6 pb-24">

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Gagal Menyimpan!</strong>
                <span class="block sm:inline">Periksa kembali kesalahan input Anda.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form id="tambah-karyawan-form" action="{{ route('admin.karyawan.store') }}" method="POST" data-confirm="Apakah Anda yakin ingin menyimpan data ini?" class="space-y-6">
            @csrf
            <div class="bg-white p-5 rounded-xl shadow-md">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Data Pegawai</h2>
                <div class="space-y-4">
                    {{-- <div>
                        <label for="id" class="block mb-1 text-sm font-medium text-gray-500">ID Pegawai</label>
                        <input type="text" name="id" id="id" value="" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    </div> --}}
                    <div>
                        <label for="nip" class="block mb-1 text-sm font-medium text-gray-500">NIP</label>
                        <input type="text" name="nip" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan NIP" required>
                    </div>
                    <div>
                        <label for="nama_lengkap" class="block mb-1 text-sm font-medium text-gray-500">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan Nama" required>
                    </div>
                    <div>
                        <label for="tempat_lahir" class="block mb-1 text-sm font-medium text-gray-500">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan Tempat Lahir" required>
                    </div>
                    <div class="relative">
                        <label for="tanggal_lahir" class="block mb-1 text-sm font-medium text-gray-500">Tanggal Lahir</label>
                        <input
                            type="text"
                            id="tanggal_lahir"
                            name="tanggal_lahir"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Pilih Tanggal Lahir"
                            required
                        >
                    </div>
                    <div>
                        <label for="agama" class="block mb-1 text-sm font-medium text-gray-500">Agama</label>
                        <select id="agama" name="agama_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="" disabled selected>Pilih Agama</option>
                            @foreach($agamas as $agama)
                            <option value="{{ $agama->id }}" {{ old('agama_id') == $agama->id ? 'selected' : '' }}>
                                {{ $agama->name }}
                            </option>
                             @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-500">Jenis Kelamin</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <input id="perempuan" type="radio" value="Perempuan" name="jenis_kelamin" required
                                    {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }}
                                    class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-indigo-500">
                                <label for="perempuan" class="ms-2 text-sm font-medium text-gray-900">Perempuan</label>
                            </div>
                            <div class="flex items-center">
                                <input id="laki-laki" type="radio" value="Laki-Laki" name="jenis_kelamin" required
                                    {{ old('jenis_kelamin') == 'Laki-Laki' ? 'checked' : '' }}
                                    class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-indigo-500">
                                <label for="laki-laki" class="ms-2 text-sm font-medium text-gray-900">Laki-Laki</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="alamat" class="block mb-1 text-sm font-medium text-gray-500">Alamat</label>
                        <textarea
                            id="alamat"
                            rows="3"
                            name="alamat"
                            required
                            class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan Alamat">{{ old('alamat') }}</textarea>
                    </div>
                    <div>
                        <label for="pendidikan" class="block mb-1 text-sm font-medium text-gray-500">Pendidikan Terakhir</label>
                        <select id="pendidikan" name="pendidikan_terakhir_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="" disabled selected>Pilih Pendidikan</option>
                            @foreach($pendidikans as $pendidikan)
                            <option value="{{ $pendidikan->id }}" {{ old('pendidikan_id') == $pendidikan->id ? 'selected' : '' }}>
                                {{ $pendidikan->name }}
                            </option>
                             @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="no_telepon" class="block mb-1 text-sm font-medium text-gray-500">No Telepon</label>
                        <input type="tel" id="no_telepon" name="no_telepon" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-md">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Akun Pegawai</h2>
                 <div class="space-y-4">
                    <div>
                        <label for="email" class="block mb-1 text-sm font-medium text-gray-500">Username</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label for="password" class="block mb-1 text-sm font-medium text-gray-500">Password</label>
                        <input type="password" name="password" id="password" value="{{ old('password') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="••••••••" required>
                    </div>
                    <div>
                        <label for="role_name" class="block mb-1 text-sm font-medium text-gray-500">Role</label>
                        <select id="role_name" name="role_name" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="" disabled selected>Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}"
                                        {{ old('role_name') == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                 </div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-md">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi Pekerjaan</h2>
                <div class="space-y-4">
                    <div class="relative">
                         <label for="tanggal_bergabung" class="block mb-1 text-sm font-medium text-gray-500">Tanggal Bergabung</label>
                         <input datepicker datepicker-autohide type="text" id="tanggal_bergabung" name="tanggal_bergabung" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Pilih Tanggal" required>
                         <div class="absolute inset-y-0 end-0 top-6 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/>
                                <path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                         </div>
                    </div>
                    <div>
                        <label for="status_karyawan" class="block mb-1 text-sm font-medium text-gray-500">Status</label>
                        <select id="status_karyawan" name="status_karyawan"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required>
                            <option value="" disabled {{ old('status_karyawan') ? '' : 'selected' }}>
                                Pilih Status
                            </option>

                            <option value="Aktif" {{ old('status_karyawan') == 'Aktif' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="Tidak Aktif" {{ old('status_karyawan') == 'Tidak Aktif' ? 'selected' : '' }}>
                                Tidak Aktif
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="jabatan" class="block mb-1 text-sm font-medium text-gray-500">Jabatan</label>
                        <select id="jabatan" name="jabatan_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="" disabled selected>Pilih Jabatan</option>
                            @foreach ($jabatans as $jabatan )
                            <option value="{{ $jabatan->id }} {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}">
                                {{ $jabatan->name }}
                            </option>

                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="divisi" class="block mb-1 text-sm font-medium text-gray-500">Divisi</label>
                        <select id="divisi" name="divisi_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="" disabled selected>Pilih Divisi</option>
                            @foreach ($divisis as $divisi )
                            <option value="{{ $divisi->id }} {{ old('divisi_id') == $divisi->id ? 'selected' : '' }}">
                                {{ $divisi->name }}
                            </option>

                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="posisi" class="block mb-1 text-sm font-medium text-gray-500">Posisi</label>
                        <select id="posisi" name="posisi_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="" disabled selected>Pilih Posisi</option>
                            @foreach ($posisis as $posisi )
                            <option value="{{ $posisi->id }} {{ old('posisi_id') == $posisi->id ? 'selected' : '' }}">
                                {{ $posisi->name }}
                            </option>

                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-indi">
                Simpan
            </button>
        </form>
    </main>
    {{-- Di bagian bawah file tambah_karyawan.blade.php --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                attachFormSubmitConfirm(
                    'tambah-karyawan-form',
                    'Simpan Data?',
                    'Apakah Anda yakin ingin menyimpan data ini?'
                );

                // (Script alert session Anda)
                @if (session('success'))
                    showSessionAlert({ success: "{{ session('success') }}" });
                @endif
                @if (session('error'))
                    showSessionAlert({ error: "{{ session('error') }}" });
                @endif
            });
        </script>
    @endpush
</x-admin-layout>
