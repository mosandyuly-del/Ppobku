<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncDigiflazz extends Command
{
    protected $signature = 'digiflazz:sync';
    protected $description = 'Sync product prices from Digiflazz Production API';

    public function handle()
    {
        if (!Schema::hasTable('products')) {
            $this->error('Tabel products tidak ditemukan!');
            return 1;
        }

        // Ambil konfigurasi dari DB Settings jika ada, fallback ke env
        $getSetting = function ($key, $default = null) {
            if (Schema::hasTable('settings')) {
                $item = DB::table('settings')->where('key', $key)->first();
                if ($item && !empty($item->value)) {
                    return $item->value;
                }
            }
            return env($key, $default);
        };

        $username = $getSetting('DIGIFLAZZ_USERNAME');
        $apiKey = $getSetting('DIGIFLAZZ_KEY');
        $markupFlat = (int) $getSetting('MARKUP_FLAT', 1500);

        if ($username && $apiKey) {
            $sign = md5($username . $apiKey . 'pricelist');
            
            $payload = [
                'cmd' => 'prepaid',
                'username' => $username,
                'sign' => $sign
            ];

            $ch = curl_init('https://api.digiflazz.com/v1/price-list');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            
            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                foreach ($result['data'] as $item) {
                    $modal = $item['price'] ?? 0;
                    $hargaJual = $modal + $markupFlat;
                    $skuCode = $item['buyer_sku_code'] ?? '';

                    if (empty($skuCode)) continue;

                    $statusBuyer = $item['buyer_product_status'] ?? true;
                    $statusSeller = $item['seller_product_status'] ?? true;
                    $status = ($statusBuyer && $statusSeller) ? 'active' : 'inactive';

                    DB::table('products')->updateOrInsert(
                        ['code' => $skuCode],
                        [
                            'sku' => $skuCode,
                            'name' => $item['product_name'] ?? 'Produk PPOB',
                            'category' => $item['category'] ?? 'Umum',
                            'brand' => $item['brand'] ?? 'Digiflazz',
                            'price_original' => $modal,
                            'price' => $hargaJual,
                            'status' => $status,
                            'updated_at' => now(),
                        ]
                    );
                }
                $this->info('Berhasil menyinkronkan data Digiflazz Production!');
            } else {
                $this->error('Gagal mengambil data dari Digiflazz. Periksa Username & Production Key.');
            }
        } else {
            $this->error('Username atau API Key Digiflazz belum dikonfigurasi.');
        }

        return 0;
    }
}
