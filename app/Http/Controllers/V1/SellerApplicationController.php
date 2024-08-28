<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Models\SellerApplication;
use App\Http\Resources\V1\SellerApplicationDetailResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SellerApplicationController extends Controller
{
    // return application data if found
    public function findUserApplication()
    {
        $application = SellerApplication::with('user:id,username')->where('user_id', Auth::id())->first();
        // Check if user has an existing application
        if($application)
        {
            return new SellerApplicationDetailResource($application);
        }
        return response()->json(['message' => 'No application found'], 404);
    }

    // New seller application
    public function newSellerApplication(Request $request)
    {
        $user_id = Auth::id();
        $validatedData = $request->validate([
            'business_name' => ['required', 'min:3', 'max:50'],
            'business_description' => ['required', 'min:10', 'unique:seller_applications']
        ]);

        // Hash password
        $validatedData['user_id'] = $user_id;

        // Insert data to database
        SellerApplication::create($validatedData);
        // Mail::to($user->email)->send(new SellerApplicationReceived($user, $application));

        return response()->json(['message' => 'Seller Application Received'], 201);
    }

    // return application fetched
    public function fetchApplications(Request $request)
    {
        $status = $request->get('status');

        if($status === "all"){
            $applications = SellerApplication::with('user')->get();
        } else {
            $applications = SellerApplication::with('user')->where('application_status', $status)->get();
        }
        return response()->json(['applications' => $applications], 200);
    }

    // verify application
    public function verifyApplication(Request $request)
    {
        // Get application id from request
        $applicationID = $request["applicationID"];
        
        // Update status and role
        SellerApplication::where('id', $applicationID)->update(['application_status' => 'approved']);

        // Get application information
        $application = SellerApplication::find($applicationID);
        $userID = $application->user_id;

        // Add record to business
        $businessData = [
            'user_id' => $userID,
            'name' => $application->business_name,
            'slug' => create_slug($application->business_name),
            'description' => $application->business_description
        ];

        BusinessController::newBusiness($businessData);

        // Redirect
        return response()->json(['message' => "Application Verified"], 201);
    }

    // reject application
    public function rejectApplication(Request $request)
    {
        // Get application id from request
        $applicationID = $request["applicationID"];
        
        // Update status and role
        SellerApplication::where('id', $applicationID)->update(['application_status' => 'rejected']);

        // return response
        return response()->json(['message' => "Application Rejected"], 201);
    }
}
