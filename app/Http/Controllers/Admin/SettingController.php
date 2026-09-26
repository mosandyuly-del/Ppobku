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

        $setting = Setting::first() ?? new Setting();

        if ($request->hasFile('qris_image')) {
            if ($setting->qris_image && Storage::disk('public')->exists($setting->qris_image)) {
                Storage::disk('public')->delete($setting->qris_image);
            }

            $path = $request->file('qris_image')->store('qris', 'public');
            $setting->qris_image = $path;
            $setting->save();
        }

        return redirect()->back()->with('success', 'Gambar QRIS berhasil diperbarui!');
    }
}
