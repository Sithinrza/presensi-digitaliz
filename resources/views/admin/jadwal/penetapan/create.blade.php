<x-admin-layout>
{{--
|--------------------------------------------------------------------------
| FILE: create.blade.php (Khusus untuk menambah data baru)
|--------------------------------------------------------------------------
| Variabel yang diharapkan dari Controller: $karyawans, $jadwalKerjas
--}}

@php
// Tetapkan nilai statis untuk mode Create
$formTitle = 'Tetapkan Jadwal Karyawan Baru';
$formAction = route('admin.penetapan.store');
$karyawanIdSaatIni = old('id_karyawan');
$jadwalIdSaatIni = old('id_jadwal_kerja');
@endphp

<div class="container mx-auto p-4 md:p-8">

<header class="bg-indigo-950 p-6 pt-10 pb-28 rounded-b-3xl shadow-xl relative z-10 -mx-4 md:mx-0">
    <div class="flex items-center space-x-4 text-white max-w-4xl mx-auto md:mx-0">
        <a href="{{ route('admin.penetapan.index') }}" class="group p-3 bg-white/10 rounded-full hover:bg-white/20 transition-all backdrop-blur-md border border-white/10 shadow-lg">
            <i class="fa-solid fa-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight">{{ $formTitle }}</h1>
            <p class="text-indigo-200 text-sm mt-0.5 font-medium">Atur karyawan dan template jadwal kerjanya.</p>
        </div>
    </div>
</header>

<main class="px-4 -mt-20 relative z-20 max-w-4xl mx-auto">

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-md mb-6" role="alert">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-xmark mr-3 text-xl"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <form action="{{ $formAction }}" method="POST" class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        @csrf
        {{-- HILANGKAN: @method('PUT') --}}

        <div class="p-6 md:p-8 space-y-8">

            <section>
                <label for="id_karyawan" class="block text-sm font-bold text-gray-700 mb-3">Nama Karyawan</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-indigo-500"></i>
                    </div>
                    {{-- HILANGKAN: {{ $isEdit ? 'disabled' : '' }} --}}
                    <select name="id_karyawan" id="id_karyawan"
                        class="w-full pl-11 pr-10 py-4 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all appearance-none outline-none text-sm font-bold text-gray-700 cursor-pointer shadow-sm @error('id_karyawan') border-red-500 @enderror">
                        <option value="" disabled {{ !$karyawanIdSaatIni ? 'selected' : '' }}>-- Pilih Karyawan --</option>
                        @foreach ($karyawans as $karyawan)
                            <option value="{{ $karyawan->id }}"
                                {{ $karyawan->id == $karyawanIdSaatIni ? 'selected' : '' }}>

                                {{-- PERBAIKAN FINAL: Hanya tampilkan name, atau name + jabatan jika ada --}}
                                {{ $karyawan->nama_lengkap ?? 'Nama Tidak Ditemukan' }}
                                @if (isset($karyawan->jabatan->name))
                                    - {{ $karyawan->jabatan->name }}
                                @endif

                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                    @error('id_karyawan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                {{-- HILANGKAN: Input hidden dan pesan 'Nama karyawan tidak dapat diubah saat mode edit relasi.' --}}
            </section>

            <section>
                <label for="id_jadwal_kerja" class="block text-sm font-bold text-gray-700 mb-3">Template Jadwal Kerja</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-calendar-alt text-green-500"></i>
                    </div>
                    <select name="id_jadwal_kerja" id="id_jadwal_kerja"
                        class="w-full pl-11 pr-10 py-4 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-4 focus:ring-green-500/10 transition-all appearance-none outline-none text-sm font-bold text-gray-700 cursor-pointer shadow-sm @error('id_jadwal_kerja') border-red-500 @enderror">
                        <option value="" disabled {{ !$jadwalIdSaatIni ? 'selected' : '' }}>-- Pilih Template Jadwal --</option>
                        @foreach ($jadwalKerjas as $jadwal)
                            <option value="{{ $jadwal->id }}"
                                {{ $jadwal->id == $jadwalIdSaatIni ? 'selected' : '' }}>
                                {{ $jadwal->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                        <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                    @error('id_jadwal_kerja')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section>
                <h3 class="text-sm font-bold text-gray-700 mb-3">Preview Jadwal Mingguan:</h3>

                <div id="jadwal-detail-preview" class="border border-gray-200 rounded-2xl overflow-hidden bg-white min-h-[200px] flex items-center justify-center text-gray-400">
                    Pilih template jadwal di atas untuk melihat detailnya.
                </div>

                <div class="mt-4 text-xs text-gray-500 p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <i class="fa-solid fa-info-circle mr-1 text-indigo-400"></i>
                    <span class="font-semibold">Catatan:</span> Pembagian GIBS/Wetland (seperti yang ada di template HTML) perlu logika penyimpanan yang lebih kompleks di Controller/Database. Untuk saat ini, hanya template jadwal (jam masuk/pulang) yang disimpan.
                </div>
            </section>

        </div>

        <div class="bg-gray-50 p-6 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-100">
            <a href="{{ route('admin.penetapan.index') }}" class="w-full sm:w-auto text-center px-6 py-3 text-sm font-bold text-gray-600 hover:bg-gray-200 rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all transform active:scale-95">
                Simpan Jadwal
            </button>
        </div>

    </form>
</main>


</div>

@push('scripts')
<script>
// Ambil variabel Blade (harus di luar @verbatim)
const jadwalKerjas = @json($jadwalKerjas);
const hariIndonesia = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

const previewContainer = document.getElementById('jadwal-detail-preview');
const selectJadwal = document.getElementById('id_jadwal_kerja');


@verbatim
function renderJadwalDetail(jadwalId) {
const selectedJadwal = jadwalKerjas.find(j => j.id == jadwalId);

if (!selectedJadwal || selectedJadwal.detail_jadwals.length === 0) {
    previewContainer.innerHTML = '<p class="text-gray-400">Template jadwal ini belum memiliki detail jam kerja.</p>';
    return;
}

// Urutkan detail berdasarkan hari kerja (Senin-Minggu)
// Asumsi: detail_jadwals punya properti 'hari' (string nama hari)
const sortedDetails = selectedJadwal.detail_jadwals.sort((a, b) => {
    return hariIndonesia.indexOf(a.hari) - hariIndonesia.indexOf(b.hari);
});


let html = `
    <div class="w-full divide-y divide-gray-100">
        <div class="hidden md:flex bg-gray-50 border-b border-gray-200 p-4 text-xs font-extrabold text-gray-500 uppercase tracking-wide items-center">
            <div class="w-1/4 pl-2">Hari</div>
            <div class="w-1/4 text-center">Status</div>
            <div class="w-1/2 text-right pr-2">Jam Kerja</div>
        </div>
`;

sortedDetails.forEach(detail => {
    const isKerja = detail.hari_kerja == 1;
    const hariIndex = hariIndonesia.indexOf(detail.hari);
    // Asumsi: Array hariIndonesia hanya sampai Sabtu
    const isWeekend = hariIndex === 5;

    let bgColor = isWeekend && isKerja ? 'bg-orange-50/20 border-l-4 border-orange-300' :
                  isKerja ? 'hover:bg-indigo-50/30' : 'bg-gray-50/50';

    let textColor = isKerja ? 'text-gray-800' : 'text-red-500';
    let statusText = isKerja ? 'Hari Kerja' : 'Libur';
    let statusColor = isKerja ? 'bg-indigo-50 text-indigo-700' : 'bg-red-50 text-red-700';
    let jamKerjaText = isKerja ? `${detail.jam_masuk.substring(0, 5)} - ${detail.jam_pulang.substring(0, 5)}` : '-';
    let jamKerjaIcon = isKerja ? 'fa-regular fa-clock' : 'fa-solid fa-moon';


    html += `
        <div class="group p-4 transition-colors ${bgColor}">
            <div class="flex flex-col md:flex-row md:items-center h-full gap-2">
                <div class="w-full md:w-1/4 flex items-center gap-3">
                    <span class="text-sm font-bold ${textColor}">${detail.hari}</span>
                </div>

                <div class="w-full md:w-1/4">
                    <span class="inline-flex items-center px-3 py-1 text-[10px] font-extrabold rounded-full ${statusColor}">
                        ${statusText}
                    </span>
                </div>

                <div class="w-full md:w-1/2 flex md:justify-end">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-lg border border-gray-200 text-gray-600 text-xs font-bold w-full md:w-auto justify-center shadow-sm">
                        <i class="fa-solid ${jamKerjaIcon} text-indigo-400"></i> ${jamKerjaText}
                    </div>
                </div>
            </div>
        </div>
    `;
});

html += `</div>`;
previewContainer.innerHTML = html;


}

// Event listener untuk perubahan select box
selectJadwal.addEventListener('change', (e) => {
renderJadwalDetail(e.target.value);
});

// Panggil saat halaman pertama kali dimuat (HILANGKAN: Tidak perlu di mode create murni kecuali untuk old data)
// Pada mode create, hanya jalankan jika ada old data yang dipilih (jika validasi gagal)
if (selectJadwal.value) {
renderJadwalDetail(selectJadwal.value);
}
@endverbatim
</script>

@endpush
</x-admin-layout>
