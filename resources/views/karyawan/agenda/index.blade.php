<x-karyawan-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <style>
            /* --- RESET DASAR --- */
            .flatpickr-calendar {
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none !important;
                background: transparent !important;
                font-family: inherit !important;
            }
            .flatpickr-rContainer, .flatpickr-months, .flatpickr-weekdays, .flatpickr-days {
                width: 100% !important;
            }
            .dayContainer {
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
                display: flex !important;
                justify-content: space-around !important;
            }
            .flatpickr-weekdaycontainer {
                display: flex !important;
                width: 100% !important;
                justify-content: space-around !important;
            }
            span.flatpickr-weekday {
                flex: 1;
                font-weight: 700;
                color: #374151;
            }

            /* 1. TAMPILAN LAPTOP / TABLET (Layar Besar) */
            .flatpickr-day {
                width: 14.28% !important;
                max-width: initial !important;
                margin: 0 !important;
                border-radius: 10px !important;
                border: 1px solid transparent;
                
                height: 46px !important; 
                line-height: 46px !important;
                font-size: 15px !important;
            }

            /* 2. TAMPILAN HP (Layar Kecil - Max Width 640px) */
            @media (max-width: 640px) {
                .flatpickr-day {
                    height: 34px !important; 
                    line-height: 34px !important; 
                    font-size: 13px !important; 
                    border-radius: 8px !important;
                }
                
                .flatpickr-months {
                    margin-bottom: 5px !important; 
                }
                
                span.flatpickr-weekday {
                    font-size: 12px !important;
                }
            }

            .flatpickr-day:hover {
                background: #f3f4f6 !important;
                border-color: #e5e7eb !important;
            }
            .flatpickr-day.today {
                background: #e0e7ff !important;
                color: #4338ca !important;
                border-color: transparent !important;
                font-weight: bold;
            }
            .flatpickr-day.selected, 
            .flatpickr-day.selected:hover {
                background: #4338ca !important;
                color: #fff !important;
                border-color: #4338ca !important;
            }
        </style>
    @endpush

    <x-slot:title>
        Jadwal
    </x-slot:title>

    <div class="relative min-h-screen pb-24 bg-gray-100">
        <div class="bg-indigo-950 p-4 pt-8 pb-24 -mt-1 rounded-t-[3rem] relative z-10"></div>
        
        <main class="p-4 -mt-20 relative z-10 space-y-6">
            
            <section class="bg-white p-4 sm:p-6 rounded-3xl shadow-lg flex justify-center">
                <div class="w-full sm:max-w-md"> 
                    <div class="text-center mb-3 sm:mb-4">
                        <h3 class="text-gray-800 font-bold text-base sm:text-lg">Pilih Tanggal</h3>
                        <p class="text-[10px] sm:text-xs text-gray-400">Klik tanggal untuk melihat agenda</p>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-2xl p-1 sm:p-2 shadow-sm">
                        <div id="kalender-karyawan"></div>
                    </div>
                </div>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-gray-800 px-1">Agenda Hari Ini</h2>
                
                <div id="agenda-list" class="space-y-3">
                    @if($agendaHariIni->count() == 0)
                        <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 text-center text-gray-500 italic">
                            Tidak ada agenda hari ini.
                        </div>
                    @else
                        @foreach ($agendaHariIni as $agenda)
                            <div class="bg-white rounded-2xl p-4 mb-4 border border-gray-100 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_20px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 group">
                                <div class="flex gap-4 items-start">
                                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M19 18H9a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h1v5l2-1.5L14 7V2h5a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2m-2 2v2H5a2 2 0 0 1-2-2V6h2v14z" stroke-width="0.5" stroke="currentColor"/>
                                        </svg>
                                    </div>

                                    <div class="flex-grow min-w-0">
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
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
        
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            
            const agendaList = document.getElementById("agenda-list");

            function fetchAgendaByDate(selectedDate) {
                agendaList.innerHTML = `
                    <div class="bg-white p-4 rounded-xl shadow-md border text-center text-gray-500 italic animate-pulse">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i> Memuat data...
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
                            let mulai = agenda.waktu_mulai ? agenda.waktu_mulai.substring(0, 5) : '--:--';
                            let selesai = agenda.waktu_selesai ? agenda.waktu_selesai.substring(0, 5) : '--:--';
                            let lokasi = agenda.lokasi_alamat ? agenda.lokasi_alamat : 'Lokasi belum diset';
                            let ruang = agenda.ruang ? agenda.ruang : 'Ruang Umum';

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
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        agendaList.innerHTML = `<div class="text-red-500 text-center text-sm p-4">Gagal memuat data.</div>`;
                    });
            }

            flatpickr("#kalender-karyawan", {
                inline: true,
                locale: "id",
                dateFormat: "Y-m-d",
                defaultDate: "today",
                onChange: function(selectedDates, dateStr) {
                    fetchAgendaByDate(dateStr);
                }
            });

        });
        </script>
    @endpush
</x-karyawan-layout>