<x-admin-layout>
    <div class="container mx-auto p-4 md:p-8">

       {{-- ===================================================== --}}
        {{-- PENETAPAN JADWAL KARYAWAN --}}
        {{-- ===================================================== --}}
        <div class="p-6 bg-green-50 border border-green-200 rounded-2xl shadow-xl mb-12">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-extrabold text-green-800 flex items-center gap-3">
                    <i class="fa-solid fa-user-tag text-green-600"></i>
                    Penetapan Jadwal Karyawan
                </h2>
                <a href="{{ route('admin.penetapan.create') }}"
                   class="px-4 py-2 bg-green-600 text-white font-semibold rounded-xl shadow hover:bg-green-700 transition">
                   <i class="fa-solid fa-user-plus mr-1"></i> Tetapkan Jadwal
                </a>
            </div>

            {{-- Tabel Penetapan --}}
            <div class="overflow-x-auto mt-4">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-green-50 border-b-2 border-green-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-green-700 uppercase tracking-wider">ID Karyawan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-green-700 uppercase tracking-wider">Nama Karyawan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-green-700 uppercase tracking-wider">Template Jadwal Aktif</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-green-700 uppercase tracking-wider">Ditetapkan Sejak</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($penetapanJadwals as $penetapan)
                            <tr class="hover:bg-green-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $penetapan->id_karyawan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">
                                    {{ $penetapan->karyawan->nama_lengkap ?? 'Karyawan Tidak Ditemukan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">
                                    @if ($penetapan->jadwalKerja)
                                        <span class="bg-green-100 text-green-800 text-xs font-extrabold px-3 py-1 rounded-full border border-green-200 shadow-sm">
                                            {{ $penetapan->jadwalKerja->name }}
                                        </span>
                                    @else
                                        <span class="text-red-500 text-xs">Jadwal Tidak Ditemukan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $penetapan->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.penetapan.edit', $penetapan->id_karyawan) }}"
                                    class="text-sm text-green-600 hover:text-green-800">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.penetapan.destroy', $penetapan->id_karyawan) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Apakah Anda yakin ingin memutuskan relasi jadwal karyawan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 transition transform hover:scale-105">
                                            <i class="fa-solid fa-unlink mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 text-lg bg-gray-50">
                                    <i class="fa-solid fa-users-slash text-4xl mb-4 text-gray-300"></i>
                                    <p class="font-semibold">Belum ada karyawan yang ditetapkan jadwal kerjanya.</p>
                                    <p class="text-sm">Silakan klik "Tetapkan Jadwal" untuk memulai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>



        {{-- ===================================================== --}}
        {{-- TEMPLATE JADWAL --}}
        {{-- ===================================================== --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-4 border-b border-indigo-100">
            <h1 class="text-4xl font-extrabold text-indigo-900 tracking-tight mb-3 md:mb-0">
                Template Jadwal Kerja
            </h1>
            <a href="{{ route('admin.jadwal.create') }}"
               class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-all duration-200 flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fa-solid fa-calendar-plus text-sm"></i>
                <span>Buat Template Baru</span>
            </a>
        </div>

        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100 mb-12">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-indigo-50 border-b-2 border-indigo-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Nama Template</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Dibuat</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($jadwalKerjas as $jadwal)
                            <tr class="hover:bg-indigo-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $jadwal->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-gray-800">
                                    <span class="bg-indigo-100 text-indigo-800 text-xs font-extrabold px-3 py-1 rounded-full border border-indigo-200 shadow-sm">
                                        {{ $jadwal->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $jadwal->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">
                                    <a href="{{ route('admin.jadwal.edit', $jadwal) }}"
                                       class="text-sm text-indigo-600 hover:text-indigo-800 transition transform hover:scale-105">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.jadwal.destroy', $jadwal) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('PERINGATAN! Anda yakin menghapus jadwal ini? Relasi karyawan akan terputus!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 transition transform hover:scale-105">
                                            <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 text-lg bg-gray-50">
                                    <i class="fa-solid fa-calendar-times text-4xl mb-4 text-gray-300"></i>
                                    <p class="font-semibold">Belum ada template jadwal kerja.</p>
                                    <p class="text-sm">Silakan klik "Buat Template Baru" di atas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>
