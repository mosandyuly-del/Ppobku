<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Akun Admin
        User::create([
            'name' => 'Administrator PPOB',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
        ]);

        // Produk
        Product::create(['sku_code' => 'TSEL5K', 'name' => 'Pulsa Telkomsel 5.000', 'brand' => 'Telkomsel', 'price_original' => 5100, 'price_sell' => 6000]);
        Product::create(['sku_code' => 'TSEL10K', 'name' => 'Pulsa Telkomsel 10.000', 'brand' => 'Telkomsel', 'price_original' => 10100, 'price_sell' => 11000]);
        Product::create(['sku_code' => 'ISAT5K', 'name' => 'Pulsa Indosat 5.000', 'brand' => 'Indosat', 'price_original' => 5150, 'price_sell' => 6000]);
        Product::create(['sku_code' => 'XL5K', 'name' => 'Pulsa XL 5.000', 'brand' => 'XL', 'price_original' => 5150, 'price_sell' => 6000]);

        // Pembayaran
        PaymentMethod::create(['code' => 'BCA', 'name' => 'Bank BCA', 'account_number' => '8830123456', 'account_name' => 'PT Toko PPOB']);
        PaymentMethod::create(['code' => 'MANDIRI', 'name' => 'Bank Mandiri', 'account_number' => '1370009876543', 'account_name' => 'PT Toko PPOB']);
        PaymentMethod::create(['code' => 'QRIS', 'name' => 'QRIS Instant', 'account_name' => 'Toko PPOB', 'qr_image' => 'https://via.placeholder.com/300x300.png?text=QRIS']);
    }
}
