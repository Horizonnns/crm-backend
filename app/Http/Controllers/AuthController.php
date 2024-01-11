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
        ])->assignRole('admin');

        return response()->json(['success' => true, 'user' => $user], 201);
    }

    // Admin create user
    public function authUser(Request $request) {
        // User validation
        $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'job_title' => 'required|string|max:255', 
        'phonenum' => 'required|string|max:9',
        'role' => 'required|string|max:12',
    ]);

        // Checking the validation
        if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phonenum' => $request->input('phonenum'),
            'role' => $request->input('role'),
            'job_title' => $request->input('job_title'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Get the role from the request (front-office or back-office)
        $roleName = $request->input('role');
        $user->assignRole($roleName);
        
        $users = User::all();
        return response()->json(['success' => true, 'user' => $user, 'users' => $users], 200);
    }

    // Admin login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('jwt', $token, 60 * 24); // 1 day

            $users = User::all();
            return response()->json(['user' => $user, 'accessToken' => $token, 'users' => $users], 200)->withCookie($cookie);
    }

    return response()->json(['error' => $validator->errors(), 'message' => 'Invalid credentials!'], Response::HTTP_UNAUTHORIZED);
    }

    // Delete user
    public function deleteUser($id) {
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $user->delete();

    $users = User::all();

    return response()->json(['success' => true, 'users' => $users], 200);
    }

    // Update user
    public function updateUser(Request $request, $id) 
    {
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,'.$id,
        'phonenum' => 'required|string',
        'role' => 'required|string|in:admin,front-office,back-office',
        'job_title' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $user->update([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'phonenum' => $request->input('phonenum'),
        'role' => $request->input('role'),
        'job_title' => $request->input('job_title'),
    ]);

    $user->syncRoles([$request->input('role')]);
    $users = User::all();

    return response()->json(['success' => true, 'user' => $user, 'users' => $users], 200);
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
