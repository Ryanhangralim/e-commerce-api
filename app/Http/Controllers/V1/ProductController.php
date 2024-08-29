<?php

namespace App\Http\Controllers\V1;

use App\Models\Product;
use App\Models\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Resources\V1\ProductTableResource;
use App\Http\Resources\V1\ProductDetailResource;
use App\Http\Resources\V1\ProductDetailReviewResource;

class ProductController extends Controller
{
    protected $product_picture_path, $business_profile_path, $profile_picture_path;
    
    // constructor
    public function __construct()
    {
        $this->product_picture_path = env('PRODUCT_PICTURE_PATH');
        $this->business_profile_path = env('BUSINESS_PROFILE_PATH');
        $this->profile_picture_path = env('PROFILE_PICTURE_PATH');
    }

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

    // Store new product
    public function storeProduct(Request $request)
    {
        // Get seller business id
        $business = Auth::user()->business;

        // Create new image manager
        $manager = new ImageManager(new Driver());

        // validate user input
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'brand' => ['required', 'string', 'min:1', 'max:255'],
            'price' => ['required', 'numeric', 'min:500'],
            'category_id' => ['required'],
            'product_image' => ['image', 'mimes:jpeg,png', 'max:1024']
        ]);

        $validatedData['business_id'] = $business->id;
        $validatedData['slug'] = create_slug($validatedData['name'] . " " . $business->id);

        // Process and save image
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $file_name = $business->slug . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = public_path($this->product_picture_path . $file_name); 

            // Save new profile picture
            $manager->read($image->getPathname())->resize(300, 300)->save($path);
    
            // Update profile picture
            $validatedData['image'] = $file_name; 
        }

        Product::create($validatedData);

        return response()->json(['message' => "Product Added successfully!"], 201);
    }


    // update product
    public function updateProduct(Request $request, Product $product)
    {
        // Create new image manager
        $manager = new ImageManager(new Driver());

        // validate user input
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'brand' => ['required', 'string', 'min:1', 'max:255'],
            'price' => ['required', 'numeric', 'min:500'],
            'category_id' => ['required'],
            'product_image' => ['image', 'mimes:jpeg,png', 'max:1024']
        ]);

        $validatedData['business_id'] = $product->business_id;
        $validatedData['slug'] = create_slug($validatedData['name'] . " " . $product->business_id);

        // get old image path
        $oldProductPicture = $product->image;
        $oldProductPicturePath = public_path($this->product_picture_path . $oldProductPicture);

        // Process and save image
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $file_name = $product->business->slug . '-' . time() . '.' . $image->getClientOriginalExtension();
            $path = public_path($this->product_picture_path . $file_name); 

            // Delete old product picture if exist
            if($oldProductPicture && File::exists($oldProductPicturePath)){
                File::delete($oldProductPicturePath);
            }

            // Save new profile picture
            $manager->read($image->getPathname())->resize(300, 300)->save($path);
    
            // Update profile picture
            $validatedData['image'] = $file_name; 
        } else {
            // Use old image path
            $validatedData['image'] = $oldProductPicture;
        }

        $product->update($validatedData);

        return response()->json(['message' => "Product updated successfully!"], 201);
    }

    // View product detail for customer
    public function customerProductDetail(Product $product)
    {
        if($product){
            return response()->json(["product-data" => $product->makeHidden(['business', 'reviews']), "business-data" => $product->business, "product-reviews" => $product->reviews], 200);
        } else {

        }
    }
}
