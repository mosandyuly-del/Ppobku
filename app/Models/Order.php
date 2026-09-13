<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'sku_code', 'sku_code');
    }
}
