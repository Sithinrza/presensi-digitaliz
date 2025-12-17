<x-karyawan-layout>
    <x-slot:title>
        Log Aktivitas
    </x-slot:title>

    <div class="relative min-h-screen pb-24">
        <header class="bg-white p-4 shadow-sm sticky top-0 z-20">
            <h1 class="text-gray-800 font-bold text-lg text-center">Log Aktivitas</h1>
        </header>

        <main class="p-4 space-y-6">

            {{-- Pesan Sukses/Error (Diambil dari controller store) --}}
            @if(session('success')) <div class="p-3 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div> @endif
            @if(session('error')) <div class="p-3 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div> @endif

            {{-- Form Tambah Aktivitas Baru --}}
            {{-- File: log.blade.php (Di dalam Form Tambah Aktivitas Baru) --}}

            <section class="bg-indigo-950 p-6 rounded-2xl shadow-lg">
                <h2 class="text-lg font-semibold text-white mb-4">Tambah Aktivitas Baru</h2>

                {{-- KRITIS: Menonaktifkan form jika presensi tidak valid --}}
                <form action="{{ route('karyawan.log.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <textarea name="catatan_log" placeholder="Apa yang dilakukan hari ini ?"
                            class="w-full p-3 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 mb-4"
                            rows="4" @unless($isPresensiValid) disabled @endunless required>{{ old('catatan_log') }}</textarea>

                    {{-- @unless($isPresensiValid)
                        <div class="p-2 bg-yellow-100 text-yellow-700 text-sm rounded-lg text-center">
                            Anda harus Check-In terlebih dahulu untuk mencatat aktivitas.
                        </div>
                    @endunless --}}

                    {{-- Tombol Submit --}}
                    <button type="submit"
                            @unless($isPresensiValid) disabled @endunless
                            class="w-full text-indigo-950 font-bold py-3 rounded-lg shadow-md transition flex items-center justify-center space-x-2
                                @if($isPresensiValid) bg-white hover:bg-gray-200 @else bg-gray-300 text-gray-500 cursor-not-allowed @endif">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">...</svg>
                        <span>Simpan Aktivitas</span>
                    </button>
                </form>
            </section>

            {{-- 2. DAFTAR AKTIVITAS HARI INI --}}
            <section>
                <div class="flex items-center justify-between mb-4 px-1">
                    <h2 class="text-lg font-bold text-gray-800">Riwayat Hari Ini</h2>
                    <div class="flex items-center space-x-1 text-xs text-gray-500 font-medium bg-white px-3 py-1 rounded-full shadow-sm border border-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{-- Pastikan controller mengirim variabel $today --}}
                        <span>{{ \Carbon\Carbon::parse($today)->translatedFormat('d M Y') }}</span>
                    </div>
                </div>

                {{-- STYLE GARIS TIMELINE --}}
                <div class="relative pl-4 space-y-4">
                    {{-- Garis Vertikal --}}
                    <div class="absolute left-[19px] top-2 bottom-4 w-0.5 bg-gray-200"></div>

                    @forelse ($logs as $log)
                        <div class="relative pl-8 group">
                            
                            {{-- Titik Timeline (Dot) --}}
                            <div class="absolute left-[13px] top-5 w-3.5 h-3.5 bg-white border-4 border-indigo-600 rounded-full z-10 group-hover:scale-110 transition-transform"></div>

                            {{-- Kartu Log --}}
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow relative">
                                
                                {{-- PERBAIKAN UTAMA: POSISI JAM --}}
                                {{-- Jam ditaruh di dalam kartu (pojok kanan atas), bukan di luar --}}
                                <div class="flex justify-between items-start gap-4 mb-2">
                                    {{-- Teks Log --}}
                                    <p class="text-sm text-gray-700 leading-relaxed font-medium break-words">
                                        {{ $log->catatan_log }}
                                    </p>

                                    {{-- Jam --}}
                                    <span class="flex-shrink-0 text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md border border-indigo-100">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}
                                    </span>
                                </div>
                                
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 px-4 bg-white rounded-xl border border-dashed border-gray-300 ml-4">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-50 rounded-full mb-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">Belum ada aktivitas.</p>
                            <p class="text-gray-400 text-xs mt-1">Catat pekerjaan Anda hari ini.</p>
                        </div>
                    @endforelse

                </div>
            </section>
        </main>
    </div>
</x-karyawan-layout>
