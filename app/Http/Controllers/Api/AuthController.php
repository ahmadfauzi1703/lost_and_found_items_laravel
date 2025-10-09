<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            // 'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $deviceName = $request->device_name ?? 'api-' . time();

        return response()->json([
            'token' => $user->createToken($deviceName)->plainTextToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name ?? $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        return new UserResource($user);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pengguna',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:15',
            // 'first_name' => 'nullable|string|max:255', // Opsional
            // 'last_name' => 'nullable|string|max:255',  // Opsional
        ]);


        if (!isset($validated['first_name']) && !isset($validated['last_name'])) {
            $nameParts = explode(' ', $validated['name'], 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null, // Tambahkan phone
            'role' => 'user',
            'first_name' => $validated['first_name'] ?? $firstName ?? '',
            'last_name' => $validated['last_name'] ?? $lastName ?? '',
        ]);

        // Buat token untuk user baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user, // User sudah termasuk field phone
            'access_token' => $token,
            // 'token_type' => 'Bearer',
        ], 201);
    }

    public function updateProfile(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'nim' => 'sometimes|string|max:20|unique:pengguna,nim,' . Auth::id(),
            'email' => 'sometimes|string|email|max:255|unique:pengguna,email,' . Auth::id(),
            'phone_number' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get authenticated user as Eloquent model
        $user = User::find(Auth::id());

        // Update fields that are present in the request
        if ($request->has('first_name')) {
            $user->first_name = $request->first_name;
        }

        if ($request->has('last_name')) {
            $user->last_name = $request->last_name;
        }

        if ($request->has('nim')) {
            $user->nim = $request->nim;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('phone')) {
            $user->phone_number = $request->phone_number;
        }

        if ($request->has('address')) {
            $user->address = $request->address;
        }

        // Save the updated user
        $user->save();

        // Return the updated user data
        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}
