<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PaymentMethod;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('home', compact('products', 'paymentMethods'));
    }
}
