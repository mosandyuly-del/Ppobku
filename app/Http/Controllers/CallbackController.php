<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    public function handleDigiflazz(Request $request)
    {
        $secret = config('services.digiflazz.secret', env('DIGIFLAZZ_KEY'));
        $postData = $request->getContent();
        $signature = 'sha1=' . hash_hmac('sha1', $postData, $secret);

        Log::info('Digiflazz Callback Received:', $request->all());

        $data = $request->input('data');

        if (!$data) {
            return response()->json(['message' => 'No data received'], 400);
        }

        $refId = $data['ref_id'] ?? null;
        $status = $data['status'] ?? null;
        $sn = $data['sn'] ?? null;

        if ($refId) {
            $order = Order::where('invoice_number', $refId)->first();

            if ($order) {
                $order->update([
                    'trx_status' => strtolower($status),
                    'sn' => $sn,
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
