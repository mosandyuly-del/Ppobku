<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SyncDigiflazz extends Command
{
    protected $signature = 'digiflazz:sync';
    protected $description = 'Sync price list from Digiflazz API';

    public function handle()
    {
        $username = env('DIGIFLAZZ_USERNAME', 'digadoDlVxag');
        $apiKey   = env('DIGIFLAZZ_KEY', '4568ac7b-881d-53e4-8a5f-40589be68ac4');

        $sign = md5($username . $apiKey . 'pricelist');

        $this->info('Mengambil data dari Digiflazz...');

        $response = Http::post('https://api.digiflazz.com/v1/price-list', [
            'cmd' => 'prepaid',
            'username' => $username,
            'sign' => $sign,
        ]);

        $resData = $response->json();

        // Tampilkan respon jika Digiflazz mengirimkan pesan error/notifikasi
        if (isset($resData['data']['rc']) || isset($resData['data']['message'])) {
            $this->error('Respon Digiflazz: ' . json_encode($resData));
            return 0;
        }

        $data = $resData['data'] ?? [];

        if (!is_array($data) || empty($data)) {
            $this->warn('Respon API: ' . json_encode($resData));
            return 0;
        }

        $count = 0;
        foreach ($data as $item) {
            if (!is_array($item) || !isset($item['price']) || !isset($item['buyer_sku_code'])) {
                continue;
            }

            $price = (float) $item['price'];
            $priceSell = ceil($price * 1.02);

            $category = $item['category'] ?? 'Umum';
            $categorySlug = strtolower(trim(str_replace(' ', '-', $category)));

            DB::table('products')->updateOrInsert(
                ['buyer_sku_code' => $item['buyer_sku_code']],
                [
                    'name'          => $item['product_name'] ?? 'Produk',
                    'category'      => $category,
                    'category_slug' => $categorySlug,
                    'brand'         => $item['brand'] ?? 'PPOB',
                    'type'          => $item['type'] ?? 'PPOB',
                    'price'         => $price,
                    'price_sell'    => $priceSell,
                    'status'        => (!empty($item['buyer_product_status'])) ? 'Active' : 'Inactive',
                    'description'   => $item['desc'] ?? '',
                    'updated_at'    => now(),
                ]
            );
            $count++;
        }

        $this->info("Berhasil menyinkronkan {$count} produk dari Digiflazz!");
        return 0;
    }
}
