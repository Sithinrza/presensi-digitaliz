<x-app-layout>
    <x-slot:title>
        Tambah
    </x-slot:title>
    <header class="bg-indigo-950 text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto flex items-center p-4">
            <button type="button" class="p-2">
                <a href="datapegawai.html">
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
        <form action="#" class="space-y-6">
            <div class="bg-white p-5 rounded-xl shadow-md">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Data Pegawai</h2>
                <div class="space-y-4">
                    <div>
                        <label for="id-pegawai" class="block mb-1 text-sm font-medium text-gray-500">ID Pegawai</label>
                        <input type="text" id="id-pegawai" value="123456789" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                    </div>
                    <div>
                        <label for="nip" class="block mb-1 text-sm font-medium text-gray-500">NIP</label>
                        <input type="text" id="nip" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan NIP">
                    </div>
                    <div>
                        <label for="nama-pegawai" class="block mb-1 text-sm font-medium text-gray-500">Nama Pegawai</label>
                        <input type="text" id="nama-pegawai" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan Nama">
                    </div>
                    <div>
                        <label for="tempat-lahir" class="block mb-1 text-sm font-medium text-gray-500">Tempat Lahir</label>
                        <input type="text" id="tempat-lahir" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan Tempat Lahir">
                    </div>
                    <div class="relative">
                         <label for="tanggal-lahir" class="block mb-1 text-sm font-medium text-gray-500">Tanggal Lahir</label>
                         <input datepicker datepicker-autohide type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Pilih Tanggal">
                         <div class="absolute inset-y-0 end-0 top-6 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/>
                                <path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                         </div>
                    </div>
                    <div>
                        <label for="agama" class="block mb-1 text-sm font-medium text-gray-500">Agama</label>
                        <select id="agama" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option>Pilih Agama</option>
                            <option>Islam</option>
                            <option>Kristen Protestan</option>
                            <option>Katolik</option>
                            <option>Hindu</option>
                            <option>Budha</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-500">Jenis Kelamin</label>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <input id="perempuan" type="radio" value="" name="jenis-kelamin" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label for="perempuan" class="ms-2 text-sm font-medium text-gray-900">Perempuan</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="laki-laki" type="radio" value="" name="jenis-kelamin" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label for="laki-laki" class="ms-2 text-sm font-medium text-gray-900">Laki-Laki</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="alamat" class="block mb-1 text-sm font-medium text-gray-500">Alamat</label>
                        <textarea id="alamat" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan Alamat"></textarea>
                    </div>
                    <div>
                        <label for="pendidikan" class="block mb-1 text-sm font-medium text-gray-500">Pendidikan Terakhir</label>
                        <select id="pendidikan" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option>Pilih Pendidikan</option>
                            <option>SMA/SMK</option>
                            <option>Diploma 3</option>
                            <option>Diploma 4</option>
                            <option>Sarjana (S1)</option>
                            <option>Magister (S2)</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block mb-1 text-sm font-medium text-gray-500">Status</label>
                        <select id="status" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option>Pilih Status</option>
                            <option>Belum Menikah</option>
                            <option>Menikah</option>
                        </select>
                    </div>
                    <div>
                        <label for="no-telepon" class="block mb-1 text-sm font-medium text-gray-500">No Telepon</label>
                        <input type="tel" id="no-telepon" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-md">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Akun Pegawai</h2>
                 <div class="space-y-4">
                    <div>
                        <label for="username" class="block mb-1 text-sm font-medium text-gray-500">Username</label>
                        <input type="email" id="username" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="email@contoh.com">
                    </div>
                    <div>
                        <label for="password" class="block mb-1 text-sm font-medium text-gray-500">Password</label>
                        <input type="password" id="password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="••••••••">
                    </div>
                    <div>
                        <label for="role" class="block mb-1 text-sm font-medium text-gray-500">Role</label>
                        <select id="role" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option>Pilih Role</option>
                            <option>Admin</option>
                            <option>Karyawan</option>
                        </select>
                    </div>
                 </div>
            </div>


            <div class="bg-white p-5 rounded-xl shadow-md">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi Pekerjaan</h2>
                <div class="space-y-4">
                    <div class="relative">
                         <label for="tanggal-bergabung" class="block mb-1 text-sm font-medium text-gray-500">Tanggal Bergabung</label>
                         <input datepicker datepicker-autohide type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Pilih Tanggal">
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
                            <option>Pilih Status</option>
                            <option>Aktif</option>
                            <option>Tidak Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label for="jabatan" class="block mb-1 text-sm font-medium text-gray-500">Jabatan</label>
                        <select id="jabatan" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option>Pilih Jabatan</option>
                            <option>Manager</option>
                            <option>Supervisor</option>
                            <option>Staff</option>
                            <option>Karyawan</option>
                        </select>
                    </div>
                    <div>
                        <label for="divisi" class="block mb-1 text-sm font-medium text-gray-500">Divisi</label>
                        <select id="divisi" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option>Pilih Divisi</option>
                            <option>Marketing</option>
                            <option>Programmer</option>
                            <option>UI/UX</option>
                            <option>HRD</option>
                        </select>
                    </div>
                    <div>
                        <label for="posisi" class="block mb-1 text-sm font-medium text-gray-500">Posisi</label>
                        <select id="posisi" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option>Pilih Posisi</option>
                            <option>Front-End</option>
                            <option>Back-End</option>
                            <option>Full-Stack</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full text-white bg-indigo-950 hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-3 text-center">
                Simpan
            </button>
        </form>
    </main>
</x-app-layout>