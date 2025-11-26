<x-admin-layout>
    <div class="max-w-4xl mx-auto p-8 bg-white rounded-2xl text-black shadow-lg mt-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Tambah Template Jadwal Kerja</h1>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-md mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-6">

              @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Gagal Menyimpan!</strong>
                <span class="block sm:inline">Periksa kembali kesalahan input Anda.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            @csrf

            <div>
                <label class="block mb-2 font-semibold text-gray-700">Nama Template</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 focus:ring-2 focus:ring-indigo-500"
                       required>
            </div>

            <div class="space-y-4">
                <label class="block font-semibold text-gray-700">Detail Jam Kerja</label>

                @php
                    $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                @endphp

                @foreach ($days as $i => $day)
                    <div class="flex items-center gap-3 p-3 border rounded-xl bg-gray-50">
                        <span class="w-24 font-medium">{{ $day }}</span>

                        <input type="hidden" name="details[{{ $i }}][hari]" value="{{ $day }}">

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="details[{{ $i }}][hari_kerja]" onclick="toggleInputs({{ $i }})"
                                class="h-5 w-5 text-indigo-600 border-gray-300">
                            <span>Hari Kerja</span>
                        </label>

                        <input type="time" name="details[{{ $i }}][jam_masuk]" id="jam_masuk_{{ $i }}"
                               class="p-2 border border-gray-300 rounded-lg text-gray-700" disabled>

                        <span>s/d</span>

                        <input type="time" name="details[{{ $i }}][jam_pulang]" id="jam_pulang_{{ $i }}"
                               class="p-2 border border-gray-300 rounded-lg text-gray-700" disabled>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.jadwal.index') }}" class="px-6 py-3 bg-gray-200 rounded-lg font-semibold text-gray-700 hover:bg-gray-300">Batal</a>
                <button class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        function toggleInputs(i) {
            const checked = document.querySelector(`input[name="details[${i}][hari_kerja]"]`).checked;
            document.getElementById(`jam_masuk_${i}`).disabled = !checked;
            document.getElementById(`jam_pulang_${i}`).disabled = !checked;
        }
    </script>
</x-admin-layout>
