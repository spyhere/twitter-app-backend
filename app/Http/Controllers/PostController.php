<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\Post as PostResource;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * PostController constructor. Authorize resource methods
     */

    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostCreateRequest $request
     * @return PostResource
     */

    public function store(PostCreateRequest $request)
    {
        $user = $request->user();
        $post = $user->posts()->create($request->validated());
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostUpdateRequest $request
     * @param Post $post
     * @return PostResource
     */

    public function update(PostUpdateRequest $request, Post $post)
    {
        $post->update($request->validated());
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return Application|ResponseFactory|JsonResponse|Response
     * @throws Exception
     */

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->noContent();
    }

    /**
     * Show posts of a specific user
     *
     * @param User $user
     * @return PostCollection
     */

    public function getUserPosts(User $user)
    {
        return new PostCollection(
            $user->posts()->orderBy('id', 'desc')->paginate(config('app.posts_paginate'))
        );
    }
}
