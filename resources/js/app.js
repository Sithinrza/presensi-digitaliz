import './bootstrap';
import 'flowbite';
document.addEventListener('DOMContentLoaded', () => {
    // Panggil initFlowbite() agar komponen JS Flowbite (datepicker, dll) terinisialisasi
    // Pastikan Anda sudah menginstal flowbite via NPM
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }
});

import flatpickr from "flatpickr";
import { Indonesian } from "flatpickr/dist/l10n/id.js";
import "flatpickr/dist/flatpickr.min.css";

document.addEventListener("DOMContentLoaded", function () {

    // === TANGGAL LAHIR ===
    const tanggalLahirInput = document.getElementById("tanggal_lahir");
    if (tanggalLahirInput) {
        flatpickr(tanggalLahirInput, {
            locale: Indonesian,
            dateFormat: "d/m/Y",  // format input yang dikirim ke backend
            altInput: true,
            altFormat: "d F Y",   // tampilan lebih ramah untuk user
            allowInput: true,
            maxDate: "today"      // tidak boleh melebihi hari ini
        });
        console.log("✅ Flatpickr aktif untuk Tanggal Lahir");
    }

    // === TANGGAL BERGABUNG ===
    const tanggalBergabungInput = document.getElementById("tanggal_bergabung");
    if (tanggalBergabungInput) {
        flatpickr(tanggalBergabungInput, {
            locale: Indonesian,
            dateFormat: "d/m/Y",
            altInput: true,
            altFormat: "d F Y",
            allowInput: true,
            maxDate: new Date().fp_incr(30), // opsional: hanya bisa pilih sampai 30 hari ke depan
            defaultDate: "today"
        });
        console.log("✅ Flatpickr aktif untuk Tanggal Bergabung");
    }
});

//kalender karyawan
document.addEventListener("DOMContentLoaded", function () {
    flatpickr("#kalender-karyawan", {
        inline: true, // tampil langsung di halaman
        locale: "id",
        dateFormat: "d/m/Y",
        defaultDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
            console.log("Tanggal dipilih:", dateStr);
                    // di sini kamu bisa load agenda sesuai tanggal
        }
    });
});

import Swal from 'sweetalert2'; 

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const message = form.dataset.confirm; // ambil teks dari atribut data
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});

window.confirmDelete = function (formId, nama) {
    Swal.fire({
        title: `Hapus ${nama}?`,
        text: "Data ini akan dihapus secara permanen dan tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
};

// Form tambah agenda
$('#formTambahAgenda').on('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menambahkan agenda ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'swal2-confirm btn-primary',
            cancelButton: 'swal2-cancel btn-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit(); // lanjut submit form
        }
    });
});

// Tombol edit agenda
$('.btnEditAgenda').on('click', function(e) {
    e.preventDefault();

    const form = $(this).closest('form');

    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin mengubah agenda ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Lanjutkan!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit(); // kirim form edit
        }
    });
});

// Tombol hapus agenda
document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll('.formHapusAgenda');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // cegah submit langsung

            Swal.fire({
                title: 'Yakin Hapus?',
                text: "Data agenda ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit jika setuju
                }
            });
        });
    });
});