<?php

namespace App\Http\Controllers\V1;

use App\Models\Business;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BusinessProductResource;
use App\Http\Resources\V1\BusinessMainPageResource;

class BusinessController extends Controller
{
    // Create new business
    public static function newBusiness($businessData)
    {
        Business::create($businessData);

        return true;
    }

    // Show all business
    public function index()
    {
        $businesses = Business::all();

        return response()->json($businesses, 200);
    }

    // Show single business detail
    public function show(Business $business)
    {
        if($business)
        {
            return response()->json([$business, $business->products], 200);
        } else {
            return response()->json(["message" => "Business not found"], 200);
        }
    }
    
    // data for business main page
    public function businessMainPage(Business $business)
    {
        if($business)
        {
            return response()->json([new BusinessMainPageResource($business), BusinessProductResource::collection($business->products)], 200);
        } else {
            return response()->json(["message" => "Business not found"], 200);
        }
    }
}
