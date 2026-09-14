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
        $username = config('services.digiflazz.username');
        $apiKey = config('services.digiflazz.key');

        if (!$username || !$apiKey) {
            $this->error('Username atau API Key Digiflazz belum diatur di Variable Railway!');
            return 1;
        }

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
        
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['data']) && is_array($result['data'])) {
            foreach ($result['data'] as $item) {
                $hargaJual = $item['price'] + 1500;

                if (Schema::hasTable('products')) {
                    DB::table('products')->updateOrInsert(
                        ['code' => $item['buyer_sku_code']],
                        [
                            'name' => $item['product_name'],
                            'category' => $item['category'],
                            'brand' => $item['brand'],
                            'price_original' => $item['price'],
                            'price' => $hargaJual,
                            'status' => $item['buyer_product_status'] && $item['seller_product_status'] ? 'active' : 'inactive',
                            'updated_at' => now(),
                        ]
                    );
                }
            }
            $this->info('Berhasil menyinkronkan harga produk Digiflazz (+Rp 1.500)!');
        } else {
            $this->error('Gagal mengambil data dari Digiflazz.');
        }

        return 0;
    }
}
