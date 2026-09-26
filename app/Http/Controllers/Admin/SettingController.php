<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function updateQris(Request $request)
    {
        $request->validate([
            'qris_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('qris_image')) {
            // Cari data setting berdasarkan key 'qris_image'
            $setting = Setting::where('key', 'qris_image')->first();

            if (!$setting) {
                $setting = new Setting();
                $setting->key = 'qris_image';
            } else {
                // Hapus gambar lama dari storage jika ada
                if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                }
            }

            // Simpan gambar baru ke storage/app/public/qris
            $path = $request->file('qris_image')->store('qris', 'public');
            $setting->value = $path;
            $setting->save();
        }

        return redirect()->back()->with('success', 'Gambar QRIS berhasil diperbarui!');
    }
}
