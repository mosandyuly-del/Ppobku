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
        $username = env('DIGIFLAZZ_USERNAME');
        $apiKey   = env('DIGIFLAZZ_KEY');

        if (!$username || !$apiKey) {
            $this->error('DIGIFLAZZ_USERNAME atau DIGIFLAZZ_KEY belum diisi di Environment Variables.');
            return 1;
        }

        $sign = md5($username . $apiKey . 'pricelist');

        $this->info('Mengambil data dari Digiflazz...');

        $response = Http::post('https://api.digiflazz.com/v1/price-list', [
            'cmd' => 'prepaid',
            'username' => $username,
            'sign' => $sign,
        ]);

        if ($response->failed()) {
            $this->error('Gagal terhubung ke API Digiflazz.');
            return 1;
        }

        $data = $response->json()['data'] ?? [];

        if (empty($data)) {
            $this->warn('Tidak ada data produk yang diterima dari Digiflazz.');
            return 0;
        }

        $count = 0;
        foreach ($data as $item) {
            $priceSell = ceil($item['price'] * 1.02); // Margin keuntungan 2%

            DB::table('products')->updateOrInsert(
                ['buyer_sku_code' => $item['buyer_sku_code']],
                [
                    'name'          => $item['product_name'],
                    'category'      => $item['category'],
                    'category_slug' => strtolower(str_replace(' ', '-', $item['category'])),
                    'brand'         => $item['brand'],
                    'type'          => $item['type'] ?? 'PPOB',
                    'price'         => $item['price'],
                    'price_sell'    => $priceSell,
                    'status'        => $item['buyer_product_status'] ? 'Active' : 'Inactive',
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
