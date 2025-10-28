<x-admin-layout>
    <x-slot:title>
        Update
    </x-slot:title>
    <header class="bg-indigo-950 text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto flex items-center p-4">
            <button type="button" class="p-2">
                <a href="{{ route('admin.karyawan.show', $karyawan->id) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </button>
            <h1 class="text-lg font-semibold flex-grow text-center">
                Edit Data Karyawan
            </h1>
        </div>
    </header>
    <section class="bg-gray-150 text-white pt-8 pb-4 text-center">
        <img class="w-24 h-24 rounded-full object-cover mx-auto mb-2 border-4 border-white shadow-lg" src="img/user.svg" alt="Foto Profil Abadi">
        <h1 class="text-xl font-bold text-black">{{ $karyawan->nama_lengkap }}</h1>
    </section>
    <main class="p-4 space-y-6 pb-24">
        <form action="{{ route('admin.karyawan.update', $karyawan->id) }}" method="PUT" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="bg-white p-5 rounded-xl shadow-md">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Data Pegawai</h2>
                <div class="space-y-4">
                    {{-- <div>
                        <label for="id-pegawai" class="block mb-1 text-sm font-medium text-gray-500">ID Pegawai</label>
                        <input type="text" id="id-pegawai" value="123456789" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    </div> --}}
                    <div>
                        <label for="nip" class="block mb-1 text-sm font-medium text-gray-500">NIP</label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip', $karyawan->nip ?? '') }}" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    </div>
                    <div>
                        <label for="tempat_lahir" class="block mb-1 text-sm font-medium text-gray-500">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" value="{{ old('tempat_lahir', $karyawan->tempat_lahir) ?? '' }}" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    </div>
                    <div class="relative">
                         <label for="tanggal-lahir" class="block mb-1 text-sm font-medium text-gray-500">Tanggal Lahir</label>
                         <input type="text" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir?->format('d-m-Y') ?? '') }}" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                         <div class="absolute inset-y-0 end-0 top-6 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/>
                                <path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                         </div>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-500">Jenis Kelamin</label>
                        <input type="text" value="{{ old('jenis_kelamin', $karyawan->jenis_kelamin) }}" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    </div>
                    <div>
                        <label for="agama" class="block mb-1 text-sm font-medium text-gray-500">Agama</label>
                        <select id="agama" name="agama_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Pilih Agama</option>
                            @foreach($agamas as $agama)
                                <option value="{{ $agama->id }}"
                                    @if(old('agama_id', $karyawan->agama_id) == $agama->id)
                                        selected
                                    @endif
                                >
                                {{ $agama->name }} {{-- Tampilkan nama agama (sesuaikan 'name' jika nama kolom berbeda) --}}
                                </option>
                            @endforeach
                        </select>
                        @error('agama_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat" class="block mb-1 text-sm font-medium text-gray-500">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan Alamat">
                            {{ old('alamat', $karyawan->alamat ?? '') }}
                        </textarea>
                        @error('alamat')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="pendidikan" class="block mb-1 text-sm font-medium text-gray-500">Pendidikan Terakhir</label>
                        <select id="pendidikan" name="pendidikan_terakhir_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Pilih Pendidikan Terakhir</option> 
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
                    </div>
                    <div>
                        <label for="no-telepon" class="block mb-1 text-sm font-medium text-gray-500">No Telepon</label>
                        <input type="tel" id="no_telepon" value="{{ old('no_telepon', $karyawan->no_telepon) }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-md">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Akun Pegawai</h2>
                 <div class="space-y-4">
                    <div>
                        <label for="username" class="block mb-1 text-sm font-medium text-gray-500">Username</label>
                        <input type="email" id="username" value="{{ old('email', $karyawan->user?->email) }}" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    </div>
                    <div>
                        <label for="password" class="block mb-1 text-sm font-medium text-gray-500">Password</label>
                        <input type="password" id="password" value="{{ old('password', $karyawan->user?->password) }}" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    </div>
                    <div>
                        <label for="role_display" class="block mb-1 text-sm font-medium text-gray-500">Role</label>
                        @php
                            $currentRoleName = $karyawan->user->roles->first()->name ?? 'N/A';
                            $currentRoleId = $karyawan->user->roles->first()->id ?? '';
                        @endphp

                        <input type="text" id="role_display" 
                            value="{{ $currentRoleName }}" 
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                            readonly> {{-- Set Readonly --}}
                        <input type="hidden" name="role_name" value="{{ $currentRoleName }}"> 
                        
                    </div>
                 </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-md">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi Pekerjaan</h2>
                <div class="space-y-4">
                    <div class="relative">
                        <label for="tanggal-bergabung" class="block mb-1 text-sm font-medium text-gray-500">Tanggal Bergabung</label>
                        <input type="text" id="tanggal_bergabung" name="tanggal_bergabung" 
                            value="{{ old('tanggal_bergabung', $karyawan->tanggal_bergabung ? \Carbon\Carbon::parse($karyawan->tanggal_bergabung)->format('Y-m-d') : '') }}" 
                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                        <div class="absolute inset-y-0 end-0 top-6 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/>
                                <path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <label for="status-karyawan" class="block mb-1 text-sm font-medium text-gray-500">Status Karyawan</label>
                        <select id="status-karyawan" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option>Aktif</option>
                            <option>Tidak Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label for="jabatan" class="block mb-1 text-sm font-medium text-gray-500">Jabatan</label>
                        <select id="jabatan" name="jabatan_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Pilih Jabatan</option> 
                            @foreach($jabatans as $jabatan)
                                <option value="{{ $jabatan->id }}"
                                    @if(old('jabatan_id', $karyawan->jabatan_id) == $jabatan->id)
                                        selected
                                    @endif
                                >
                                    {{ $jabatan->name }} 
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="divisi" class="block mb-1 text-sm font-medium text-gray-500">Divisi</label>
                        <select id="divisi" name="divisi_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Pilih Divisi</option>
                            @foreach($divisis as $divisi)
                                <option value="{{ $divisi->id }}"
                                    @if(old('divisi_id', $karyawan->divisi_id) == $divisi->id)
                                        selected
                                    @endif
                                >
                                    {{ $divisi->name }} 
                                </option>
                            @endforeach
                        </select>
                        @error('divisi_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="posisi" class="block mb-1 text-sm font-medium text-gray-500">Posisi</label>
                        <select id="posisi" name="posisi_id" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Pilih Posisi</option> 
                            @foreach($posisis as $posisi)
                                <option value="{{ $posisi->id }}"
                                    @if(old('posisi_id', $karyawan->posisi_id) == $posisi->id)
                                        selected
                                    @endif
                                >
                                    {{ $posisi->name }} 
                                </option>
                            @endforeach
                        </select>
                        @error('posisi_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-indi">
                Edit Data
            </button>
        </form>
    </main>
</x-admin-layout>
