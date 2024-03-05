<?php

namespace Model;

class Product
{
    public int $id;
    public string $name;
    public string $price;
    public string $created_at;
    public int $category_id;

    public static function constraints(): array
    {
        return [
            'max_name_length' => 256,
            'max_price_length' => 10,
            'price_precision' => 2,
        ];
    }

    public static function FromArray(array $row): Product
    {
        $product = new Product();
        $product->id = $row['id'] ?? 0;
        $product->name = $row['name'];
        $product->price = $row['price'];
        $product->created_at = $row['created_at'];
        $product->category_id = $row['category_id'];

        return $product;
    }
}