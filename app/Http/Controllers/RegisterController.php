<?php
//
//namespace App\Http\Controllers;
//
//use App\Models\Client;
//use App\Models\Workers;
//use Illuminate\Http\Request;
//
//class RegisterController extends Controller
//{
//function clientregister(Request $request)
//{
//    $request->validate([
//        'fname' => 'required|string|max:255',
//        'lname' => 'required|string|max:255',
//        'mobile' => 'required|string|max:15',
//        'email' => 'nullable|email|max:255|unique:clients,email',
//        'password' => 'required|string|min:6|confirmed',
//    ]);
//    $client = Client::create([
//        'first_name' => $request->fname,
//        'last_name' => $request->lname,
//        'mobile' => $request->mobile,
//        'email' => $request->email,
//        'password' => $request->password,
//    ]);
//    return response()->json([
//        'message' => 'Registration successful!',
//        'data' => $client,
//    ], 201);
//}
//
//function workerregister(Request $request)
//{
//    $request->validate([
//        'fname' => 'required|string|max:255',
//        'lname' => 'required|string|max:255',
//        'mobile' => 'required|string|max:15',
//        'email' => 'nullable|email|max:255|unique:clients,email',
//        'password' => 'required|string|min:6|confirmed',
//    ]);
//    $workers = Workers::create([
//        'first_name' => $request->fname,
//        'last_name' => $request->lname,
//        'mobile' => $request->mobile,
//        'email' => $request->email,
//        'password' => $request->password,
//    ]);
//    return response()->json([
//        'message' => 'Registration successful!',
//        'data' => $workers,
//    ], 201);
//}
//}
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Workers;
use App\Models\User; // Import the User model
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function clientregister(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email|max:255|unique:clients,email',
            'password' => 'required|string|min:6|confirmed',
        ]);


        $client = Client::create([
            'first_name' => $request->fname,
            'last_name' => $request->lname,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);


       User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'client',
        ]);

        return response()->json([
            'message' => 'Registration successful!',
            'data' => $client,
        ], 201);
    }

    public function workerregister(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email|max:255|unique:workers,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create the worker entry
        $worker = Workers::create([
            'first_name' => $request->fname,
            'last_name' => $request->lname,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);


        User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'worker',
        ]);

        return response()->json([
            'message' => 'Registration successful!',
            'data' => $worker,
        ], 201);
    }
}
