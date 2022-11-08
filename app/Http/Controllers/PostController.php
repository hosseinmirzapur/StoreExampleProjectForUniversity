<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'only-admin'])->except(['index', 'show']);
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::with('tags')->get();
        return successResponse([
            'posts' => $posts
        ]);
    }

    /**
     * @param PostRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store(PostRequest $request): JsonResponse
    {
        $data = filterData($request->validated());
        $admin = currentUser();
        $post = Post::query()->create([
            'title' => $data['title'],
            'text' => $data['text'],
            'image' => handleFile('/posts', $data['image']),
            'admin_id' => $admin->getId()
        ]);
        if (exists($data['tags'])) {
           $tags = explode(' ', $data['tags']);
           foreach ($tags as $tag) {
               Tag::query()->create([
                   'title' => $tag,
                   'post_id' => $post->getId()
               ]);
           }
        }
        return successResponse();
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        return successResponse([
            'post' => $post
        ]);
    }

    /**
     * @param PostRequest $request
     * @param Post $post
     * @return JsonResponse
     * @throws CustomException
     */
    public function update(PostRequest $request, Post $post): JsonResponse
    {
        $data = filterData($request->validated());
        if (isset($data['image'])) {
            $data['image'] = handleFile('/posts', $data['image']);
        }
        $post->update([
            'image' => $data['image'] ?? $post->image,
            'title' => $data['title'] ?? $post->title,
            'text' => $data['text'] ?? $post->text
        ]);
        if (isset($data['tags'])) {
            Tag::query()->where('post_id', $post->getId())->delete();
            $tags = explode(' ', $data['tags']);
            foreach ($tags as $tag) {
                Tag::query()->create([
                    'post_id' => $post->getId(),
                    'title' => $tag
                ]);
            }
        }
        return successResponse();
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return successResponse();
    }

    /**
     * @return JsonResponse
     */
    public function allForAdmin(): JsonResponse
    {
        $posts = Post::with(['tags', 'admin'])->get();
        return successResponse([
            'posts' => $posts
        ]);
    }
}
