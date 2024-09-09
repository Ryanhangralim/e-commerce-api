<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken('user login')->plainTextToken;
    }

    public function logout()
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->tokens()->delete();
    }

    public function Register(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'min:1'],
            'last_name' => ['required', 'string', 'min:1'],
            'username' => ['required', 'string', 'min:4', 'max:255', 'regex:/^\S*$/u'],
            'email' => ['required', 'unique:users', 'email'],
            'password' => ['required', 'min:5', 'confirmed'],
            'phone_number' => ['required', 'numeric']
        ]);

        // Hash password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Insert data to database
        $user = User::create($validatedData);

        return response()->json([
            'statusCode' => 201,
            'message' => 'User successfully registered',
        ], 201);
    }
}
