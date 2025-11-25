<x-admin-layout>
    <div class="max-w-4xl text-black mx-auto p-8 bg-white rounded-2xl shadow-lg mt-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Edit Template Jadwal Kerja</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl shadow-md mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-md mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Nama Template --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700">Nama Template</label>
                <input type="text" name="name"
                       value="{{ old('name', $jadwal->name) }}"
                       class="w-full p-3 border border-gray-300 rounded-lg text-gray-800 focus:ring-2 focus:ring-indigo-500"
                       required>
            </div>

            {{-- Detail Hari --}}
            <div class="space-y-4">
                <label class="block font-semibold text-gray-700">Detail Jam Kerja</label>
@foreach ($jadwal->detailJadwals as $detail)
    @php
        $isWorking = $detail->hari_kerja == 1;
        $detailId = $detail->id;
    @endphp

    <div class="flex items-center gap-3 p-3 border rounded-xl bg-gray-50">
        <span class="w-24 font-medium text-gray-700">{{ $detail->hari }}</span>

        <label class="flex items-center gap-2">
            <input type="checkbox"
                   name="hari_kerja[{{ $detailId }}]"
                   onclick="toggleInputs({{ $detailId }})"
                   class="h-5 w-5 text-indigo-600 border-gray-300"
                   {{ $isWorking ? 'checked' : '' }}>
            <span>Hari Kerja</span>
        </label>

      <input type="time"
       name="jam_masuk[{{ $detailId }}]"
       id="jam_masuk_{{ $detailId }}"
       value="{{ $isWorking ? $detail->jam_masuk : '' }}"
       placeholder="{{ $isWorking ? '' : '--' }}"
       class="p-2 border border-gray-300 rounded-lg text-gray-800"
       {{ $isWorking ? '' : 'disabled' }}>

<input type="time"
       name="jam_pulang[{{ $detailId }}]"
       id="jam_pulang_{{ $detailId }}"
       value="{{ $isWorking ? $detail->jam_pulang : '' }}"
       placeholder="{{ $isWorking ? '' : '--' }}"
       class="p-2 border border-gray-300 rounded-lg text-gray-800"
       {{ $isWorking ? '' : 'disabled' }}>

    </div>
@endforeach


            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.jadwal.index') }}"
                   class="px-6 py-3 bg-gray-200 rounded-lg font-semibold text-gray-700 hover:bg-gray-300">
                    Batal
                </a>
                <button class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- Script toggle disable --}}
    <script>
        function toggleInputs(id) {
            const checkbox = document.querySelector(`input[name="hari_kerja[${id}]"]`);
            const masuk = document.getElementById(`jam_masuk_${id}`);
            const pulang = document.getElementById(`jam_pulang_${id}`);

            if (checkbox.checked) {
                masuk.disabled = false;
                pulang.disabled = false;
                masuk.placeholder = "";
                pulang.placeholder = "";
            } else {
                masuk.disabled = true;
                pulang.disabled = true;
                masuk.value = "";
                pulang.value = "";
                masuk.placeholder = "--";
                pulang.placeholder = "--";
            }
        }
    </script>
</x-admin-layout>
