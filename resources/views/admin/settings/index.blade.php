<form action="{{ route('admin.settings.update-qris') }}" method="POST" enctype="multipart/form-data" class="p-4 bg-white rounded shadow">
    @csrf
    @method('PUT')

    <h3 class="text-lg font-bold mb-4">Pengaturan QRIS Pembayaran</h3>

    <div class="mb-4">
        <label class="block font-medium text-sm text-gray-700 mb-2">QRIS Saat Ini:</label>
        <div>
            @php
                $setting = \App\Models\Setting::first();
            @endphp
            @if(isset($setting->qris_image) && $setting->qris_image)
                <img src="{{ asset('storage/' . $setting->qris_image) }}" alt="QRIS Mosandy Cell" class="w-48 h-auto border rounded p-2">
            @else
                <p class="text-gray-500 text-sm">Belum ada gambar QRIS yang diunggah.</p>
            @endif
        </div>
    </div>

    <div class="mb-4">
        <label for="qris_image" class="block font-medium text-sm text-gray-700 mb-1">Pilih Gambar QRIS Baru</label>
        <input type="file" name="qris_image" id="qris_image" accept="image/*" class="w-full border-gray-300 rounded-md shadow-sm">
        @error('qris_image')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Simpan QRIS Baru
    </button>
</form>
