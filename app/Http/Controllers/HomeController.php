<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        // Cek apakah tabel products ada
        if (!Schema::hasTable('products')) {
            $products = collect();
        } else {
            // Ambil produk berdasarkan pencarian kategori/brand/type
            $products = DB::table('products')
                ->where('category_slug', 'LIKE', '%' . $slug . '%')
                ->orWhere('category_slug', 'LIKE', '%' . $cleanSlug . '%')
                ->orWhere('brand', 'LIKE', '%' . $cleanSlug . '%')
                ->orWhere('type', 'LIKE', '%' . $cleanSlug . '%')
                ->get();

            // Jika query spesifik kosong, ambil seluruh isi produk di DB agar halaman tidak 404/kosong
            if ($products->isEmpty()) {
                $products = DB::table('products')->get();
            }
        }

        return view('category', [
            'slug' => $slug,
            'products' => $products,
            'phone' => $searchNumber,
            'showExpiryFilter' => in_array(strtolower($slug), ['paket-data', 'data']),
        ]);
    }
}
