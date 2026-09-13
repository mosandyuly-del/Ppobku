<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::with(['paymentMethod', 'product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders', compact('orders'));
    }

    public function updateStatus(Request $request, $invoice_number)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,expired',
            'trx_status' => 'required|in:pending,process,success,failed',
            'sn' => 'nullable|string',
        ]);

        $order = Order::where('invoice_number', $invoice_number)->firstOrFail();
        $order->update([
            'payment_status' => $request->payment_status,
            'trx_status' => $request->trx_status,
            'sn' => $request->sn,
        ]);

        return back()->with('success', 'Status transaksi ' . $invoice_number . ' berhasil diperbarui!');
    }
}
