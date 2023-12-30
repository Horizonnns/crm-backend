<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    // Admin register
    public function authAdmin(Request $request) {
    // Admin validation
        $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    // Checking the validation
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Create a AdminUser
       $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phonenum' => $request->input('phonenum'),
            'role' => 'admin',
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json(['success' => true, 'user' => $user], 201);
    }

    // Admin create user
    public function authUser(Request $request) {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phonenum' => $request->input('phonenum'),
            'role' => $request->input('role'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Get the role from the request (front-office or back-office)
        $roleName = $request->input('role');
        $user->assignRole($roleName);
        return response()->json(['success' => true, 'user' => $user], 200);
    }

    // Admin login
    public function login(Request $request) 
    {
        if(!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24); // 1 day

        return response([
            'message' => 'Success', 'accessToken' => $token,
        ])->withCookie($cookie);
    }
    
    // Get user
    public function user() 
    {
        return Auth::user();
    }

    // Admin logout
    public function logout() 
    {
       $cookie = Cookie::forget('jwt');

       return response([
        'message' => 'Success',
       ])->withCookie($cookie);
    }
}
