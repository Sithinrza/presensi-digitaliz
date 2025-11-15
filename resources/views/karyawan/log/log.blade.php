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

            <section>
                <div class="flex items-center justify-between mb-4 px-1">
                    <h2 class="text-lg font-bold text-gray-800">Aktivitas Hari Ini</h2>
                    <div class="flex items-center space-x-1 text-xs text-gray-500 font-medium">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">...</svg>
                        {{-- Tanggal Hari Ini (Variabel $today dari Controller) --}}
                        <span>{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</span>
                    </div>
                </div>

                <div class="relative pl-8">

                    {{-- LOOP DATA LOG HARIAN --}}
                    @forelse ($logs as $log)
                        <div class="timeline-item relative pb-6">

                             {{-- Perbaikan: CSS Timeline Dot --}}
                            <style>
                                /* Catatan: Style ini sebaiknya dipindah ke file app.css */
                                .timeline-dot::before {
                                    content: ''; position: absolute; left: -4px; top: 10px; width: 8px; height: 8px;
                                    background-color: #312e81; border-radius: 9999px; transform: translateX(-100%); z-index: 10;
                                }
                                /* Style untuk garis (jika belum ada di app.css) */
                                .relative.pl-8:before {
                                    content: ''; position: absolute; left: 0; top: 0; width: 2px; height: 100%; background-color: #e5e7eb;
                                }
                            </style>
                            <div class="timeline-dot"></div>

                            {{-- Waktu Log Dicatat (created_at) --}}
                            <div class="absolute left-0 top-3 text-xs font-semibold text-gray-500 -translate-x-full pr-2">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}
                            </div>

                            {{-- Konten Log --}}
                            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200 ml-4">
                                @php
                                    // Pisahkan log berdasarkan baris baru untuk membuat daftar (jika log multi-baris)
                                    $log_lines = explode("\n", $log->catatan_log);
                                @endphp

                                @if (count($log_lines) > 1)
                                    {{-- Jika log punya lebih dari 1 baris, tampilkan sebagai list --}}
                                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                                        @foreach ( $log_lines as $line)
                                            @if (trim($line) !== '') <li>{{ trim($line) }}</li> @endif
                                        @endforeach
                                    </ul>
                                @else
                                    {{-- Jika hanya satu baris, tampilkan sebagai paragraf --}}
                                    <p class="text-sm text-gray-700">{{ $log->catatan_log }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500 bg-white rounded-lg shadow-md border border-gray-200">
                            <p>Anda belum mencatat aktivitas hari ini.</p>
                            <p class="text-xs mt-2">Pastikan sudah melakukan presensi Check-In.</p>
                        </div>
                    @endforelse

                </div>
            </section>
        </main>
    </div>
</x-karyawan-layout>
