<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductDiscountService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected readonly ProductDiscountService $productDiscountService) {
      
    }
    public function index(Request $request)
    {
        $category =  $request->query('category');
        $priceLessThan =  $request->query('priceLessThan');
        $perPage = $request->query('per_page', 5);

        $productQuery = Product::query();
        if ($category != null) $productQuery->where('category', 'LIKE', '%' . $category . '%');
        if ($priceLessThan != null && is_numeric($priceLessThan)) $productQuery->where('price', '<=', $priceLessThan);
        $products =  $productQuery->simplePaginate($perPage);
        foreach ($products as $product){
            /** @var  Product $product */
            $product->price = $this->productDiscountService->productPricing($product);
        }
        return $products;
    }
}
