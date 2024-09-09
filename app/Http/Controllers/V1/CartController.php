<?php

namespace App\Http\Controllers\V1;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    // view cart
    public function viewCart()
    {
        $userCarts = Auth()->user()->carts
            ->load('product.business')
            ->groupBy(function($cart) {
                return $cart->product->business->slug; // Group by business slug
            })
            ->map(function($carts) {
                return $carts->map(function($cart) {
                    return [
                        'image' => $cart->product->image,
                        'name' => $cart->product->name,
                        'price' => calculateDiscount($cart->product),
                        'stock' => $cart->product->stock,
                        'quantity' => $cart->quantity
                    ];
                });
            });

    return response()->json(['statusCode' => 200, 
                            'message' => 'Data retrieved successfully',
                            'data' => ['cartData' => $userCarts]]);
    }

    // add product to cart
    public function addProduct(Request $request, Product $product)
    {
        $product_id = $product->id;
        $user_id = Auth()->user()->id;

        // Validate quantity input
        $validatedData = $request->validate([
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        // Check stock
        if($validatedData['quantity'] > $product->stock)
        {
            return response()->json(['message' => 'Stock unavailable!'], 200);
        }

        // Add additional information
        $validatedData['user_id'] = $user_id;
        $validatedData['product_id'] = $product_id;
        
        // Check if product is in cart
        if($existing_cart = Cart::where('user_id', $user_id)
               ->where('product_Id', $product_id)
               ->first()){
                if($validatedData['quantity'] + $existing_cart->quantity > $product->stock)
                {
                    return response()->json(['statusCode' => 200, 'message' => 'Product out of stock!'], 200);
                }
            $existing_cart->quantity += $validatedData['quantity'];
            $existing_cart->save();
        } else {
            // Add product to cart
            Cart::create($validatedData);
        }

        return response()->json(['statusCode' => 201,
                                'message' => 'Product added to cart!'], 201);
    }

    // Update quantity
    public function updateQuantity(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'cart_product_id' => ['required', 'integer', 'min:1', 'exists:carts,id'],
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        // Find the cart
        $cart = Cart::find($validatedData['cart_product_id']);

        // Check stock
        if($validatedData['quantity'] > $cart->product->stock)
        {
            return response()->json(['statusCode' => 200, 'message' => 'Stock unavailable!'], 200);
        }

        // Update to new quantity
        $cart->quantity = $validatedData['quantity'];
        $cart->save();

        // Calculate new total price
        $newTotal = calculateDiscount($cart->product) * $cart->quantity;
        $newTotalFormatted = formatNumber($newTotal);

        // Return JSON response
        return response()->json([
            'statusCode' => 201,
            'message' => 'Quantity updated',
            'data' => ['newTotalFormatted' => $newTotalFormatted]
        ], 201);
    }

        // delete product function
        public function deleteProduct(Request $request)
        {
            $validatedData = $request->validate([
                'cart_product_id' => ['required', 'integer', 'exists:carts,id']
            ]);
        
            $cart = Cart::find($validatedData['cart_product_id']);
            if ($cart) {
                $cart->delete();
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Product deleted',
                ]);
            }
        
            return response()->json(['statusCode' => 404, 'message' => 'Fail to delete product'], 404);
        }

        // Remove cart
        public static function deleteCart(Cart $cart)
        {
            $cart->delete();
        }
}
