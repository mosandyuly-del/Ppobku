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
        $phone = $request->query('phone', '');
        $cleanSlug = strtolower(trim($slug));

        // Deteksi Provider berdasarkan Awalan Nomor HP (Prefix)
        $detectedBrand = $this->detectProvider($phone);

        // Query Dasar Produk
        $query = DB::table('products')->where('status', 'Active');

        // 1. FILTER BERDASARKAN JENIS LAYANAN (Ketat)
        if (in_array($cleanSlug, ['pulsa', 'pulsa-reguler'])) {
            $query->where(function($q) {
                $q->whereRaw('LOWER(category) LIKE ?', ['%pulsa%'])
                  ->orWhereRaw('LOWER(category_slug) LIKE ?', ['%pulsa%'])
                  ->orWhereRaw('LOWER(type) LIKE ?', ['%pulsa%']);
            })->whereRaw('LOWER(name) NOT LIKE ?', ['%data%'])
              ->whereRaw('LOWER(name) NOT LIKE ?', ['%kuota%']);

        } elseif (in_array($cleanSlug, ['paket-data', 'data', 'kuota'])) {
            $query->where(function($q) {
                $q->whereRaw('LOWER(category) LIKE ?', ['%data%'])
                  ->orWhereRaw('LOWER(category_slug) LIKE ?', ['%data%'])
                  ->orWhereRaw('LOWER(type) LIKE ?', ['%data%'])
                  ->orWhereRaw('LOWER(name) LIKE ?', ['%data%'])
                  ->orWhereRaw('LOWER(name) LIKE ?', ['%kuota%']);
            });

        } elseif (in_array($cleanSlug, ['pln', 'token-pln', 'listrik'])) {
            $query->where(function($q) {
                $q->whereRaw('LOWER(category) LIKE ?', ['%pln%'])
                  ->orWhereRaw('LOWER(category) LIKE ?', ['%listrik%'])
                  ->orWhereRaw('LOWER(brand) LIKE ?', ['%pln%']);
            });
        }

        // 2. FILTER BERDASARKAN PROVIDER / BRAND (Jika Nomor HP Diisi & Terdeteksi)
        if (!empty($detectedBrand)) {
            $query->whereRaw('LOWER(brand) LIKE ?', ['%' . strtolower($detectedBrand) . '%']);
        }

        $products = $query->orderBy('price_sell', 'asc')->get();

        // Fallback jika tidak ditemukan spesifik brand
        if ($products->isEmpty()) {
            $products = DB::table('products')->where('status', 'Active')->limit(50)->get();
        }

        return view('category', [
            'slug' => $slug,
            'products' => $products,
            'phone' => $phone,
            'detectedBrand' => $detectedBrand
        ]);
    }

    private function detectProvider($phone)
    {
        if (empty($phone)) return null;

        // Normalisasi nomor
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        $prefix = substr($phone, 0, 4);

        $prefixes = [
            'TELKOMSEL' => ['0811','0812','0813','0821','0822','0823','0851','0852','0853'],
            'INDOSAT'   => ['0814','0815','0816','0855','0856','0857','0858'],
            'XL'        => ['0817','0818','0819','0859','0877','0878'],
            'AXIS'      => ['0831','0832','0833','0838'],
            'TRI'       => ['0895','0896','0897','0898','0899'],
            'SMARTFREN' => ['0881','0882','0883','0884','0885','0886','0887','0888','0889'],
        ];

        foreach ($prefixes as $brand => $list) {
            if (in_array($prefix, $list)) {
                return $brand;
            }
        }

        return null;
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
