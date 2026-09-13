<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('brand', 'asc')->get();
        return view('admin.products', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku_code' => 'required|unique:products,sku_code',
            'name' => 'required|string',
            'brand' => 'required|string',
            'price_original' => 'required|numeric',
            'price_sell' => 'required|numeric',
        ]);

        Product::create([
            'sku_code' => strtoupper($request->sku_code),
            'name' => $request->name,
            'brand' => ucfirst($request->brand),
            'price_original' => $request->price_original,
            'price_sell' => $request->price_sell,
            'is_active' => true,
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'price_original' => 'required|numeric',
            'price_sell' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        $product->update([
            'name' => $request->name,
            'brand' => ucfirst($request->brand),
            'price_original' => $request->price_original,
            'price_sell' => $request->price_sell,
            'is_active' => $request->is_active,
        ]);

        return back()->with('success', 'Produk ' . $product->sku_code . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus!');
    }
}
