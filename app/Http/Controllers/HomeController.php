<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function category(Request $request, $slug = 'pulsa')
    {
        $products = DB::table('products')
            ->where('category_slug', $slug)
            ->get();

        if ($products->isEmpty()) {
            $products = DB::table('products')->get();
        }

        return view('category', [
            'slug' => $slug,
            'products' => $products,
            'phone' => $request->query('phone', '')
        ]);
    }

    public function checkIp(Request $request)
    {
        // Deteksi IP Publik Pengguna (Mendukung Proxy / Cloudflare / Railway)
        $ip = $request->header('X-Forwarded-For') 
            ?? $request->header('CF-Connecting-IP') 
            ?? $request->ip();

        // Jika dipanggil dari IP array (komma separated), ambil IP pertama
        if (str_contains($ip, ',')) {
            $ip = trim(explode(',', $ip)[0]);
        }

        // Ambil info geolokasi & ISP menggunakan API ip-api
        $ipDetails = [];
        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
            if ($response->successful()) {
                $ipDetails = $response->json();
            }
        } catch (\Exception $e) {
            $ipDetails = [];
        }

        return view('check_ip', [
            'ip' => $ip,
            'details' => $ipDetails,
            'userAgent' => $request->userAgent()
        ]);
    }
}
