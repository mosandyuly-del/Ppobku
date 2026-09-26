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

        // Ambil produk murni berdasarkan category_slug atau brand
        $products = DB::table('products')
            ->where('category_slug', 'LIKE', '%' . $slug . '%')
            ->orWhere('brand', 'LIKE', '%' . $slug . '%')
            ->get();

        // Pengaman jika data tidak berespons pada filter ketat
        if ($products->isEmpty()) {
            $products = DB::table('products')->get();
        }

        return view('category', [
            'slug' => $slug,
            'products' => $products,
            'phone' => $searchNumber
        ]);
    }
}
