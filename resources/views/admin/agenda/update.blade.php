<x-admin-layout>
    <x-slot:title>
        Edit
    </x-slot:title>

    <style>
        /* Styling tambahan untuk custom select dropdown agar lebih mirip input */
        .custom-select-wrapper {
            position: relative;
        }

        .custom-select-wrapper .dropdown-panel {
            max-height: 240px;
            overflow-y: auto;
            z-index: 50;
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        .custom-select-wrapper .dropdown-panel li {
            padding: 8px 12px;
            display: flex;
            align-items: center;
            color: #1f2937;
        }

        .custom-select-wrapper .dropdown-panel label {
            color: #1f2937;
        }

        .custom-select-wrapper .dropdown-panel li:hover {
            background-color: #f3f4f6;
        }

        /* Styling untuk placeholder pada trigger */
        .custom-select-wrapper .trigger-placeholder {
            color: #6b7280;
        }

        /* 💡 NEW: Tambahkan min-height pada tombol agar chip terlihat */
        #dropdownDivisiTrigger, #dropdownKaryawanTrigger {
            min-height: 44px; /* Tinggi minimum standar input */
            align-items: flex-start; /* Pastikan konten dimulai dari atas */
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

            {{-- BLOK 1: Detail Agenda (Judul & Tanggal) --}}
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
                        <label for="tanggal_agenda_input" class="block mb-1 text-sm font-medium text-gray-500">Tanggal Agenda</label>
                        {{-- 💡 PERBAIKAN: Gunakan ID baru, Hapus atribut Flowbite, dan set VALUE ke format M/D/Y --}}
                        <input type="text"
                            id="tanggal_agenda_input"
                            name="tanggal_agenda"
                            {{-- KRITIS: Output format M/D/Y agar Flatpickr mem-parsingnya dengan benar dan sesuai validasi --}}
                            value="{{ old('tanggal_agenda', $agenda->tanggal_agenda ? \Carbon\Carbon::parse($agenda->tanggal_agenda)->format('m/d/Y') : '') }}"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Pilih Tanggal">
                        <div class="absolute inset-y-0 end-0 top-6 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/>
                                <path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BLOK 2: PESERTA UNDANGAN (CUSTOM MULTI-SELECT WITH CHIP DISPLAY) --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-lg font-bold text-gray-800 mb-5">👥 Peserta Undangan</h2>
                <div class="space-y-6">

                    @php
                        $selectedDivisi = old('peserta_divisi')
                                                 ? (array)old('peserta_divisi')
                                                 : $selectedDivisiIds;

                        $selectedKaryawan = old('peserta_karyawan')
                                                 ? (array)old('peserta_karyawan')
                                                 : $selectedKaryawanIds;
                    @endphp

                    {{-- Custom Multi-Select untuk Divisi --}}
                    <div>
                        <label for="peserta_divisi_hidden" class="block mb-2 text-sm font-medium text-gray-700">1. Berdasarkan Divisi</label>
                        <div class="relative custom-select-wrapper">
                            <button type="button" class="flex flex-wrap items-center justify-between w-full px-4 py-2 text-left bg-white border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="dropdownDivisiTrigger">
                                <span class="text-gray-900 trigger-summary flex-grow" data-placeholder="Pilih Divisi..."></span>
                                <svg class="w-4 h-4 text-gray-400 self-start mt-1 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <select multiple name="peserta_divisi[]" id="peserta_divisi_hidden" class="hidden">
                                @foreach ($divisis as $divisi)
                                    <option value="{{ $divisi->id }}" {{ in_array($divisi->id, $selectedDivisi) ? 'selected' : '' }}>
                                        {{ $divisi->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="absolute w-full mt-1 dropdown-panel hidden" id="dropdownDivisiPanel">
                                <div class="p-2">
                                    <input type="text" placeholder="Cari divisi..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900"
                                            id="searchDivisiInput">
                                </div>
                                <ul class="options-list" id="divisiOptionsList">
                                    @foreach ($divisis as $divisi)
                                        <li data-value="{{ $divisi->id }}" data-text="{{ $divisi->name }}">
                                            <input type="checkbox" id="divisi-checkbox-{{ $divisi->id }}" value="{{ $divisi->id }}"
                                                    class="mr-2 rounded text-blue-600 focus:ring-blue-500"
                                                    {{ in_array($divisi->id, $selectedDivisi) ? 'checked' : '' }}>
                                            <label for="divisi-checkbox-{{ $divisi->id }}" class="flex-grow">{{ $divisi->name }}</label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Semua karyawan di divisi yang dipilih akan diundang.</p>
                    </div>

                    {{-- Custom Multi-Select untuk Perorangan --}}
                    <div>
                        <label for="peserta_karyawan_hidden" class="block mb-2 text-sm font-medium text-gray-700">2. Perorangan</label>
                        <div class="relative custom-select-wrapper">
                            <button type="button" class="flex flex-wrap items-center justify-between w-full px-4 py-2 text-left bg-white border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="dropdownKaryawanTrigger">
                                <span class="text-gray-900 trigger-summary flex-grow" data-placeholder="Cari dan pilih Karyawan..."></span>
                                <svg class="w-4 h-4 text-gray-400 self-start mt-1 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <select multiple name="peserta_karyawan[]" id="peserta_karyawan_hidden" class="hidden">
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}" {{ in_array($karyawan->id, $selectedKaryawan) ? 'selected' : '' }}>
                                        {{ $karyawan->user->name ?? 'Tanpa Nama' }}
                                        @if ($karyawan->divisi)
                                            — {{ $karyawan->divisi->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            <div class="absolute w-full mt-1 dropdown-panel hidden" id="dropdownKaryawanPanel">
                                <div class="p-2">
                                    <input type="text" placeholder="Cari karyawan..."
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-900"
                                            id="searchKaryawanInput">
                                </div>
                                <ul class="options-list" id="karyawanOptionsList">
                                    @foreach ($karyawans as $karyawan)
                                        <li data-value="{{ $karyawan->id }}" data-text="{{ $karyawan->user->name ?? 'Tanpa Nama' }} @if ($karyawan->divisi) — {{ $karyawan->divisi->name }} @endif">
                                            <input type="checkbox" id="karyawan-checkbox-{{ $karyawan->id }}" value="{{ $karyawan->id }}"
                                                    class="mr-2 rounded text-blue-600 focus:ring-blue-500"
                                                    {{ in_array($karyawan->id, $selectedKaryawan) ? 'checked' : '' }}>
                                            <label for="karyawan-checkbox-{{ $karyawan->id }}" class="flex-grow">
                                                {{ $karyawan->user->name ?? 'Tanpa Nama' }}
                                                @if ($karyawan->divisi)
                                                    — {{ $karyawan->divisi->name }}
                                                @endif
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Pilih karyawan tertentu di luar divisi (jika ada).</p>
                    </div>

                </div>
            </div>

            {{-- BLOK 3: Detail Agenda Lanjutan --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <div class="space-y-4">
                    <div>
                        <label for="waktu_mulai" class="block mb-1 text-sm font-medium text-gray-700">Waktu Mulai <span class="text-gray-400">(Opsional)</span></label>
                        <input type="time" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai', $agenda->waktu_mulai ?? '') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <div>
                        <label for="waktu_selesai" class="block mb-1 text-sm font-medium text-gray-700">Waktu Selesai <span class="text-gray-400">(Opsional)</span></label>
                        <input type="time" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai', $agenda->waktu_selesai ?? '') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="lokasi_alamat" class="block mb-1 text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" id="lokasi_alamat" name="lokasi_alamat" value="{{ old('lokasi_alamat', $agenda->lokasi_alamat ?? '') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Misal:  Alamat(Jl.Kampung Melayu) ">
                    </div>

                    <div>
                        <label for="ruang" class="block mb-1 text-sm font-medium text-gray-700">Ruangan</label>
                        <input type="text" id="ruang" name="ruang" value="{{ old('ruang', $agenda->ruang ?? '') }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Misal: Ruang Rapat Lt. 2 / Zoom Meeting">
                    </div>

                    <div>
                        <label for="catatan" class="block mb-1 text-sm font-medium text-gray-700">Catatan <span class="text-gray-400">(Opsional)</span></label>
                        <textarea id="catatan" name="catatan" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Detail tambahan mengenai agenda...">{{ old('catatan', $agenda->catatan ?? '') }}</textarea>
                    </div>
                    <input type="hidden" name="target" value="semua">
                </div>
            </div>

            <button type="submit" class=" btnEditAgenda btn-indi">
                Simpan Agenda
            </button>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // 💡 PERBAIKAN TANGGAL: Inisialisasi Flatpickr
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil nilai yang sudah ada dari input
            const dateInput = document.getElementById('tanggal_agenda_input');
            const initialDate = dateInput ? dateInput.value : '';

            // Inisialisasi Flatpickr
            flatpickr("#tanggal_agenda_input", {
                // Format yang dikirimkan ke SERVER (M/D/Y) -> HARUS SAMA DENGAN VALIDASI LARAVEL
                dateFormat: "m/d/Y",

                // Aktifkan Alternate Input
                altInput: true,

                // Format yang DILIHAT PENGGUNA (d M Y)
                altFormat: "d M Y",

                // Set defaultDate ke nilai M/D/Y yang sudah diparsing di Blade
                defaultDate: initialDate || 'today',
            });
        });

        $(document).ready(function() {
            // Fungsi untuk menginisialisasi custom multi-select
            function initializeCustomMultiSelect(triggerId, panelId, searchInputId, optionsListId, hiddenSelectId) {
                const $trigger = $(`#${triggerId}`);
                const $panel = $(`#${panelId}`);
                const $searchInput = $(`#${searchInputId}`);
                const $optionsList = $(`#${optionsListId}`);
                const $hiddenSelect = $(`#${hiddenSelectId}`);
                const $summarySpan = $trigger.find('.trigger-summary');
                const placeholder = $summarySpan.data('placeholder');

                // 💡 FUNGSI BARU: Update summary untuk menampilkan chips
                function updateSummary() {
                    const selectedOptions = $hiddenSelect.find('option:selected');
                    $summarySpan.empty(); // Kosongkan ringkasan

                    if (selectedOptions.length === 0) {
                        // Tampilkan placeholder
                        $summarySpan.html(`<span class="trigger-placeholder">${placeholder}</span>`);
                        $trigger.removeClass('items-start').addClass('items-center');
                    } else {
                        // Tampilkan chips
                        selectedOptions.each(function() {
                            const text = $(this).text().trim();
                            // Chip HTML dengan styling Tailwind
                            const chip = `<span class="inline-flex items-center text-sm font-medium bg-indigo-100 text-indigo-800 rounded px-2 py-0.5 mr-2 mb-0.5">
                                            ${text}
                                          </span>`;
                            $summarySpan.append(chip);
                        });
                        $summarySpan.removeClass('trigger-placeholder');
                        $trigger.removeClass('items-center').addClass('items-start');
                    }
                }

                // Inisialisasi summary saat pertama kali dimuat
                updateSummary();

                // Toggle panel ketika trigger diklik
                $trigger.on('click', function(e) {
                    e.stopPropagation();
                    $panel.toggleClass('hidden');
                    $searchInput.focus();
                });

                // Sembunyikan panel jika klik di luar
                $(document).on('click', function(e) {
                    if (!$panel.is(e.target) && $panel.has(e.target).length === 0 &&
                        !$trigger.is(e.target) && $trigger.has(e.target).length === 0) {
                        $panel.addClass('hidden');
                    }
                });

                // Filter opsi ketika input pencarian berubah
                $searchInput.on('keyup', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    $optionsList.find('li').each(function() {
                        const optionText = $(this).data('text').toLowerCase();
                        if (optionText.includes(searchTerm)) {
                            $(this).removeClass('hidden');
                        } else {
                            $(this).addClass('hidden');
                        }
                    });
                });

                // Handle perubahan checkbox
                $optionsList.on('change', 'input[type="checkbox"]', function() {
                    const value = $(this).val();
                    const isChecked = $(this).is(':checked');

                    // Perbarui hidden <select>
                    $hiddenSelect.find(`option[value="${value}"]`).prop('selected', isChecked);

                    updateSummary();
                });

                // Pastikan checkbox sesuai dengan selected di hidden select saat inisialisasi
                $hiddenSelect.find('option').each(function() {
                    const value = $(this).val();
                    const isSelected = $(this).is(':selected');
                    $optionsList.find(`input[type="checkbox"][value="${value}"]`).prop('checked', isSelected);
                });
            }

            // Inisialisasi untuk Divisi
            initializeCustomMultiSelect(
                'dropdownDivisiTrigger',
                'dropdownDivisiPanel',
                'searchDivisiInput',
                'divisiOptionsList',
                'peserta_divisi_hidden'
            );

            // Inisialisasi untuk Karyawan
            initializeCustomMultiSelect(
                'dropdownKaryawanTrigger',
                'dropdownKaryawanPanel',
                'searchKaryawanInput',
                'karyawanOptionsList',
                'peserta_karyawan_hidden'
            );
        });
    </script>
</x-admin-layout>
