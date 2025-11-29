<x-karyawan-layout>
    <x-slot:title>
        Jadwal
    </x-slot:title>

    <div class="relative min-h-screen pb-24 bg-gray-100">
        <div class="bg-indigo-950 p-4 pt-8 pb-24 -mt-1 rounded-t-[3rem] relative z-10"></div>
        <main class="p-4 -mt-20 relative z-10 space-y-6">
            <section class="bg-white p-5 rounded-2xl shadow-lg flex justify-center">
                <div class="border-4 border-gray-100 rounded-2xl p-3">
                    <div id="kalender-karyawan"></div>
                </div>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-gray-800 px-1">Agenda Hari Ini</h2>
                {{-- Memanggil data dari controller --}}
                <div id="agenda-list" class="space-y-3">

                    @if($agendaHariIni->count() == 0)
                        <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 text-center text-gray-500 italic">
                            Tidak ada agenda hari ini.
                        </div>
                    @else

                        @foreach ($agendaHariIni as $agenda)
                            <div class="bg-white rounded-2xl p-4 mb-4 border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_20px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 group">
                                <div class="flex gap-4 items-start">
                                    {{-- ICON BOX (Kiri) --}}
                                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19 18H9a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h1v5l2-1.5L14 7V2h5a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2m-2 2v2H5a2 2 0 0 1-2-2V6h2v14z" stroke-width="0.5" stroke="currentColor"/>
                                        </svg>
                                    </div>

                                    {{-- KONTEN UTAMA --}}
                                    <div class="flex-grow min-w-0">

                                        {{-- Header: Judul & Waktu --}}
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-2">
                                            <h3 class="font-bold text-gray-800 text-lg leading-tight truncate pr-2">
                                                {{ $agenda->judul }}
                                            </h3>

                                            <div class="inline-flex self-start sm:self-auto items-center gap-2 text-indigo-600 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-lg">
                                                <i class="fa-solid fa-clock text-xs"></i>
                                                <span class="text-xs font-bold whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($agenda->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($agenda->waktu_selesai)->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Detail: Lokasi & Ruangan --}}
                                        <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs text-gray-500 mb-3">
                                            <div class="flex items-center gap-1.5">
                                                <i class="fa-solid fa-location-dot text-rose-500"></i>
                                                <span class="truncate max-w-[150px]">{{ $agenda->lokasi_alamat ?? 'Lokasi belum diset' }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <i class="fa-solid fa-door-open text-blue-500"></i>
                                                <span class="truncate">{{ $agenda->ruang ?? 'Ruang Umum' }}</span>
                                            </div>
                                        </div>

                                        {{-- Catatan (Kembali ke model Kotak Kuning) --}}
                                        @if($agenda->catatan)
                                            <div class="mt-2 p-3 bg-amber-50 border border-amber-100 rounded-xl flex items-start gap-3">
                                                <div class="mt-0.5 text-amber-500">
                                                    <i class="fa-solid fa-note-sticky text-sm"></i>
                                                </div>
                                                <div class="text-xs text-slate-700 leading-relaxed w-full">
                                                    <span class="font-bold text-amber-700 block mb-0.5 uppercase tracking-wider text-[10px]">Catatan</span>
                                                    {{ $agenda->catatan }}
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>
        </main>
    </div>
    @push('scripts')
        <script>
        document.addEventListener("DOMContentLoaded", function () {

            const agendaList = document.getElementById("agenda-list");

            window.onDateClick = function (selectedDate) {

                agendaList.innerHTML = `
                    <div class="bg-white p-4 rounded-xl shadow-md border text-center text-gray-500 italic">
                        Memuat data...
                    </div>
                `;

                fetch(`/karyawan/agenda/by-date?date=${selectedDate}`)
                    .then(res => res.json())
                    .then(data => {

                        if (data.length === 0) {
                            agendaList.innerHTML = `
                                <div class="bg-white p-4 rounded-xl shadow-md border text-center text-gray-500 italic">
                                    Tidak ada agenda pada tanggal ini.
                                </div>
                            `;
                            return;
                        }

                    agendaList.innerHTML = "";

                    data.forEach(agenda => {
                        // Format Waktu
                        let mulai = agenda.waktu_mulai.substring(0, 5);
                        let selesai = agenda.waktu_selesai.substring(0, 5);

                        // Cek Null
                        let lokasi = agenda.lokasi_alamat ? agenda.lokasi_alamat : 'Lokasi belum diset';
                        let ruang = agenda.ruang ? agenda.ruang : 'Ruang Umum';

                        // HTML Catatan (Style Kotak Kuning)
                        let catatanHtml = '';
                        if (agenda.catatan) {
                            catatanHtml = `
                                <div class="mt-2 p-3 bg-amber-50 border border-amber-100 rounded-xl flex items-start gap-3">
                                    <div class="mt-0.5 text-amber-500">
                                        <i class="fa-solid fa-note-sticky text-sm"></i>
                                    </div>
                                    <div class="text-xs text-slate-700 leading-relaxed w-full">
                                        <span class="font-bold text-amber-700 block mb-0.5 uppercase tracking-wider text-[10px]">Catatan</span>
                                        ${agenda.catatan}
                                    </div>
                                </div>
                            `;
                        }

                        agendaList.innerHTML += `
                            <div class="bg-white rounded-2xl p-4 mb-4 border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_20px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 group animate-fade-in">
                                <div class="flex gap-4 items-start">

                                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19 18H9a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h1v5l2-1.5L14 7V2h5a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2m-2 2v2H5a2 2 0 0 1-2-2V6h2v14z" stroke-width="0.5" stroke="currentColor"/>
                                        </svg>
                                    </div>

                                    <div class="flex-grow min-w-0">

                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-2">
                                            <h3 class="font-bold text-gray-800 text-lg leading-tight truncate pr-2">
                                                ${agenda.judul}
                                            </h3>

                                            <div class="inline-flex self-start sm:self-auto items-center gap-2 text-indigo-600 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-lg">
                                                <i class="fa-solid fa-clock text-xs"></i>
                                                <span class="text-xs font-bold whitespace-nowrap">
                                                    ${mulai} - ${selesai}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap gap-x-4 gap-y-2 text-xs text-gray-500 mb-3">
                                            <div class="flex items-center gap-1.5">
                                                <i class="fa-solid fa-location-dot text-rose-500"></i>
                                                <span class="truncate max-w-[150px]">${lokasi}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <i class="fa-solid fa-door-open text-blue-500"></i>
                                                <span class="truncate">${ruang}</span>
                                            </div>
                                        </div>

                                        ${catatanHtml}

                                    </div>
                                </div>
                            </div>
                        `;
                    });
                });
            };
        });
        </script>
    @endpush

</x-karyawan-layout>

