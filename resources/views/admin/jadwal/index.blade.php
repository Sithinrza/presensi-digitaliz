<x-admin-layout>
    <x-slot:title>
        Manajemen Jadwal Kerja
    </x-slot:title>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

        {{-- ===================================================== --}}
        {{-- BAGIAN 1: PENETAPAN JADWAL KARYAWAN --}}
        {{-- ===================================================== --}}
        <section class="bg-white rounded-2xl shadow-sm border border-indigo-100 overflow-hidden">
            {{-- Header Card --}}
            <div class="px-6 py-5 border-b border-indigo-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gradient-to-r from-indigo-50/50 to-white">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <span class="bg-indigo-100 text-indigo-700 p-2 rounded-lg shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        Penetapan Jadwal Karyawan
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 ml-11">Atur jadwal kerja aktif untuk setiap karyawan.</p>
                </div>
                
                <a href="{{ route('admin.penetapan.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-900 hover:bg-indigo-800 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-md shadow-indigo-200 focus:ring-4 focus:ring-indigo-100 transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tetapkan Jadwal
                </a>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Karyawan</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Jadwal Aktif</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Ditetapkan Sejak</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-indigo-900 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($penetapanJadwals as $penetapan)
                            <tr class="hover:bg-indigo-50/30 transition-colors duration-150 group">
                                {{-- Kolom Nama dengan FOTO PROFIL --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($penetapan->karyawan && $penetapan->karyawan->foto_profil)
                                                {{-- Tampilkan Foto Jika Ada --}}
                                                <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" 
                                                     src="{{ asset('storage/' . $penetapan->karyawan->foto_profil) }}" 
                                                     alt="{{ $penetapan->karyawan->nama_lengkap }}">
                                            @else
                                                {{-- Fallback ke Inisial Jika Foto Kosong --}}
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm border-2 border-white shadow-sm">
                                                    {{ substr($penetapan->karyawan->nama_lengkap ?? 'X', 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">
                                                {{ $penetapan->karyawan->nama_lengkap ?? 'Data Hilang' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $penetapan->karyawan->jabatan->name ?? 'Karyawan' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                    #{{ $penetapan->id_karyawan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($penetapan->jadwalKerja)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $penetapan->jadwalKerja->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Tidak Ada Jadwal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $penetapan->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.penetapan.edit', $penetapan->id_karyawan) }}" 
                                           class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 hover:text-indigo-800 transition-all shadow-sm"
                                           title="Edit Jadwal">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        
                                        <form action="{{ route('admin.penetapan.destroy', $penetapan->id_karyawan) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal karyawan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 hover:text-red-800 transition-all shadow-sm"
                                                    title="Hapus Jadwal">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="bg-indigo-50 p-4 rounded-full mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900">Belum ada penetapan jadwal</h3>
                                        <p class="text-sm text-gray-500 mt-1 mb-4">Mulai tetapkan jadwal kerja untuk karyawan Anda.</p>
                                        <a href="{{ route('admin.penetapan.create') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm underline decoration-indigo-200 hover:decoration-indigo-500">
                                            + Tetapkan Jadwal Sekarang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ===================================================== --}}
        {{-- BAGIAN 2: TEMPLATE JADWAL MASTER --}}
        {{-- ===================================================== --}}
        <section class="bg-white rounded-2xl shadow-sm border border-indigo-100 overflow-hidden">
            {{-- Header Card --}}
            <div class="px-6 py-5 border-b border-indigo-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gradient-to-r from-indigo-50/50 to-white">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <span class="bg-indigo-100 text-indigo-700 p-2 rounded-lg shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </span>
                        Master Template Jadwal
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 ml-11">Buat template jam kerja (Regular, Shift, dll).</p>
                </div>
                
                <a href="{{ route('admin.jadwal.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-white text-indigo-700 text-sm font-medium rounded-lg border border-indigo-200 hover:bg-indigo-50 hover:border-indigo-300 transition-colors duration-200 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Template Baru
                </a>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Nama Template</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Detail Waktu</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Dibuat Pada</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-indigo-900 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($jadwalKerjas as $jadwal)
                            <tr class="hover:bg-indigo-50/30 transition-colors duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                    {{ $jadwal->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-800">{{ $jadwal->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex flex-col gap-1">
                                        
                                        {{-- PERBAIKAN: Gunakan 'detailJadwals' sesuai Controller kamu --}}
                                        @php
                                            $detail = $jadwal->detailJadwals ? $jadwal->detailJadwals->first() : null;
                                        @endphp

                                        {{-- JAM MASUK --}}
                                        <span class="flex items-center text-xs">
                                            <svg class="w-4 h-4 text-emerald-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                            Masuk: 
                                            <span class="font-medium ml-1">
                                                {{ ($detail && $detail->jam_masuk) ? \Carbon\Carbon::parse($detail->jam_masuk)->format('H:i') : '-' }}
                                            </span>
                                        </span>

                                        {{-- JAM PULANG --}}
                                        <span class="flex items-center text-xs">
                                            <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Pulang: 
                                            <span class="font-medium ml-1">
                                                {{ ($detail && $detail->jam_pulang) ? \Carbon\Carbon::parse($detail->jam_pulang)->format('H:i') : '-' }}
                                            </span>
                                        </span>
                                        
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $jadwal->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.jadwal.edit', $jadwal) }}" 
                                           class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 hover:text-indigo-800 transition-all shadow-sm"
                                           title="Edit Template">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        
                                        <form action="{{ route('admin.jadwal.destroy', $jadwal) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('PERINGATAN! Menghapus template ini akan mempengaruhi karyawan yang menggunakannya. Lanjutkan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 hover:text-red-800 transition-all shadow-sm"
                                                    title="Hapus Template">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="bg-indigo-50 p-4 rounded-full mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900">Belum ada template jadwal</h3>
                                        <p class="text-sm text-gray-500 mt-1 mb-4">Buat template jam kerja standar atau shift.</p>
                                        <a href="{{ route('admin.jadwal.create') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm underline decoration-indigo-200 hover:decoration-indigo-500">
                                            + Buat Template Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</x-admin-layout>