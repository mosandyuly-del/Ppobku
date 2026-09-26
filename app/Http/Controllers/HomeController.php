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
        // Ambil slug dari URL atau query string (misal: /layanan?type=pulsa)
        if ($request->has('type')) {
            $slug = $request->query('type');
        } elseif ($request->has('category')) {
            $slug = $request->query('category');
        }

        $searchNumber = $request->query('phone');
        $cleanSlug = strtolower(trim(str_replace('-', ' ', $slug)));

        // Ambil produk dari database
        $products = DB::table('products')
            ->where(function($q) use ($slug, $cleanSlug) {
                $q->where('category_slug', 'LIKE', '%' . $slug . '%')
                  ->orWhere('category_slug', 'LIKE', '%' . $cleanSlug . '%')
                  ->orWhere('brand', 'LIKE', '%' . $cleanSlug . '%')
                  ->orWhere('type', 'LIKE', '%' . $cleanSlug . '%');
            })
            ->get();

        // Pengaman: Jika tidak ada produk yang cocok dengan slug, tampilkan semua produk agar tidak kosong
        if ($products->isEmpty()) {
            $products = DB::table('products')->get();
        }

        return view('category', [
            'slug' => $slug,
            'products' => $products,
            'phone' => $searchNumber,
            'showExpiryFilter' => in_array(strtolower($slug), ['paket-data', 'data']),
        ]);
    }
}
