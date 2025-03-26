<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function createProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:profiles',
            'about' => 'nullable|string',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|unique:profiles',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'profileImage' => 'nullable|url',
            'coverImage' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $profile = \App\Models\Profile::create([
                'username' => $request->username,
                'about' => $request->about,
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'country' => $request->country,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'profile_image' => $request->profileImage,
                'cover_image' => $request->coverImage,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Profile created successfully',
                'data' => $profile
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profile creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
