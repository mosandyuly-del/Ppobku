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
        $cleanSlug = strtolower(trim(str_replace('-', ' ', $slug)));

        // Pemetaan kata kunci untuk kategori Digiflazz
        $keyword = $cleanSlug;
        if ($cleanSlug == 'paket data' || $cleanSlug == 'data') {
            $keyword = 'data';
        } elseif ($cleanSlug == 'pln' || $cleanSlug == 'token pln') {
            $keyword = 'pln';
        }

        // Ambil produk berdasarkan kecocokan nama, kategori, atau type
        $products = DB::table('products')
            ->where(function($q) use ($keyword, $slug) {
                $q->whereRaw('LOWER(category) LIKE ?', ["%{$keyword}%"])
                  ->orWhereRaw('LOWER(category_slug) LIKE ?', ["%{$slug}%"])
                  ->orWhereRaw('LOWER(type) LIKE ?', ["%{$keyword}%"])
                  ->orWhereRaw('LOWER(name) LIKE ?', ["%{$keyword}%"]);
            })
            ->get();

        // Jika tidak ada hasil spesifik, tampilkan produk aktif agar tidak kosong
        if ($products->isEmpty()) {
            $products = DB::table('products')->limit(100)->get();
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
