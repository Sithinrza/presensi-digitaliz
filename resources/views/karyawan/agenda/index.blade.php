<x-karyawan-layout>
    <x-slot:title>
        Jadwal
    </x-slot:title>

    {{-- Latar belakang abu-abu sekarang ada di body layout atau di sini --}}
    <div class="relative min-h-screen pb-24 bg-gray-100"> 
        <div class="bg-indigo-950 p-4 pt-8 pb-24 -mt-1 rounded-t-[3rem] relative z-10">
        </div>

        <main class="p-4 -mt-20 relative z-10 space-y-6">
            <section class="bg-white p-5 rounded-2xl shadow-lg flex justify-center">
                <div id="kalender-karyawan"></div>
            </section>


            <!-- Agenda Hari Ini -->
            <section id="agenda-section" class="space-y-3">
                 <h2 class="text-lg font-bold text-gray-800 px-1">Agenda Hari Ini</h2>
                 
                 {{-- Container ini akan diisi oleh JavaScript dari app.js --}}
                 <div id="agenda-list" class="space-y-3">
                    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 text-center text-gray-500 italic">
                        Memuat agenda...
                    </div>
                 </div>
            </section>

        </main>
    </div>

</x-karyawan-layout>

