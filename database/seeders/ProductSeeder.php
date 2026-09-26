<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Pulsa
            ['buyer_sku_code' => 'P5', 'name' => 'Pulsa Telkomsel 5.000', 'category' => 'Pulsa', 'category_slug' => 'pulsa', 'brand' => 'TELKOMSEL', 'price' => 5300, 'price_sell' => 6000],
            ['buyer_sku_code' => 'P10', 'name' => 'Pulsa Telkomsel 10.000', 'category' => 'Pulsa', 'category_slug' => 'pulsa', 'brand' => 'TELKOMSEL', 'price' => 10200, 'price_sell' => 11000],
            ['buyer_sku_code' => 'I5', 'name' => 'Pulsa Indosat 5.000', 'category' => 'Pulsa', 'category_slug' => 'pulsa', 'brand' => 'INDOSAT', 'price' => 5250, 'price_sell' => 6000],
            ['buyer_sku_code' => 'I10', 'name' => 'Pulsa Indosat 10.000', 'category' => 'Pulsa', 'category_slug' => 'pulsa', 'brand' => 'INDOSAT', 'price' => 10150, 'price_sell' => 11000],
            ['buyer_sku_code' => 'X5', 'name' => 'Pulsa XL 5.000', 'category' => 'Pulsa', 'category_slug' => 'pulsa', 'brand' => 'XL', 'price' => 5300, 'price_sell' => 6000],
            
            // Paket Data
            ['buyer_sku_code' => 'D1GB', 'name' => 'Telkomsel Data 1GB 30 Hari', 'category' => 'Paket Data', 'category_slug' => 'paket-data', 'brand' => 'TELKOMSEL', 'price' => 14000, 'price_sell' => 15000],
            ['buyer_sku_code' => 'D2GB', 'name' => 'Indosat Freedom 2GB 30 Hari', 'category' => 'Paket Data', 'category_slug' => 'paket-data', 'brand' => 'INDOSAT', 'price' => 12500, 'price_sell' => 14000],
            
            // PLN Token
            ['buyer_sku_code' => 'PLN20', 'name' => 'Token PLN 20.000', 'category' => 'PLN', 'category_slug' => 'pln', 'brand' => 'PLN', 'price' => 20100, 'price_sell' => 21000],
            ['buyer_sku_code' => 'PLN50', 'name' => 'Token PLN 50.000', 'category' => 'PLN', 'category_slug' => 'pln', 'brand' => 'PLN', 'price' => 50100, 'price_sell' => 51000],
        ];

        foreach ($products as $p) {
            DB::table('products')->updateOrInsert(
                ['buyer_sku_code' => $p['buyer_sku_code']],
                array_merge($p, [
                    'type' => 'PPOB',
                    'status' => 'Active',
                    'description' => 'Proses otomatis kilat 24 jam',
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }
}
