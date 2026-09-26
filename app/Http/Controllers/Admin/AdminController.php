<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/admin/dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function dashboard()
    {
        $totalProducts = DB::table('products')->count();
        $totalOrders   = DB::table('orders')->count();
        $recentOrders  = DB::table('orders')->orderBy('id', 'desc')->limit(15)->get();

        // Ambil IP Outbound Server Railway
        $serverIp = 'Tidak Terdeteksi';
        try {
            $response = Http::timeout(3)->get('https://api.ipify.org?format=json');
            if ($response->successful()) {
                $serverIp = $response->json('ip');
            }
        } catch (\Exception $e) {
            $serverIp = gethostbyname(gethostname());
        }

        return view('admin.dashboard', compact('totalProducts', 'totalOrders', 'recentOrders', 'serverIp'));
    }

    public function processOrder(Request $request, $id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $username = env('DIGIFLAZZ_USERNAME', 'digadoDlVxag');
        $apiKey   = env('DIGIFLAZZ_KEY', '4568ac7b-881d-53e4-8a5f-40589be68ac4');
        $refId    = $order->trx_id ?? ('TRX-' . time());
        $sign     = md5($username . $apiKey . $refId);

        // Cari buyer_sku_code produk
        $product = DB::table('products')->where('id', $order->product_id)->first();
        $skuCode = $product->buyer_sku_code ?? ($order->product_code ?? 'P5');

        // Request Transaksi Top Up ke Digiflazz
        try {
            $response = Http::post('https://api.digiflazz.com/v1/transaction', [
                'username'       => $username,
                'buyer_sku_code' => $skuCode,
                'customer_no'    => $order->phone,
                'ref_id'         => $refId,
                'sign'           => $sign,
            ]);

            $resData = $response->json();
            $data = $resData['data'] ?? [];

            $statusDigi = $data['status'] ?? 'Pending';
            $sn         = $data['sn'] ?? '';
            $message    = $data['message'] ?? 'Diproses ke Digiflazz';

            // Update status pesanan di database
            $finalStatus = ($statusDigi == 'Sukses') ? 'Sukses' : (($statusDigi == 'Gagal') ? 'Gagal' : 'Diproses');

            DB::table('orders')->where('id', $id)->update([
                'status'     => $finalStatus,
                'sn'         => $sn,
                'note'       => $message,
                'updated_at' => now(),
            ]);

            return back()->with('success', "Orderan #{$refId} Berhasil Ditembak! Status Digiflazz: {$statusDigi} ({$message})");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke API Digiflazz: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
