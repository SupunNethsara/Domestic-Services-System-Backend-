<?php
//
//namespace App\Http\Controllers;
//
//use App\Models\User;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;
//
//class ProfileController extends Controller
//{
//    public function createProfile(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'username' => 'required|string|max:255|unique:profiles',
//            'about' => 'nullable|string',
//            'fname' => 'required|string|max:255',
//            'lname' => 'required|string|max:255',
//            'email' => 'required|email|unique:profiles',
//            'country' => 'required|string|max:255',
//            'address' => 'required|string|max:255',
//            'city' => 'required|string|max:255',
//            'province' => 'required|string|max:255',
//            'profileImage' => 'nullable|url',
//            'coverImage' => 'nullable|url',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'status' => 'error',
//                'message' => 'Validation failed',
//                'errors' => $validator->errors()
//            ], 422);
//        }
//
//        try {
//            $profile = \App\Models\Profile::create([
//                'username' => $request->username,
//                'about' => $request->about,
//                'fname' => $request->fname,
//                'lname' => $request->lname,
//                'email' => $request->email,
//                'country' => $request->country,
//                'address' => $request->address,
//                'city' => $request->city,
//                'province' => $request->province,
//                'profile_image' => $request->profileImage,
//                'cover_image' => $request->coverImage,
//            ]);
//
//            return response()->json([
//                'status' => 'success',
//                'message' => 'Profile created successfully',
//                'data' => $profile
//            ], 201);
//        } catch (\Exception $e) {
//            return response()->json([
//                'status' => 'error',
//                'message' => 'Profile creation failed',
//                'error' => $e->getMessage()
//            ], 500);
//        }
//    }
//
//    public function show($userId)
//    {
//        $user = User::findOrFail($userId);
//
//        if (!$user->profile) {
//            return response()->json([
//                'message' => 'Profile not found',
//                'profile_exists' => false
//            ], 404);
//        }
//
//        return response()->json([
//            'profile' => $user->profile,
//            'profile_complete' => $user->profile->is_complete
//        ]);
//    }
//}


namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Profile;
use App\Models\Workers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile
     */
    public function show(Request $request)
    {
        try {
            $profile = $request->user()->profile;

            if (!$profile) {
                return response()->json([
                    'message' => 'Profile not found',
                    'profile_exists' => false
                ], 404);
            }

            $responseData = [
                'profile' => $profile,
                'profile_complete' => $profile->is_complete
            ];
            $worker = Workers::where('email', $profile->email)->first();
            $clients = Client::where('email', $profile->email)->first();
            if ($worker) {
                $profileData = $profile->toArray();
                $profileData['mobile'] = $worker->mobile;

                $responseData['profile'] = $profileData;
            }
            elseif($clients){
                $profileData = $profile->toArray();
                $profileData['mobile'] = $clients->mobile;

                $responseData['profile'] = $profileData;
            }
            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error('Profile fetch error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:profiles',
            'about' => 'nullable|string',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'profile_image' => 'nullable|url',
            'cover_image' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if ($request->user()->profile) {
                return response()->json([
                    'message' => 'Profile already exists',
                    'profile_exists' => true
                ], 409);
            }

            $profile = $request->user()->profile()->create($validator->validated());

            return response()->json([
                'message' => 'Profile created successfully',
                'profile' => $profile,
                'profile_complete' => $profile->is_complete
            ], 201);

        } catch (\Exception $e) {
            Log::error('Profile creation error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Profile creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the authenticated user's profile
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:profiles,username,' . $request->user()->profile->id,
            'about' => 'nullable|string',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'profile_image' => 'nullable|url',
            'cover_image' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $profile = $request->user()->profile;

            if (!$profile) {
                return response()->json([
                    'message' => 'Profile not found',
                    'profile_exists' => false
                ], 404);
            }

            $profile->update($validator->validated());

            return response()->json([
                'message' => 'Profile updated successfully',
                'profile' => $profile->fresh(),
                'profile_complete' => $profile->is_complete
            ]);

        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Profile update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the authenticated user's profile
     */
    public function destroy(Request $request)
    {
        try {
            $profile = $request->user()->profile;

            if (!$profile) {
                return response()->json([
                    'message' => 'Profile not found'
                ], 404);
            }

            $profile->delete();

            return response()->json([
                'message' => 'Profile deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Profile deletion error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Profile deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
