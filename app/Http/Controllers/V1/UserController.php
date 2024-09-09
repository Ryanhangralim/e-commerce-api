<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\UserDetailResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        // return response()->json(['user-data' => $users]);
        return response()->json(['statusCode' => 200, 
                                'message' => 'Data retrieved successfully',
                                'data' => ['users' => UserResource::collection($users)]], 200);
        // dd(UserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = User::with('role:id,title')->findOrFail($user->id);
        return response()->json(['statusCode' => 200, 
        'message' => 'Data retrieved successfully',
        'data' => ['user' => new UserDetailResource($user)]], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
