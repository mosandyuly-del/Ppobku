<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DigiflazzSync extends Command
{
    protected $signature = 'digiflazz:sync';
    protected $description = 'Sync product list from Digiflazz API';

    public function handle()
    {
        function get_config($key) {
            if (Schema::hasTable('settings')) {
                $item = DB::table('settings')->where('key', $key)->first();
                if ($item && !empty($item->value)) return $item->value;
            }
            return env($key, '');
        }

        $username = get_config('DIGIFLAZZ_USERNAME');
        $apiKey = get_config('DIGIFLAZZ_KEY');
        $markupFlat = (int) get_config('MARKUP_FLAT');
        if ($markupFlat <= 0) $markupFlat = 1500;

        if (empty($username) || empty($apiKey)) {
            $this->error('Digiflazz Username atau API Key belum diisi di Admin Panel.');
            return;
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

        $resData = json_decode($response, true);

        // Jika API Digiflazz Mengembalikan Error / Unauthorized
        if (isset($resData['data']['message'])) {
            $this->error('Digiflazz API Response: ' . $resData['data']['message']);
            return;
        }

        if (!isset($resData['data']) || !is_array($resData['data'])) {
            $this->error('Gagal terhubung ke API Digiflazz.');
            return;
        }

        if (!Schema::hasTable('products')) {
            Schema::create('products', function ($table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('code')->unique();
                $table->string('sku')->nullable();
                $table->integer('price')->default(0);
                $table->integer('original_price')->default(0);
                $table->string('status')->default('active');
                $table->string('category')->nullable();
                $table->string('brand')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        $successCount = 0;

        foreach ($resData['data'] as $item) {
            if (is_array($item) && isset($item['buyer_sku_code'])) {
                $sellerStatus = $item['seller_product_status'] ?? false;
                $buyerStatus = $item['buyer_product_status'] ?? false;

                if ($sellerStatus == true && $buyerStatus == true) {
                    $originalPrice = (int) ($item['price'] ?? 0);
                    $sellPrice = $originalPrice + $markupFlat;

                    DB::table('products')->updateOrInsert(
                        ['code' => $item['buyer_sku_code']],
                        [
                            'name' => $item['product_name'] ?? 'Produk PPOB',
                            'sku' => $item['buyer_sku_code'],
                            'price' => $sellPrice,
                            'original_price' => $originalPrice,
                            'status' => 'active',
                            'category' => strtolower($item['category'] ?? 'umum'),
                            'brand' => strtolower($item['brand'] ?? 'umum'),
                            'description' => $item['desc'] ?? '',
                            'updated_at' => now()
                        ]
                    );
                    $successCount++;
                }
            }
        }

        $this->info("Berhasil sinkronisasi {$successCount} produk dari Digiflazz.");
    }
}
