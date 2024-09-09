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
        $data = [
            'statusCode' => 200,
            'message' => 'Data retrieved successfully',
            'data' => Business::all()
        ];

        return response()->json($data, 200);
    }

    // Show single business detail
    public function show(Business $business)
    {
        if($business)
        {
            return response()->json(['statusCode' => 200,
                                    'message' => 'Data retrieved successfully',
                                    'data' => ['businessData' => $business->makeHidden(['products']), 
                                               'products' => $business->products]], 200);
        } else {
            return response()->json(['statusCode' => 404,
                                    'message' => 'Business not found'], 404);
        }
    }
    
    // data for business main page
    public function businessMainPage(Business $business)
    {
        if($business)
        {
            return response()->json(['statusCode' => 200,
                                    'message' => 'Data retrieved successfully',
                                    'data' => ['businessData' => new BusinessMainPageResource($business), 
                                                'businessProducts' => BusinessProductResource::collection($business->products)]], 200);
        } else {
            return response()->json(['statusCode' => 404,
                                    'message' => 'Business not found'], 404);
        }
    }
}
