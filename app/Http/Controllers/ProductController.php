<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index ()
    {
        $products = Product::query()->orderby( 'updated_at', 'desc')->paginate(5);
        return view('product.index', [
            'products' => $products
        ]);
    }

    public function view(Product $product)
{
       Return view('product.view', ['product' => $product]);
}

}
