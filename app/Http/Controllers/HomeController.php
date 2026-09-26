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
}
