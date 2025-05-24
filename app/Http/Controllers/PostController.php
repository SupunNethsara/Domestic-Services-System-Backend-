<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\WorkerPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Exception;
use function Laravel\Prompts\error;

class PostController extends Controller
{
    public function getallposts()
    {
        try {
            $posts = Post::with(['user.client', 'user.profile'])->get();
            $workerPosts = WorkerPost::with(['user.worker', 'user.profile'])->get();

            $mergedPosts = $posts->merge($workerPosts);

            return response()->json([
                'status' => 'success',
                'posts' => $mergedPosts
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch all posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'image' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create the post
            $post = Post::create([
                'user_id' => Auth::id(),
                'content' => $request->content,
                'image' => $request->image
            ]);


            $post->load('user');

            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully',
                'data' => $post
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show()
    {
        try {
            $posts = Post::with(['user.Profile'])->get();

            return response()->json([
                'status' => 'success',
                'posts' => $posts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function workerstore(Request $request){
        $validator = Validator::make($request->all(),[
            'content' => 'required|string',
            'image' => 'nullable|url'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        try{
            $post = WorkerPost::create([
                'user_id' => Auth::id(),
                'content' => $request->content,
                'image' => $request->image
            ]);
            $post->load('user');

            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully',
                'data' => $post
            ], 201);

        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    //show Worker Post
    public function showWorkerPosts()
    {
        try {
            $posts = WorkerPost::with(['user.profile'])->get();

            return response()->json([
                'status' => 'success',
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    /**  Workers Request Post */

}
