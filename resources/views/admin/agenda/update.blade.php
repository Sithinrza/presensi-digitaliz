<x-admin-layout>
    <x-slot:title>
        Edit
    </x-slot:title>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- 2. CUSTOM CSS UNTUK INTEGRASI DENGAN TAILWIND/FLOWBITE -->
    <style>
        .select2-container .select2-selection--multiple {
            min-height: 44px;
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 4px 10px 0 10px;
            background-color: white;
            width: 100% !important;
            transition: all 0.2s;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding-top: 5px;
        }

        .select2-container.select2-container--focus .select2-selection--multiple {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 1px #3b82f6 !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #a682f2;
            border: 1px solid #4f46e5;
            color: white;
            padding: 0 8px;
            margin-top: 5px;
            margin-right: 5px;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            line-height: 24px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            opacity: 0.7;
            margin-right: 4px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #d1d5db;
            opacity: 1;
        }

        .select2-results__options {
            list-style: none;
            margin: 0;
            padding: 0;
            max-height: 300px;
            overflow-y: auto;
        }

        .select2-results__option {
            padding: 6px 12px;
            cursor: pointer;
            user-select: none;
        }

        .select2-results__option--highlighted {
            background-color: #e0f2fe !important;
            color: #1e3a8a !important;
        }

        .select2-results__option[aria-selected=true] {
            background-color: #f3f4f6;
        }

        .select2-dropdown {
            z-index: 99999;
        }

        .select2-container {
            width: 100% !important;
        }

        /* 🔧 Perbaikan kontras teks */
        .select2-container--default .select2-selection--multiple .select2-selection__rendered li {
            color: #111827 !important;
        }

        .select2-selection__placeholder {
            color: #6b7280 !important;
        }

        .select2-results__option {
            color: #111827 !important;
            background-color: #fff !important;
        }

        .select2-results__option--highlighted {
            background-color: #e0f2fe !important;
            color: #1e3a8a !important;
        }

        /* Sedikit polesan biar lebih Tailwind-feel */
        .select2-container--default .select2-selection--multiple {
            background-color: #f9fafb;
        }

        .select2-container--default .select2-selection--multiple:focus {
            border-color: #6366f1 !important;
        }
    </style>
    <header class="bg-indigo-950 text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto flex items-center p-4">
            <a href="{{ route('admin.agenda.index') }}" class="p-2 mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-lg font-semibold flex-grow text-center mr-10">
                Edit Agenda
            </h1>
        </div>
    </header>
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg mb-4">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main class="p-4 space-y-6 pb-24">
        <form action="{{ route('admin.agenda.update', $agenda->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-lg font-bold text-gray-800 mb-5">Detail Agenda</h2>
                <div class="space-y-4">
                    <div>
                        <label for="judul" class="block mb-1 text-sm font-medium text-gray-700">Judul Agenda</label>
                        <input type="text" id="judul" name="judul"
                               value="{{ old('judul', $agenda->judul ?? '') }}"
                               class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Misal: Rapat Evaluasi Bulanan" required>
                        @error('judul') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="relative">
                         <label for="tanggal_agenda" class="block mb-1 text-sm font-medium text-gray-500">Tanggal Agenda</label>
                         <input datepicker datepicker-autohide type="text" id="tanggal_agenda" name="tanggal_agenda" value="{{ old('tanggal_agenda', $agenda->tanggal_agenda ? \Carbon\Carbon::parse($agenda->tanggal_agenda)->format('m/d/Y') : '') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Pilih Tanggal">
                         <div class="absolute inset-y-0 end-0 top-6 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/>
                                <path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                         </div>
                    </div>

                     <div class="bg-white p-6 rounded-2xl shadow-lg">
                        <h2 class="text-lg font-bold text-gray-800 mb-5">👥 Peserta Undangan</h2>
                        <div class="space-y-6">

                            {{-- Logika untuk menyimpan pilihan lama saat validasi gagal (old()) atau saat pertama kali edit (selectedDivisiIds) --}}
                            @php
                                // Gabungkan data lama ($selectedDivisiIds) dengan data yang baru di-submit (old)
                                $selectedDivisi = old('peserta_divisi')
                                                    ? (array)old('peserta_divisi')
                                                    : $selectedDivisiIds;

                                $selectedKaryawan = old('peserta_karyawan')
                                                    ? (array)old('peserta_karyawan')
                                                    : $selectedKaryawanIds;
                            @endphp

                            <div>
                                <label for="peserta_divisi" class="block mb-2 text-sm font-medium text-gray-700">1. Berdasarkan Divisi</label>
                                <select multiple id="peserta_divisi" name="peserta_divisi[]"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full">
                                    <option value="" disabled>Cari dan pilih Divisi...</option>
                                    @foreach ($divisis as $divisi)
                                        <option value="{{ $divisi->id }}"
                                            {{ in_array($divisi->id, $selectedDivisi) ? 'selected' : '' }}>
                                            {{ $divisi->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-gray-500">Semua karyawan di divisi yang dipilih akan diundang.</p>
                            </div>

                            <div>
                                <label for="peserta_karyawan" class="block mb-2 text-sm font-medium text-gray-700">2. Perorangan</label>
                                <select multiple id="peserta_karyawan" name="peserta_karyawan[]"
                                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full">
                                    <option value="" disabled>Cari dan pilih Karyawan...</option>
                                    @foreach ($karyawans as $karyawan)
                                        <option value="{{ $karyawan->id }}"
                                            {{ in_array($karyawan->id, $selectedKaryawan) ? 'selected' : '' }}>
                                            {{ $karyawan->user->name ?? 'Tanpa Nama' }}
                                            @if ($karyawan->divisi)
                                                — {{ $karyawan->divisi->name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-gray-500">Pilih karyawan tertentu di luar divisi (jika ada).</p>
                            </div>

                        </div>
                    </div>
                     <div>
                        <label for="waktu_mulai" class="block mb-1 text-sm font-medium text-gray-700">Waktu Mulai <span class="text-gray-400">(Opsional)</span></label>
                        <input type="time" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai', $agenda->waktu_mulai ?? '') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                     <div>
                        <label for="waktu_selesai" class="block mb-1 text-sm font-medium text-gray-700">Waktu Selesai <span class="text-gray-400">(Opsional)</span></label>
                        <input type="time" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai', $agenda->waktu_selesai ?? '') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="lokasi" class="block mb-1 text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" id="lokasi_alamat" name="lokasi_alamat" value="{{ old('lokasi_alamat', $agenda->lokasi_alamat ?? '') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Misal:  Alamat(Jl.Kampung Melayu) ">
                    </div>

                    <div>
                        <label for="lokasi" class="block mb-1 text-sm font-medium text-gray-700">Ruangan</label>
                        <input type="text" id="ruang" name="ruang" value="{{ old('ruang', $agenda->ruang ?? '') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Misal: Ruang Rapat Lt. 2 / Zoom Meeting">
                    </div>

                    <div>
                        <label for="catatan" class="block mb-1 text-sm font-medium text-gray-700">Catatan <span class="text-gray-400">(Opsional)</span></label>
                        <textarea id="catatan" name="catatan" value="{{ old('catatan', $agenda->catatan ?? '') }}" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Detail tambahan mengenai agenda..."></textarea>
                    </div>
                     <input type="hidden" name="target" value="semua">

                </div>
            </div>

            <!-- Tombol Simpan -->
            <button type="submit" class="btn-indi">
                Simpan Agenda
            </button>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

     <script>
        $(document).ready(function() {

            // Select2
            $('#peserta_divisi').select2({
                placeholder: "Cari dan pilih Divisi...",
                allowClear: true,
                width: '100%'
            });

            $('#peserta_karyawan').select2({
                placeholder: "Cari dan pilih Karyawan...",
                allowClear: true,
                width: '100%'
            });
        });
        
    </script>
</x-admin-layout>
