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
        // Ambil credential dari tabel settings atau env
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
        if ($markupFlat <= 0) $markupFlat = 1500; // Default markup Rp 1.500

        if (empty($username) || empty($apiKey)) {
            $this->error('Digiflazz Username or Key is missing.');
            return;
        }

        // Generate MD5 Sign
        $sign = md5($username . $apiKey . 'pricelist');
        
        $payload = [
            'cmd' => 'prepaid',
            'username' => $username,
            'sign' => $sign
        ];

        // Hit API Digiflazz
        $ch = curl_init('https://api.digiflazz.com/v1/price-list');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $response = curl_exec($ch);
        curl_close($ch);

        $resData = json_decode($response, true);

        if (!isset($resData['data']) || !is_array($resData['data'])) {
            $this->error('Gagal mengambil data dari API Digiflazz.');
            return;
        }

        // Buat tabel products jika belum ada
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
            // Hanya simpan produk yang normal/aktif di Digiflazz
            if ($item['seller_product_status'] == true && $item['buyer_product_status'] == true) {
                
                $originalPrice = (int) $item['price'];
                $sellPrice = $originalPrice + $markupFlat;

                DB::table('products')->updateOrInsert(
                    ['code' => $item['buyer_sku_code']],
                    [
                        'name' => $item['product_name'],
                        'sku' => $item['buyer_sku_code'],
                        'price' => $sellPrice,
                        'original_price' => $originalPrice,
                        'status' => 'active',
                        'category' => strtolower($item['category']),
                        'brand' => strtolower($item['brand']),
                        'description' => $item['desc'] ?? '',
                        'updated_at' => now()
                    ]
                );
                $successCount++;
            } else {
                // Jika produk sedang gangguan/ditutup dari Digiflazz, ubah status di web kita jadi nonaktif
                DB::table('products')
                    ->where('code', $item['buyer_sku_code'])
                    ->update(['status' => 'inactive', 'updated_at' => now()]);
            }
        }

        $this->info("Berhasil sinkronisasi {$successCount} produk dari Digiflazz.");
    }
}
