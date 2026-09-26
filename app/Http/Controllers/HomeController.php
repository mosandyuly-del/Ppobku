<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function category(Request $request, $slug = 'pulsa')
    {
        $searchNumber = $request->query('phone');
        $operator = $this->detectOperator($searchNumber);

        $query = DB::table('products')->where('category_slug', $slug);

        if ($operator) {
            $query->where('brand', 'LIKE', '%' . $operator . '%');
        }

        $products = $query->get();

        return view('category', [
            'slug' => $slug,
            'products' => $products,
            'phone' => $searchNumber,
            'operator' => $operator,
            'showExpiryFilter' => in_array(strtolower($slug), ['paket-data', 'data']),
        ]);
    }

    private function detectOperator($phone)
    {
        if (!$phone) return null;

        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        $prefix = substr($phone, 0, 4);

        $operators = [
            'Telkomsel' => ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'],
            'Indosat'   => ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
            'XL'        => ['0817', '0818', '0819', '0859', '0877', '0878'],
            'Axis'      => ['0831', '0832', '0833', '0838'],
            'Tri'       => ['0895', '0896', '0897', '0898', '0899'],
            'Smartfren' => ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'],
        ];

        foreach ($operators as $brand => $prefixes) {
            if (in_array($prefix, $prefixes)) {
                return $brand;
            }
        }

        return null;
    }
}
