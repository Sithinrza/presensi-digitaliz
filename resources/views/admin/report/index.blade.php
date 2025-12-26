<x-admin-layout>
    <x-slot:title>
        Laporan Harian
    </x-slot:title>

    <div class="relative min-h-screen pb-24 bg-gray-50">
        
        {{-- Header --}}
        <header class="bg-indigo-950 p-4 pb-16 rounded-b-[2.5rem] shadow-lg relative z-10 -mt-1">
            <div class="flex items-center space-x-3 text-white mb-4">
                <a href="{{ route('admin.dashboard') }}" class="p-1 hover:bg-white/10 rounded-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-xl font-bold tracking-wide">Laporan Harian Karyawan</h2>
            </div>
        </header>

        <main class="p-4 -mt-10 relative z-20 space-y-6">
            
            {{-- Bagian Filter --}}
            <section class="bg-white p-5 rounded-xl shadow-lg relative z-30 border border-gray-100">
                <form action="{{ route('admin.report.index') }}" method="GET" id="filter-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Filter Tanggal --}}
                        <div>
                            <label for="filter_tanggal" class="block mb-1.5 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                Filter Tanggal
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-indigo-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/>
                                        <path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input type="text" id="filter_tanggal" name="tanggal"
                                    class="bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10 p-2.5 transition-shadow duration-200 focus:shadow-md"
                                    placeholder="Pilih Tanggal"
                                    value="{{ $selected_date }}">
                            </div>
                        </div>

                        {{-- Filter Nama Karyawan --}}
                        <div>
                             <label for="search_karyawan" class="block mb-1.5 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                Cari Karyawan
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="text" id="search_karyawan" name="search"
                                    class="bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10 p-2.5 transition-shadow duration-200 focus:shadow-md"
                                    placeholder="Nama karyawan..."
                                    value="{{ $search_query ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        @if (($selected_date && $selected_date !== 'all') || $search_query)
                            <a href="{{ route('admin.report.index', ['tanggal' => 'all']) }}"
                                class="inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-600 text-sm font-semibold py-2 px-4 rounded-lg hover:bg-gray-200 transition-all">
                                <i class="fa-solid fa-rotate-left"></i> Reset
                            </a>
                        @endif
                        <button type="submit" class="inline-flex items-center justify-center gap-2 bg-indigo-600 text-white text-sm font-semibold py-2 px-5 rounded-lg shadow-md hover:bg-indigo-700 active:scale-95 transition-all">
                            <i class="fa-solid fa-filter"></i> Terapkan
                        </button>
                    </div>
                </form>
            </section>

            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}",
                            timer: 1500, showConfirmButton: false, toast: true, position: 'top-end'
                        });
                    });
                </script>
            @endif

            {{-- DAFTAR KARTU LAPORAN --}}
            <section class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h2 class="text-lg font-bold text-gray-800">Daftar Laporan</h2>
                    <span class="text-xs font-medium text-gray-500 bg-white px-2 py-1 rounded-md shadow-sm border border-gray-100">
                        {{ $reports->total() }} Data
                    </span>
                </div>

                @forelse ($reports as $report)
                    @php
                        $linkAttachment = $report->attachments->where('type', 'link')->first();
                        $fileAttachment = $report->attachments->where('type', 'file')->first();
                        
                        // Foto Profil Karyawan
                        $fotoKaryawan = 'https://placehold.co/40x40?text=' . substr($report->employee->name, 0, 1);
                        if ($report->employee->karyawan && $report->employee->karyawan->foto_profil) {
                            $fotoKaryawan = asset('storage/' . $report->employee->karyawan->foto_profil);
                        }

                        // Warna Status
                        $statusColor = 'yellow'; // Default Pending
                        $statusIcon = 'fa-hourglass-half';
                        $statusLabel = 'Menunggu';
                        
                        if($report->status == 'approved') {
                            $statusColor = 'green';
                            $statusIcon = 'fa-circle-check';
                            $statusLabel = 'Diterima';
                        } elseif($report->status == 'rejected') {
                            $statusColor = 'red';
                            $statusIcon = 'fa-circle-xmark';
                            $statusLabel = 'Ditolak';
                        }
                    @endphp

                    {{-- KARTU LAPORAN --}}
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 relative group">
                        
                        {{-- Strip Warna Status --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-{{ $statusColor }}-500"></div>

                        <div class="p-5 pl-6"> 
                            
                            {{-- Header: Profil Karyawan & Status --}}
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $fotoKaryawan }}" alt="Foto" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 leading-tight">{{ $report->employee->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $report->report_date->isoFormat('dddd, D MMM YYYY • HH:mm') }}</p>
                                    </div>
                                </div>
                                
                                {{-- Badge Status --}}
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-{{ $statusColor }}-50 text-{{ $statusColor }}-600 border border-{{ $statusColor }}-100">
                                    <i class="fa-solid {{ $statusIcon }}"></i> {{ $statusLabel }}
                                </span>
                            </div>

                            {{-- Konten Laporan --}}
                            <h3 class="font-bold text-gray-800 text-base mb-1">{{ $report->title }}</h3>
                            <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                {{ $report->description }}
                            </p>

                            {{-- Komentar Admin (Jika ada) --}}
                            @if($report->admin_comment)
                                <div class="mt-2 mb-4 p-3 bg-gray-50 border-l-4 border-indigo-500 rounded-r-lg flex items-start gap-3">
                                    <i class="fa-solid fa-comment-dots text-indigo-400 mt-1"></i>
                                    <div class="text-sm text-gray-700">
                                        <span class="font-bold text-xs uppercase text-indigo-600 block mb-0.5">Komentar Anda:</span>
                                        "{{ $report->admin_comment }}"
                                    </div>
                                </div>
                            @endif

                            {{-- Footer: Lampiran & Tombol Verifikasi --}}
                            <div class="border-t border-gray-50 pt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                
                                {{-- Lampiran --}}
                                <div class="flex flex-wrap gap-2">
                                    @if ($linkAttachment)
                                        <a href="{{ $linkAttachment->url_or_path }}" target="_blank" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-medium rounded-lg hover:bg-blue-100 transition border border-blue-100">
                                            <i class="fa-brands fa-figma"></i> Link
                                        </a>
                                    @endif
                                    @if ($fileAttachment)
                                        <a href="{{ Storage::url($fileAttachment->url_or_path) }}" target="_blank" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-600 text-xs font-medium rounded-lg hover:bg-green-100 transition border border-green-100">
                                            <i class="fa-solid fa-paperclip"></i> File
                                        </a>
                                    @endif
                                    @if (!$linkAttachment && !$fileAttachment)
                                       <span class="text-xs text-gray-400 italic flex items-center gap-1">
                                            <i class="fa-regular fa-file-excel"></i> Tanpa lampiran
                                       </span>
                                    @endif
                                </div>

                                {{-- Tombol Verifikasi (Modal Trigger) --}}
                                <button type="button" 
                                    data-report-id="{{ $report->id }}"
                                    data-title="{{ $report->title }}"
                                    data-employee="{{ $report->employee->name }}"
                                    data-status="{{ strtolower($report->status) }}"
                                    data-comment="{{ $report->admin_comment }}"
                                    data-modal-target="verifikasi-modal" data-modal-toggle="verifikasi-modal" 
                                    class="open-verify-modal inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition shadow-md active:scale-95">
                                    <i class="fa-solid fa-clipboard-check"></i> Verifikasi
                                </button>
                            </div>
                        </div>
                    </div>

                @empty
                    <div class="bg-white p-8 rounded-xl text-center shadow-sm border border-dashed border-gray-300">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-50 rounded-full mb-3 text-gray-400">
                            <i class="fa-regular fa-folder-open text-xl"></i>
                        </div>
                        <p class="text-gray-500 text-sm">Tidak ada laporan ditemukan.</p>
                    </div>
                @endforelse

                @if ($reports->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $reports->links('pagination::tailwind') }}
                    </div>
                @endif
            </section>
        </main>
    </div>

    {{-- MODAL VERIFIKASI (UPDATE STATUS & KOMENTAR) --}}
    <div id="verifikasi-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden">
                
                <div class="flex items-center justify-between p-4 border-b bg-gray-50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Verifikasi Laporan</h3>
                        <p class="text-xs text-gray-500" id="modal-employee-name">Nama Karyawan</p>
                    </div>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="verifikasi-modal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>

                {{-- Form Update Status --}}
                <form id="verify-form" method="POST" class="p-5 space-y-5">
                    @csrf
                    @method('PATCH')

                    {{-- Pilihan Status --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Tentukan Status</label>
                        <div class="grid grid-cols-3 gap-3">
                            {{-- Diterima --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="approved" class="peer sr-only">
                                <div class="p-2 rounded-lg border border-gray-200 text-center peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition">
                                    <i class="fa-solid fa-check-circle text-green-500 text-xl mb-1 block"></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-green-700">Diterima</span>
                                </div>
                            </label>
                            {{-- Pending --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="pending" class="peer sr-only">
                                <div class="p-2 rounded-lg border border-gray-200 text-center peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:bg-gray-50 transition">
                                    <i class="fa-solid fa-clock text-yellow-500 text-xl mb-1 block"></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-yellow-700">Pending</span>
                                </div>
                            </label>
                            {{-- Ditolak --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="rejected" class="peer sr-only">
                                <div class="p-2 rounded-lg border border-gray-200 text-center peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50 transition">
                                    <i class="fa-solid fa-circle-xmark text-red-500 text-xl mb-1 block"></i>
                                    <span class="text-xs font-bold text-gray-600 peer-checked:text-red-700">Ditolak</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Komentar --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Komentar / Alasan <span class="font-normal text-gray-400 text-xs">(Opsional)</span></label>
                        <textarea id="modal-comment" name="admin_comment" rows="3" 
                            class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400" 
                            placeholder="Tulis alasan jika ditolak atau catatan tambahan..."></textarea>
                    </div>

                    <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-bold rounded-lg text-sm px-5 py-3 text-center shadow-md transition-all">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Script Flatpickr & Modal Logic --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Flatpickr
            const filterInput = document.getElementById('filter_tanggal');
            const filterForm = document.getElementById('filter-form');
            
            flatpickr(filterInput, {
                dateFormat: "Y-m-d", altInput: true, altFormat: "j F Y", defaultDate: filterInput.value || null,
                onClose: function(selectedDates, dateStr) { if (dateStr) filterForm.submit(); }
            });

            // Modal Verifikasi Logic
            document.querySelectorAll('.open-verify-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const reportId = this.dataset.reportId;
                    const employeeName = this.dataset.employee;
                    const status = this.dataset.status;
                    const comment = this.dataset.comment;

                    // Set Form Action URL
                    const form = document.getElementById('verify-form');
                   form.action = `/admin/report/${reportId}/status`; // Pastikan route sesuai di web.php

                    // Set Data ke Modal
                    document.getElementById('modal-employee-name').textContent = employeeName;
                    document.getElementById('modal-comment').value = comment;

                    // Set Radio Button
                    const radios = form.querySelectorAll('input[name="status"]');
                    radios.forEach(radio => {
                        if(radio.value === status) {
                            radio.checked = true;
                        }
                    });
                });
            });
        });
    </script>
</x-admin-layout>