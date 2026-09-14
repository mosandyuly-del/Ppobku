<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncDigiflazz extends Command
{
    protected $signature = 'digiflazz:sync';
    protected $description = 'Sync product prices from Digiflazz with +1500 markup';

    public function handle()
    {
        if (!Schema::hasTable('products')) {
            $this->error('Tabel products tidak ditemukan!');
            return 1;
        }

        $username = config('services.digiflazz.username', env('DIGIFLAZZ_USERNAME'));
        $apiKey = config('services.digiflazz.key', env('DIGIFLAZZ_KEY'));

        $dataFound = false;

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
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                $dataFound = true;
                foreach ($result['data'] as $item) {
                    $hargaJual = ($item['price'] ?? 0) + 1500;
                    $skuCode = $item['buyer_sku_code'] ?? ('SKU-' . rand(1000, 9999));

                    DB::table('products')->updateOrInsert(
                        ['sku' => $skuCode],
                        [
                            'name' => $item['product_name'] ?? 'Produk PPOB',
                            'category' => $item['category'] ?? 'Umum',
                            'brand' => $item['brand'] ?? 'Digiflazz',
                            'price_original' => $item['price'] ?? 0,
                            'price' => $hargaJual,
                            'status' => 'active',
                            'updated_at' => now(),
                        ]
                    );
                }
                $this->info('Sukses mengunduh ' . count($result['data']) . ' produk dari Digiflazz!');
            }
        }

        // Dummy catalog fallback jika API kosong/gagal
        if (!$dataFound) {
            $sampleProducts = [
                ['sku' => 'PULSA5K', 'name' => 'Pulsa Reguler 5.000', 'category' => 'Pulsa', 'brand' => 'Telkomsel', 'price' => 6700],
                ['sku' => 'PULSA10K', 'name' => 'Pulsa Reguler 10.000', 'category' => 'Pulsa', 'brand' => 'Telkomsel', 'price' => 11700],
                ['sku' => 'ML86', 'name' => 'Mobile Legends 86 Diamonds', 'category' => 'Games', 'brand' => 'Mobile Legends', 'price' => 21500],
                ['sku' => 'FF140', 'name' => 'Free Fire 140 Diamonds', 'category' => 'Games', 'brand' => 'Free Fire', 'price' => 20500],
                ['sku' => 'DATA1GB', 'name' => 'Paket Data 1GB / 30 Hari', 'category' => 'Data', 'brand' => 'Indosat', 'price' => 14500],
                ['sku' => 'PLN20K', 'name' => 'Token PLN 20.000', 'category' => 'PLN', 'brand' => 'PLN', 'price' => 21500],
                ['sku' => 'PDAM1', 'name' => 'Pembayaran Tagihan PDAM', 'category' => 'PDAM', 'brand' => 'PDAM', 'price' => 2500],
            ];

            foreach ($sampleProducts as $sp) {
                DB::table('products')->updateOrInsert(
                    ['sku' => $sp['sku']],
                    [
                        'name' => $sp['name'],
                        'category' => $sp['category'],
                        'brand' => $sp['brand'],
                        'price_original' => $sp['price'] - 1500,
                        'price' => $sp['price'],
                        'status' => 'active',
                        'updated_at' => now(),
                    ]
                );
            }
        }

        return 0;
    }
}
