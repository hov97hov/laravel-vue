<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Interfaces\PostInterface;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Route;

class PostController extends Controller
{
    /**
     * @var PostInterface
     */
    protected PostInterface $postService;

    /**
     * @param PostInterface $postService
     */
    public function __construct(PostInterface $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $posts = $this->postService->getAllPosts();
        $resourceData = PostResource::collection($posts);

        return Inertia::render('Post/Index', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'posts' => $resourceData ?? null
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Post/Create');
    }

    /**
     * @param PostRequest $request
     * @return JsonResponse
     */
    public function store(PostRequest $request): JsonResponse
    {
        try {
            $createdPost = $this->postService->store($request);

            return response()->json([
                'success' => true,
                'message' => 'Data stored successfully',
                'post' => $createdPost,
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error storing data', ['error' => $exception->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * @param Post $post
     * @return Response
     */
    public function show(Post $post)
    {
        return Inertia::render('Post/Show', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): Response
    {
        return Inertia::render('Post/Edit', [
            'post' => $post
        ]);
    }

  /**
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        try {
            $createdPost = $this->postService->update($request, $post);

            return response()->json([
                'success' => true,
                'message' => 'Data updated successfully',
                'post' => $createdPost,
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error storing data', ['error' => $exception->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        try {
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data deleted successfully',
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error storing data', ['error' => $exception->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}
