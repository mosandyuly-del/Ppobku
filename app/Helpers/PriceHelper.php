<?php

namespace App\Helpers;

class PriceHelper
{
    public static function getSellingPrice($costPrice)
    {
        if ($costPrice <= 10000) {
            return ceil(($costPrice + 1500) / 100) * 100;
        }

        if ($costPrice <= 50000) {
            return floor(($costPrice + 1700) / 1000) * 1000 + 800;
        }

        return floor(($costPrice + 2000) / 1000) * 1000 + 900;
    }
}
