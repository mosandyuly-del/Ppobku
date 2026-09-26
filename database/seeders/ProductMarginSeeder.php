<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductMarginSeeder extends Seeder
{
    public function run()
    {
        $products = DB::table('products')->get();

        foreach ($products as $product) {
            $modal = $product->price_modal;
            $hargaJual = $modal;

            if ($modal <= 10000) {
                $hargaJual = ceil(($modal + 1500) / 100) * 100;
            } elseif ($modal <= 50000) {
                $hargaJual = floor(($modal + 1700) / 1000) * 1000 + 800;
            } else {
                $hargaJual = floor(($modal + 2000) / 1000) * 1000 + 900;
            }

            DB::table('products')->where('id', $product->id)->update([
                'price_sell' => $hargaJual,
                'price_original' => $hargaJual + 1500,
            ]);
        }
    }
}
