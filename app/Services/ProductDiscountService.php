<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductDiscountService
{

    private Collection $discounts;

    public function __construct()
    {
        $this->discounts = $this->fetchDiscounts();
    }

    private function originalPrice(Product $product): int
    {
        return $product->price;
    }

    private function finalPrice(Product $product): float|int
    {
        $discountPercentage = $this->discountPercentage($product);
        if ($discountPercentage < 0) return $this->originalPrice($product);
        return $this->originalPrice($product) - (($this->discountPercentage($product) / 100) * $this->originalPrice($product));
    }

    private function discountPercentage(Product $product): ?float
    {
        return $this->pickMaxDiscount($product);
    }

    private function currency(): string
    {
        return Product::CURRENCY;
    }


    public function pickMaxDiscount(Product $product): ?float
    {
        return $this->discounts->whereIn('pointer', [$product->sku, $product->category])->max('percentage');
    }

    public function productPricing(Product $product): array
    {
        return [
            'original' => $this->originalPrice($product),
            'final' => $this->finalPrice($product),
            'discount_percentage' => $this->discountPercentage($product) ? $this->discountPercentage($product) . '%' : null,
            'currency' => $this->currency(),
        ];
    }

    private function fetchDiscounts(): Collection
    {
        return collect([
            [
                'pointer' => 'boots',
                'percentage' => 30,
            ],
            [
                'pointer' => '000003',
                'percentage' => 15,
            ]
        ]);
    }
}
