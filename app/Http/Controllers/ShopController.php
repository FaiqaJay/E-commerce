<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $size = (int) $request->query('size', 12);
        if ($size < 1 || $size > 100) {
            $size = 12;
        }

        $order = (int) $request->query('order', -1);
        $f_brands = $request->query('brands'); //1,3,5
        $f_categories = $request->query('categories'); //2,4
        $min_price = (float) $request->query('min_price', 0);
        $max_price = (float) $request->query('max_price', 0);

        //values come from this fixed list only, never from the request
        switch ($order) {
            case 1:
                $o_column = 'created_at';
                $o_order = 'DESC';
                break;
            case 2:
                $o_column = 'created_at';
                $o_order = 'ASC';
                break;
            case 3:
                $o_column = 'COALESCE(sale_price, regular_price)';
                $o_order = 'ASC';
                break;
            case 4:
                $o_column = 'COALESCE(sale_price, regular_price)';
                $o_order = 'DESC';
                break;
            default:
                $o_column = 'id';
                $o_order = 'DESC';
        }

        $brands = Brand::withCount('products')->orderBy('name', 'ASC')->get();
        $categories = Category::withCount('products')->orderBy('name', 'ASC')->get();

        $products = Product::with('category')
            ->when($f_brands, function ($query) use ($f_brands) {
                $query->whereIn('brand_id', array_filter(explode(',', $f_brands)));
            })
            ->when($f_categories, function ($query) use ($f_categories) {
                $query->whereIn('category_id', array_filter(explode(',', $f_categories)));
            })
            ->when($min_price > 0, fn ($q) => $q->whereRaw('COALESCE(sale_price, regular_price) >= ?', [$min_price]))
            ->when($max_price > 0, fn ($q) => $q->whereRaw('COALESCE(sale_price, regular_price) <= ?', [$max_price]))
            ->orderByRaw("{$o_column} {$o_order}")
            ->paginate($size)
            ->withQueryString();

        return view('shop', compact('products', 'size', 'order',
            'brands', 'f_brands', 'categories', 'f_categories', 'min_price', 'max_price'));
    }

    public function product_details($product_slug)
    {
        $product = Product::with('category')->where('slug', $product_slug)->firstOrFail();
        $rproducts = Product::with('category')
            ->where('slug', '<>', $product_slug)
            ->take(8)
            ->get();

        return view('details', compact('product', 'rproducts'));
    }
}
