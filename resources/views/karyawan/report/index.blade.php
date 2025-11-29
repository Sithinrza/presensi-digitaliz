
<x-karyawan-layout>
    <x-slot:title>
        report
    </x-slot:title>

    <div class="relative min-h-screen pb-24">
        {{-- ... Header, Tombol Buat Laporan Baru (sama seperti sebelumnya) ... --}}
        <header class="bg-indigo-950 p-4 pb-16 rounded-t-[2.5rem] shadow-lg relative z-10 -mt-1">
            <div class="flex items-center space-x-3 text-white mb-4">
                <a href="{{ route('karyawan.dashboard') }}" class="p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-xl font-bold">Laporan Saya</h2>
            </div>
        </header>

        <main class="p-4 -mt-10 relative z-20 space-y-6">
            <section class="bg-white p-5 rounded-xl shadow-lg relative z-30">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">

                    {{-- Ganti action form ke route index Anda --}}
                    <form action="{{ route('karyawan.report.index') }}" method="GET" id="filter-form" class="w-full md:flex-1 order-2 md:order-1">
                        @csrf
                        <label for="filter_tanggal" class="block mb-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wider relative z-30">
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
                                {{-- Isi dengan nilai filter saat ini --}}
                                value="{{ $selected_date }}">
                        </div>
                    </form>

                    <div class="w-full md:w-auto order-1 md:order-2">
                        <div class="hidden md:block mb-1.5 text-xs">&nbsp;</div>

                        {{-- Tombol 'Lihat Semua Laporan' yang menghilangkan filter tanggal --}}
                        @if ($selected_date && $selected_date !== 'all')
                            <a href="{{ route('karyawan.report.index', ['tanggal' => 'all']) }}"
                                class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-gray-600 text-white text-sm font-semibold py-2.5 px-5 rounded-lg shadow-md hover:bg-gray-700 active:scale-95 transition-all duration-200 mb-4 md:mb-0">
                                <span>Lihat Semua Laporan</span>
                            </a>
                        @endif

                        <button type="button" data-modal-target="tambah-laporan-modal" data-modal-toggle="tambah-laporan-modal"
                            class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-indigo-600 text-white text-sm font-semibold py-2.5 px-5 rounded-lg shadow-md hover:bg-indigo-700 active:scale-95 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Buat Laporan Baru</span>
                        </button>
                    </div>

                </div>
            </section>

            @if(session('success'))
                {{-- Tambahkan notifikasi success --}}
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif

            <section class="pb-4">
                <div class="flex items-center justify-between mb-4 px-1">
                    <h2 class="text-lg font-bold text-gray-800">
                        Riwayat Laporan
                    </h2>
                     <span class="text-sm font-medium text-gray-500">{{ $reports->total() }} Laporan</span>
                </div>

                <div class="space-y-4">

                    @forelse ($reports as $report)
                        {{-- Card Laporan --}}
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" data-report-id="{{ $report->id }}">
                            <div class="p-4 space-y-3">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 bg-indigo-100 text-indigo-700 p-2 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm">{{ $report->title }}</p>
                                            <p class="text-xs font-medium text-gray-500">{{ $report->report_date->isoFormat('dddd, D MMM YYYY - HH:mm') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-700">{{ $report->description }}</p>

                                {{-- Lampiran --}}
                                <div class="space-y-2">
                                    @php
                                        $linkAttachment = $report->attachments->where('type', 'link')->first();
                                        $fileAttachment = $report->attachments->where('type', 'file')->first();
                                    @endphp

                                    @if ($linkAttachment)
                                        <a href="{{ $linkAttachment->url_or_path }}" target="_blank" class="text-xs text-blue-600 hover:underline inline-flex items-center space-x-1 p-2 bg-gray-50 rounded-md">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                                            <span>Link: {{ Str::limit($linkAttachment->url_or_path, 30) }}</span>
                                        </a>
                                    @endif

                                    @if ($fileAttachment)
                                        <a href="{{ Storage::url($fileAttachment->url_or_path) }}" target="_blank" class="text-xs text-green-600 hover:underline inline-flex items-center space-x-1 p-2 bg-gray-50 rounded-md">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.122 2.122l7.81-7.81" /></svg>
                                            <span>File: {{ $fileAttachment->filename }}</span>
                                        </a>
                                    @endif

                                    @if (!$linkAttachment && !$fileAttachment)
                                        <span class="text-xs text-gray-400 italic">Tidak ada lampiran</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Tombol CRUD (Update & Delete) --}}
                            <div class="flex border-t border-gray-200 bg-gray-50">
                                {{-- Tombol Edit: Tambahkan data-* untuk mengisi modal --}}
                                <button type="button"
                                    data-report-id="{{ $report->id }}"
                                    data-title="{{ $report->title }}"
                                    data-description="{{ $report->description }}"
                                    data-link="{{ $linkAttachment->url_or_path ?? '' }}"
                                    data-file-id="{{ $fileAttachment->id ?? '' }}"
                                    data-file-name="{{ $fileAttachment->filename ?? '' }}"
                                    data-modal-target="edit-laporan-modal" data-modal-toggle="edit-laporan-modal"
                                    class="open-edit-modal flex-1 p-3 text-sm font-medium text-blue-600 hover:bg-blue-50 transition-colors duration-200 inline-flex items-center justify-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                    <span>Edit</span>
                                </button>

                                {{-- Tombol Hapus: Tambahkan data-* untuk mengisi modal --}}
                                <button type="button"
                                    data-report-id="{{ $report->id }}"
                                    data-modal-target="hapus-laporan-modal" data-modal-toggle="hapus-laporan-modal"
                                    class="open-delete-modal flex-1 p-3 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors duration-200 inline-flex items-center justify-center space-x-1 border-l border-gray-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.144-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.057-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-6 rounded-xl text-center text-gray-500 italic shadow-lg border border-gray-200">
                            Tidak ada laporan ditemukan untuk filter ini.
                        </div>
                    @endforelse

                </div>

                @if ($reports->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $reports->links('pagination::tailwind') }}
                        {{-- Sesuaikan dengan style pagination yang Anda gunakan --}}
                    </div>
                @endif
            </section>
        </main>
    </div>

    <div id="tambah-laporan-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Buat Laporan Harian Baru
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="tambah-laporan-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                {{-- Ganti action form ke route store --}}
                <form action="{{ route('karyawan.report.store') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-5 space-y-4">
                    @csrf
                    <div>
                        <label for="judul" class="block mb-2 text-sm font-medium text-gray-900">Judul Laporan</label>
                        <input type="text" name="judul" id="judul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5" placeholder="Contoh: Desain Halaman Login" required value="{{ old('judul') }}"/>
                        @error('judul') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Jelaskan apa yang Anda kerjakan..." required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="link" class="block mb-2 text-sm font-medium text-gray-900">Lampiran Tautan (Opsional)</label>
                        <input type="url" name="link" id="link" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5" placeholder="https://figma.com/..." value="{{ old('link') }}"/>
                         @error('link') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900" for="file_input">Lampiran File (Opsional)</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="file_input" name="file" type="file">
                        @error('file') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Laporan</button>
                </form>
            </div>
        </div>
    </div>

    <div id="edit-laporan-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Edit Laporan Harian
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="edit-laporan-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                {{-- Ganti action form ke route update --}}
                <form id="edit-form" method="POST" enctype="multipart/form-data" class="p-4 md:p-5 space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="attachment_link_id" id="attachment_link_id_edit">
                    <input type="hidden" name="attachment_file_id" id="attachment_file_id_edit">

                    <div>
                        <label for="judul_edit" class="block mb-2 text-sm font-medium text-gray-900">Judul Laporan</label>
                        <input type="text" name="judul_edit" id="judul_edit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5" required />
                    </div>
                    <div>
                        <label for="deskripsi_edit" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                        <textarea id="deskripsi_edit" name="deskripsi_edit" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                    </div>
                    <div>
                        <label for="link_edit" class="block mb-2 text-sm font-medium text-gray-900">Lampiran Tautan (Opsional)</label>
                        <input type="url" name="link_edit" id="link_edit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5" placeholder="https://figma.com/..." />
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900" for="file_input_edit">Ubah Lampiran File (Opsional)</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="file_input_edit" name="file_edit" type="file">
                        <p class="text-xs text-gray-500 mt-1">File saat ini: <span class="font-medium" id="current_file_name">Tidak Ada</span></p>
                    </div>

                    <button type="submit" class="w-full text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

    <div id="hapus-laporan-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="hapus-laporan-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500">Apakah Anda yakin ingin menghapus laporan ini?</h3>
                    {{-- Ganti button menjadi form delete --}}
                    <form id="delete-form" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Ya, Hapus
                        </button>
                    </form>
                    <button data-modal-hide="hapus-laporan-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Logika Flatpickr dan Filter Tanggal ---
            const filterInput = document.getElementById('filter_tanggal');
            const filterForm = document.getElementById('filter-form');

            // Konfigurasi Flatpickr
            flatpickr(filterInput, {
                dateFormat: "d/m/Y", // Format yang akan dikirim ke server via input value
                altInput: true,
                altFormat: "j F Y", // Format tampilan
                defaultDate: filterInput.value || null, // Gunakan nilai yang ada atau null (untuk 'Pilih Tanggal')
                allowInput: true,

                onClose: function(selectedDates, dateStr, instance) {
                    // Cek jika nilai input diubah atau tanggal baru dipilih
                    const initialValue = "{{ $selected_date }}";
                    const isDateCleared = dateStr === "";

                    if (isDateCleared && initialValue) {
                        // Jika input dikosongkan, redirect ke 'all'
                        window.location.href = "{{ route('karyawan.report.index', ['tanggal' => 'all']) }}";
                    } else if (dateStr && dateStr !== initialValue) {
                         // Jika tanggal dipilih/berubah, submit form
                        filterForm.submit();
                    }
                },
                // Aktifkan tombol 'clear' Flatpickr (opsional, jika Anda ingin pengguna bisa menghapus filter tanpa klik 'Lihat Semua')
                // wrap: true, // perlu elemen wrapper, atau custom HTML
            });

            // --- Logika Modal Edit (Mengisi data laporan ke modal) ---
            document.querySelectorAll('.open-edit-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const reportId = this.dataset.reportId;
                    const title = this.dataset.title;
                    const description = this.dataset.description;
                    const link = this.dataset.link;
                    const fileId = this.dataset.fileId;
                    const fileName = this.dataset.fileName;

                    // Set action form untuk PUT
                    const form = document.getElementById('edit-form');
                    form.action = `/karyawan/report/${reportId}`; // Sesuaikan dengan URL route Anda

                    // Isi form field
                    document.getElementById('judul_edit').value = title;
                    document.getElementById('deskripsi_edit').value = description;
                    document.getElementById('link_edit').value = link;

                    // Tampilkan file saat ini
                    document.getElementById('current_file_name').textContent = fileName || 'Tidak Ada';

                    // Simpan ID attachment (berguna jika Anda ingin menambahkan logika hapus file/link lama yang lebih spesifik)
                    document.getElementById('attachment_file_id_edit').value = fileId;
                });
            });

            // --- Logika Modal Hapus (Mengisi action form) ---
            document.querySelectorAll('.open-delete-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const reportId = this.dataset.reportId;

                    // Set action form untuk DELETE
                    const form = document.getElementById('delete-form');
                    form.action = `/karyawan/report/${reportId}`; // Sesuaikan dengan URL route Anda
                });
            });
        });
    </script>
</x-karyawan-layout>
