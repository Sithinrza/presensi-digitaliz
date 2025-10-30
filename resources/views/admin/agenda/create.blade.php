<x-karyawan-layout>
    <x-slot:title>
        agenda
    </x-slot:title>
    <header class="bg-indigo-950 text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto flex items-center p-4">
            <a href="{{ route('admin.agenda.index') }}" class="p-2 mr-2"> 
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-lg font-semibold flex-grow text-center mr-10">
                Buat Agenda Baru
            </h1>
        </div>
    </header>
    <main class="p-4 space-y-6 pb-24">
        <form action="#" method="POST" class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-lg font-bold text-gray-800 mb-5">Detail Agenda</h2>
                <div class="space-y-4">
                    <div>
                        <label for="judul" class="block mb-1 text-sm font-medium text-gray-700">Judul Agenda</label>
                        <input type="text" id="judul" name="judul" value="" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Misal: Rapat Evaluasi Bulanan" required>
                    </div>

                    <div>
                        <label for="tanggal_agenda" class="block mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4Z"/><path d="M0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                            </div>
                            <input type="text" id="tanggal_agenda" name="tanggal" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" placeholder="Pilih Tanggal" value="" required>
                        </div>
                    </div>

                     <div>
                        <label for="waktu_mulai" class="block mb-1 text-sm font-medium text-gray-700">Waktu Mulai <span class="text-gray-400">(Opsional)</span></label>
                        <input type="time" id="waktu_mulai" name="waktu_mulai" value="" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                     <div>
                        <label for="waktu_selesai" class="block mb-1 text-sm font-medium text-gray-700">Waktu Selesai <span class="text-gray-400">(Opsional)</span></label>
                        <input type="time" id="waktu_selesai" name="waktu_selesai" value="" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="lokasi" class="block mb-1 text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" value="" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Misal:  Alamat(Jl.Kampung Melayu) ">
                    </div>

                    <div>
                        <label for="lokasi" class="block mb-1 text-sm font-medium text-gray-700">Ruangan</label>
                        <input type="text" id="lokasi" name="lokasi" value="" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Misal: Ruang Rapat Lt. 2 / Zoom Meeting">
                    </div>

                    <div>
                        <label for="deskripsi" class="block mb-1 text-sm font-medium text-gray-700">Catatan <span class="text-gray-400">(Opsional)</span></label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Detail tambahan mengenai agenda..."></textarea>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#tanggal_agenda", {
                dateFormat: "Y-m-d", // Format yyyy-mm-dd
                altInput: true,     // Tampilkan format berbeda ke user
                altFormat: "j F Y", // Format d M YYYY yang dilihat user
                minDate: "today"    // Opsional
            });
        });
    </script>

</x-karyawan-layout>
