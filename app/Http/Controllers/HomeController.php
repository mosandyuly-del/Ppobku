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
        $searchNumber = $request->query('phone');
        $cleanSlug = strtolower(trim(str_replace('-', ' ', $slug)));

        // Pencarian aman produk berdasarkan kategori/brand/tipe
        $products = DB::table('products')
            ->where('category_slug', 'LIKE', '%' . $slug . '%')
            ->orWhere('category_slug', 'LIKE', '%' . $cleanSlug . '%')
            ->orWhere('brand', 'LIKE', '%' . $cleanSlug . '%')
            ->orWhere('type', 'LIKE', '%' . $cleanSlug . '%')
            ->get();

        // Jika tidak ditemukan filter spesifik, tampilkan seluruh produk agar tidak kosong
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
