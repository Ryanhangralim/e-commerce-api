<?php

namespace App\Http\Controllers\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductDetailResource;
use App\Http\Resources\V1\ProductDetailReviewResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\ProductTableResource;
use App\Models\Business;

class ProductController extends Controller
{
    // View seller own product
    public function viewProducts()
    {
        // Check if user has a business
        if (Auth::user()->business) {
            return response()->json(["products" => ProductTableResource::collection(Auth::user()->products)], 200);
        }

        return response()->json(["message" => "No business found"], 404);
    }

    // View product detail for seller
    public function viewProductDetail(Product $product)
    {
        if($product){
            return response()->json(["product_data" => new ProductDetailResource($product), "reviews" => ProductDetailReviewResource::collection($product->reviews)], 201);
        } else {
            return response()->json(["message" => "Product not found"], 404);
        }
    }

    // Add product stock
    public function addStock(Product $product, Request $request)
    {
        $request->validate([
            'numberOfProducts' => ['required', 'min:1', 'integer', 'max:2147483647']
        ]);

        // Update stock
        $product->stock += (int) $request->numberOfProducts; 
        $product->save();

        return response()->json(['message' => "Stock added successfully!"], 201);
    }

    // Subtract stock
    public static function subtractStock(Product $product, $quantity)
    {
        // Subtract stock
        $product->stock -= $quantity;
        $product->save();
    }

    // Set product dicount
    public function setDiscount(Product $product, Request $request)
    {
        $request->validate([
            'discount' => ['required', 'min:0', 'integer', 'max:99']
        ]);

        // Update stock
        $product->discount = (int) $request->discount; 
        $product->save();

        return response()->json(['message' => "Discount updated successfully!"], 201);
    }
}
