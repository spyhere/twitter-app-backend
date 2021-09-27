<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvatarRequest;
use App\Http\Resources\Profile as ProfileResource;
use App\Http\Resources\ProfileCollection;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Return users profiles
     */
    public function index()
    {
        return new ProfileCollection(
            User::paginate(config('app.users_paginate'))
        );
    }

    /**
     * Return current user's profile
     *
     * @param Request $request
     * @return ProfileResource
     */

    public function profile(Request $request)
    {
        return new ProfileResource($request->user());
    }

    /**
     * Store new avatar
     * @param AvatarRequest $request
     * @return ProfileResource
     */
    public function storeAvatar(AvatarRequest $request)
    {
        $user = $request->user();
        $user->profile->setNewAvatar($request->avatar);
        return new ProfileResource($user);
    }

    /**
     * Delete avatar
     * @param Request $request
     * @return ProfileResource
     */
    public function destroyAvatar(Request $request)
    {
        $user = $request->user();
        if ($user->profile->avatar) {
            $user->profile->destroyAvatar();
        }
        return new ProfileResource($user);
    }
}
