<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\DeleteProfilePhotoRequest;
use App\Http\Responses\Auth\DeleteProfilePhotoResponse;

class UserProfilePhotoController extends Controller
{
    /**
     * Delete the current user's profile photo.
     *
     * @param \App\Http\App\Http\Requests\DeleteProfilePhotoRequest $request
     *
     * @return \App\Http\Responses\DeleteProfilePhotoResponse
     */
    public function destroy(DeleteProfilePhotoRequest $request): DeleteProfilePhotoResponse
    {
        $request->user()->deleteProfilePhoto();

        return $this->app(DeleteProfilePhotoResponse::class);
    }
}
