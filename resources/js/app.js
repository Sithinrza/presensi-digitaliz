import './bootstrap';
import 'flowbite';
document.addEventListener('DOMContentLoaded', () => {
    // Panggil initFlowbite() agar komponen JS Flowbite (datepicker, dll) terinisialisasi
    // Pastikan Anda sudah menginstal flowbite via NPM
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }
});

import flatpickr from 'flatpickr';

document.addEventListener('DOMContentLoaded', function() {

    // Targetkan elemen di halaman jadwal
    const calendarInput = document.getElementById('karyawan-calendar');
    const agendaListContainer = document.getElementById('agenda-list');

    // Hanya jalankan kode ini JIKA kita berada di halaman jadwal
    // (yaitu, jika elemen kalender dan agenda ditemukan)
    if (calendarInput && agendaListContainer) {

        console.log("Halaman Jadwal: Menginisialisasi Flatpickr...");

        // Inisialisasi Flatpickr
        flatpickr("#karyawan-calendar", {
            inline: true, // Membuat kalender selalu terlihat
            dateFormat: "Y-m-d",
            defaultDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                // Panggil fungsi untuk memuat agenda saat tanggal berubah
                console.log('Tanggal dipilih:', dateStr);
                fetchAndDisplayAgenda(dateStr);
            }
        });

        // Fungsi untuk mengambil data agenda via AJAX (menggunakan Axios)
        function fetchAndDisplayAgenda(dateISO) {
             agendaListContainer.innerHTML = `<div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 text-center text-gray-500 italic">Memuat agenda...</div>`;

             // Ganti URL ini dengan endpoint API Anda yang sebenarnya
             const apiUrl = `/api/agenda-karyawan/${dateISO}`;

             // Menggunakan Axios (yang otomatis di-setup oleh bootstrap.js)
             // Ini lebih baik daripada fetch() karena otomatis mengirim header CSRF
             axios.get(apiUrl, {
                headers: {
                    'Accept': 'application/json'
                    // Tidak perlu 'X-CSRF-TOKEN' di sini, Axios sudah menanganinya
                }
             })
             .then(response => {
                const agendas = response.data; // Data ada di response.data
                agendaListContainer.innerHTML = '';
                if (agendas && agendas.length > 0) {
                    agendas.forEach(agenda => {
                        // Render HTML agenda
                        const agendaElement = `
                            <div class="agenda-item bg-white p-4 rounded-xl shadow-md border border-gray-200">
                                <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                                     <span class="p-2 bg-gray-100 rounded-lg mr-2">
                                        <svg class="w-5 h-5 text-indigo-950" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                     </span>
                                    ${agenda.judul || 'Tanpa Judul'}
                                </h3>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-gray-500 pl-12">
                                    <div class="flex items-center space-x-2"> <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg> <span>${new Date(agenda.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</span> </div>
                                    <div class="flex items-center space-x-2"> <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <span>${agenda.waktu_mulai ? agenda.waktu_mulai.substring(0,5) : 'Sepanjang hari'}</span> </div>
                                    <div class="flex items-center space-x-2"> <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg> <span>${agenda.lokasi || 'Lokasi tidak ditentukan'}</span> </div>
                                    <div class="flex items-center space-x-2"> <svg class="h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">${agenda.venueIcon || '<path d="M2.992 4.018A2 2 0 014.99 2h10.02a2 2 0 011.998 2.018L17 14a3 3 0 11-6 0L11 8l-3 6a3 3 0 11-6 0L2 4.018A.001.001 0 012.992 4zm1.01 4h12V4H4v4z" />'}</svg> <span>${agenda.venue || 'Online'}</span> </div>
                                </div>
                            </div>`;
                        agendaListContainer.innerHTML += agendaElement;
                    });
                } else {
                    agendaListContainer.innerHTML = `<div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 text-center text-gray-500 italic"> Tidak ada agenda untuk tanggal ini. </div>`;
                }
             })
             .catch(error => {
                console.error('Error fetching agenda:', error);
                agendaListContainer.innerHTML = `<div class="bg-red-100 text-red-700 p-4 rounded-xl shadow-md border border-red-200 text-center"> Gagal memuat agenda. </div>`;
             });
        }

        // Muat agenda untuk tanggal hari ini saat halaman pertama kali dibuka
        fetchAndDisplayAgenda(new Date().toISOString().split('T')[0]);
    }
});
