<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_no' => 'required|numeric|digits_between:10,14',
            'sku_code' => 'required|exists:products,sku_code',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $product = Product::where('sku_code', $request->sku_code)->firstOrFail();
        $invoiceNumber = 'INV' . date('YmdHis') . rand(100, 999);

        $order = Order::create([
            'invoice_number' => $invoiceNumber,
            'customer_no' => $request->customer_no,
            'sku_code' => $product->sku_code,
            'price' => $product->price_sell,
            'payment_method_id' => $request->payment_method_id,
            'payment_status' => 'pending',
            'trx_status' => 'pending',
        ]);

        return redirect()->route('order.show', $order->invoice_number);
    }

    public function show($invoice_number)
    {
        $order = Order::with(['paymentMethod', 'product'])
            ->where('invoice_number', $invoice_number)
            ->firstOrFail();

        return view('order-detail', compact('order'));
    }

    public function uploadProof(Request $request, $invoice_number)
    {
        $request->validate([
            'proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::where('invoice_number', $invoice_number)->firstOrFail();

        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('proofs', 'public');
            $order->update([
                'proof_of_payment' => $path,
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah!');
    }

    public function checkStatusForm()
    {
        return view('check-status');
    }

    public function checkStatusSearch(Request $request)
    {
        $request->validate([
            'query' => 'required',
        ]);

        $search = $request->input('query');

        $orders = Order::with(['paymentMethod', 'product'])
            ->where('invoice_number', $search)
            ->orWhere('customer_no', $search)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('check-status', compact('orders', 'search'));
    }
}
