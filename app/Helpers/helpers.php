<?php

use App\Models\Product;
use Illuminate\Support\Str;

if (!function_exists('create_slug')) {
    function create_slug($string)
    {
        return Str::slug($string, '-');
    }
}

if (!function_exists('calculateDiscount')) {
    function calculateDiscount(Product $product)
    {
        return $product->price - ($product->price * ($product->discount / 100));
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($string)
    {
        return number_format($string, 0, ',', '.');
    }
}

if (!function_exists('printRating')) {
    function printRating($score)
    {
        $rating = "";
        for($i = 0; $i < $score; $i++)
        {
            $rating .= '★';
        }
        
        return $rating;
    }
}

if (!function_exists('isBusinessOwner')) {
    function isBusinessOwner($owner_id)
    {
        return Auth()->user()->id == $owner_id;
    }
}
