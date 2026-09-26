<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function category(Request $request, $slug = 'pulsa')
    {
        // Normalisasi kata kunci slug
        $cleanSlug = strtolower(trim(str_replace('-', ' ', $slug)));

        // Cari produk berdasarkan category_slug, category, atau brand
        $products = DB::table('products')
            ->where(function($query) use ($slug, $cleanSlug) {
                $query->where('category_slug', 'like', "%{$slug}%")
                      ->orWhere('category', 'like', "%{$cleanSlug}%")
                      ->orWhere('type', 'like', "%{$cleanSlug}%");
            })
            ->where('status', 'Active')
            ->orderBy('price_sell', 'asc')
            ->get();

        // Jika tidak ditemukan hasil spesifik, ambil semua produk aktif agar halaman tidak kosong
        if ($products->isEmpty()) {
            $products = DB::table('products')
                ->where('status', 'Active')
                ->orderBy('price_sell', 'asc')
                ->limit(50)
                ->get();
        }

        return view('category', [
            'slug' => $slug,
            'products' => $products,
            'phone' => $request->query('phone', '')
        ]);
    }

    public function checkIp(Request $request)
    {
        $ip = $request->header('X-Forwarded-For') 
            ?? $request->header('CF-Connecting-IP') 
            ?? $request->ip();

        if (str_contains($ip, ',')) {
            $ip = trim(explode(',', $ip)[0]);
        }

        $ipDetails = [];
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
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
