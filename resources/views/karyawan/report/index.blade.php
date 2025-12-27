<x-karyawan-layout>
    <x-slot:title>
        Laporan Saya
    </x-slot:title>

    <div class="relative min-h-screen bg-gray-50 pb-24 font-sans">
        
        {{-- Header Solid (Tanpa Gradasi) --}}
        <header class="bg-indigo-950 pt-8 pb-28 rounded-b-[3rem] shadow-xl relative z-10">
            <div class="relative container mx-auto px-6">
                <div class="flex items-center justify-between text-white mb-2">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('karyawan.dashboard') }}" class="group p-2 bg-white/10 hover:bg-white/20 rounded-xl transition-all duration-300 border border-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <div>
                            <h2 class="text-2xl font-bold tracking-tight">Laporan Harian</h2>
                            <p class="text-indigo-200 text-sm">Kelola aktivitas kerja harianmu di sini.</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="container mx-auto px-4 -mt-20 relative z-20 space-y-8">
            
            {{-- Bagian Filter & Tombol Tambah --}}
            <section class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">

                    {{-- Form Filter --}}
                    <form action="{{ route('karyawan.report.index') }}" method="GET" id="filter-form" class="w-full md:flex-1 order-2 md:order-1">
                        <label for="filter_tanggal" class="block mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider ml-1">
                            Pilih Tanggal
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <i class="fa-regular fa-calendar text-indigo-500"></i>
                            </div>
                            <input type="text" id="filter_tanggal" name="tanggal"
                                class="bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 block w-full ps-10 p-3 transition-all duration-200 hover:bg-white cursor-pointer shadow-sm"
                                placeholder="Filter berdasarkan tanggal..."
                                value="{{ $selected_date }}">
                        </div>
                    </form>

                    {{-- Tombol Aksi --}}
                    <div class="w-full md:w-auto order-1 md:order-2 flex flex-col sm:flex-row gap-3">
                        @if ($selected_date && $selected_date !== 'all')
                            <a href="{{ route('karyawan.report.index', ['tanggal' => 'all']) }}"
                                class="inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-600 border border-gray-200 text-sm font-semibold py-3 px-5 rounded-xl hover:bg-gray-200 transition-all duration-200">
                                <i class="fa-solid fa-rotate-left"></i> <span>Reset</span>
                            </a>
                        @endif

                        <button type="button" data-modal-target="tambah-laporan-modal" data-modal-toggle="tambah-laporan-modal"
                            class="inline-flex items-center justify-center gap-2 bg-indigo-600 text-white text-sm font-bold py-3 px-6 rounded-xl shadow-md hover:bg-indigo-700 hover:shadow-lg active:scale-95 transition-all duration-300">
                            <i class="fa-solid fa-plus"></i>
                            <span>Buat Laporan</span>
                        </button>
                    </div>

                </div>
            </section>

            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}",
                            timer: 2000, showConfirmButton: false, toast: true, position: 'top-end'
                        });
                    });
                </script>
            @endif

            {{-- DAFTAR KARTU LAPORAN --}}
            <section class="space-y-6">
                <div class="flex items-center justify-between px-2 border-b border-gray-200 pb-2">
                    <h2 class="text-xl font-bold text-gray-800 tracking-tight">Riwayat Aktivitas</h2>
                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                        Total: {{ $reports->total() }}
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-5"> 
                @forelse ($reports as $report)
                    @php
                        $linkAttachment = $report->attachments->where('type', 'link')->first();
                        $fileAttachment = $report->attachments->where('type', 'file')->first();
                        
                        // LOGIKA STATUS
                        $statusColor = 'yellow';
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

                    {{-- CARD START --}}
                    <div class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                        
                        {{-- Strip Warna Status --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-{{ $statusColor }}-500 rounded-l-2xl"></div>

                        {{-- Header Card: Judul & Status --}}
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4 pl-2">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 group-hover:text-indigo-700 transition-colors leading-snug">
                                    {{ $report->title }}
                                </h3>
                                <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mt-1">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fa-regular fa-calendar text-indigo-400"></i>
                                        {{ $report->report_date->isoFormat('dddd, D MMM YYYY') }}
                                    </span>
                                    <span class="text-gray-300">•</span>
                                    <span class="flex items-center gap-1">
                                        <i class="fa-regular fa-clock"></i>
                                        {{ $report->report_date->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- STATUS BADGE --}}
                            <span class="self-start inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide bg-{{ $statusColor }}-50 text-{{ $statusColor }}-600 border border-{{ $statusColor }}-100">
                                <i class="fa-solid {{ $statusIcon }}"></i> {{ $statusLabel }}
                            </span>
                        </div>

                        {{-- Body: Deskripsi --}}
                        <div class="mb-5 pl-2">
                            <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ $report->description }}</p>
                        </div>

                        {{-- Komentar Admin --}}
                        @if($report->admin_comment)
                            <div class="mb-5 ml-2 relative overflow-hidden rounded-xl bg-red-50 border border-red-100 p-4">
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-comment-dots text-red-400 mt-1"></i>
                                    <div>
                                        <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest mb-0.5">Catatan Admin:</p>
                                        <p class="text-sm text-gray-800 font-medium italic">"{{ $report->admin_comment }}"</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Footer: Attachments & Actions --}}
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-gray-50 pt-4 mt-2 pl-2">
                            
                            {{-- Attachments --}}
                            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                                @if ($linkAttachment)
                                    <a href="{{ $linkAttachment->url_or_path }}" target="_blank" 
                                       class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg text-xs font-semibold hover:bg-blue-100 transition-colors">
                                        <i class="fa-brands fa-figma"></i> Link
                                    </a>
                                @endif
                                @if ($fileAttachment)
                                    <a href="{{ Storage::url($fileAttachment->url_or_path) }}" target="_blank" 
                                       class="flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-600 border border-green-100 rounded-lg text-xs font-semibold hover:bg-green-100 transition-colors">
                                        <i class="fa-solid fa-paperclip"></i> File
                                    </a>
                                @endif
                                @if (!$linkAttachment && !$fileAttachment)
                                    <span class="text-xs text-gray-400 flex items-center gap-1.5 px-2 py-1">
                                        <i class="fa-solid fa-ban opacity-50"></i> Tidak ada lampiran
                                    </span>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                @if($report->status == 'pending')
                                    <button type="button" 
                                        data-report-id="{{ $report->id }}"
                                        data-title="{{ $report->title }}"
                                        data-description="{{ $report->description }}"
                                        data-link="{{ $linkAttachment->url_or_path ?? '' }}"
                                        data-file-id="{{ $fileAttachment->id ?? '' }}"
                                        data-file-name="{{ $fileAttachment->filename ?? '' }}"
                                        data-modal-target="edit-laporan-modal" data-modal-toggle="edit-laporan-modal" 
                                        class="open-edit-modal flex items-center justify-center w-9 h-9 text-gray-400 bg-gray-50 rounded-lg hover:text-indigo-600 hover:bg-indigo-50 border border-transparent hover:border-indigo-100 transition-all duration-200"
                                        title="Edit Laporan">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    
                                    {{-- TOMBOL HAPUS (DIPERBAIKI) --}}
                                    <button type="button" 
                                        data-delete-url="{{ route('karyawan.report.destroy', $report->id) }}"
                                        data-title="{{ $report->title }}"
                                        class="btn-delete flex items-center justify-center w-9 h-9 text-gray-400 bg-gray-50 rounded-lg hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all duration-200"
                                        title="Hapus Laporan">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                @else
                                    <div class="flex items-center gap-1.5 text-xs font-medium text-gray-400 bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200 cursor-not-allowed opacity-70">
                                        <i class="fa-solid fa-lock text-[10px]"></i>
                                        <span>Terkunci</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <i class="fa-regular fa-folder-open text-3xl text-gray-300"></i>
                        </div>
                        <h3 class="text-gray-900 font-bold text-lg mb-1">Belum ada laporan</h3>
                        <p class="text-gray-500 text-sm mb-6">Mulai buat laporan harianmu sekarang.</p>
                    </div>
                @endforelse
                </div>

                @if ($reports->hasPages())
                    <div class="mt-8">
                        {{ $reports->links('pagination::tailwind') }}
                    </div>
                @endif
            </section>
        </main>
    </div>
    
    {{-- Modal Create --}}
    <div id="tambah-laporan-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-gray-900/30">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Buat Laporan Baru</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-white hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="tambah-laporan-modal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form id="create-form" action="{{ route('karyawan.report.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <label class="block mb-1.5 text-sm font-bold text-gray-700">Judul Laporan</label>
                        <input type="text" name="judul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3" placeholder="Contoh: Desain Login Page" required>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-sm font-bold text-gray-700">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-bold text-gray-700">Link</label>
                            <input type="url" name="link" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-bold text-gray-700">File</label>
                            <input class="block w-full text-sm text-gray-500 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" name="file" type="file">
                        </div>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-bold rounded-xl text-sm px-5 py-3.5 text-center shadow-lg transition-all">Simpan Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 

    {{-- Modal Edit --}}
    <div id="edit-laporan-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-gray-900/30">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Edit Laporan</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-white hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="edit-laporan-modal">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    </button>
                </div>
                <form id="edit-form" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                    @csrf @method('PUT')
                    <input type="hidden" name="attachment_link_id" id="attachment_link_id_edit">
                    <input type="hidden" name="attachment_file_id" id="attachment_file_id_edit">
                    
                    <div>
                        <label class="block mb-1.5 text-sm font-bold text-gray-700">Judul Laporan</label>
                        <input type="text" name="judul_edit" id="judul_edit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3" required>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-sm font-bold text-gray-700">Deskripsi</label>
                        <textarea id="deskripsi_edit" name="deskripsi_edit" rows="4" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-xl border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-bold text-gray-700">Link</label>
                            <input type="url" name="link_edit" id="link_edit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-bold text-gray-700">File</label>
                            <input class="block w-full text-sm text-gray-500 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" id="file_input_edit" name="file_edit" type="file">
                            <p class="text-xs text-gray-500 mt-2">File saat ini: <span class="font-bold text-gray-700" id="current_file_name">Tidak Ada</span></p>
                        </div>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-bold rounded-xl text-sm px-5 py-3.5 text-center shadow-lg transition-all">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Form Hapus Global --}}
    <form id="delete-form-global" action="" method="POST" style="display: none;">
        @csrf @method('DELETE')
    </form>

    {{-- Script --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- 1. SETTING FLATPICKR (TETAP FORMAT d/m/Y sesuai permintaan) ---
            const filterInput = document.getElementById('filter_tanggal');
            const filterForm = document.getElementById('filter-form');
            const initialDateValue = filterInput.getAttribute('value');

            if(filterInput) {
                flatpickr(filterInput, {
                    dateFormat: "d/m/Y", // JANGAN DIUBAH (Sesuai Controller)
                    altInput: true,          
                    altFormat: "j F Y",      
                    defaultDate: initialDateValue || null,
                    onClose: function(selectedDates, dateStr) { 
                        if (dateStr) filterForm.submit(); 
                    }
                });
            }

            // --- 2. MODAL EDIT ---
            document.querySelectorAll('.open-edit-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const reportId = this.dataset.reportId;
                    document.getElementById('edit-form').action = `/karyawan/report/${reportId}`;
                    document.getElementById('judul_edit').value = this.dataset.title;
                    document.getElementById('deskripsi_edit').value = this.dataset.description;
                    document.getElementById('link_edit').value = this.dataset.link;
                    document.getElementById('current_file_name').textContent = this.dataset.fileName || 'Tidak Ada';
                    document.getElementById('attachment_file_id_edit').value = this.dataset.fileId;
                });
            });

            // --- 3. LOGIKA DELETE (DIPERBAIKI) ---
            // Kita pakai Event Listener agar tidak error kena tanda petik di judul
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const deleteUrl = this.dataset.deleteUrl;
                    const title = this.dataset.title;
                    
                    Swal.fire({
                        title: 'Hapus Laporan?',
                        html: `Anda yakin ingin menghapus laporan <br><strong>"${title}"</strong>?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: { popup: 'rounded-2xl' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('delete-form-global');
                            form.action = deleteUrl;
                            form.submit();
                        }
                    });
                });
            });

        });
    </script>
</x-karyawan-layout>