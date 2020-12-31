<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\DeleteUserJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteUserRequest;
use Inertia\Response as InertiaResponse;
use App\Http\Responses\DeleteUserResponse;
use App\Contracts\Auth\UpdatesUserProfiles;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Http\Responses\UpdateProfileResponse;

class UserProfileController extends Controller
{
    /**
     * Show user profile view.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\ResponseFactory|\Inertia\Response
     */
    public function show(Request $request): InertiaResponse
    {
        return inertia('Profile/Show', ['user' => $request->user()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateUserProfileRequest $request
     * @param \App\Contracts\Auth\UpdatesUserProfiles     $updator
     *
     * @return \App\Http\Responses\UpdateProfileResponse
     */
    public function update(UpdateProfileRequest $request, UpdatesUserProfiles $updator): UpdateProfileResponse
    {
        $updator->update($request->user(), $request->validated());

        return $this->app(UpdateProfileResponse::class);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\DeleteUserRequest     $request
     * @param \App\Auth\Contracts\DeletesUsers         $deletor
     * @param \Illuminate\Contracts\Auth\StatefulGuard $auth
     *
     * @return \App\Http\Responses\DeleteUserResponse
     */
    public function destroy(DeleteUserRequest $request, StatefulGuard $auth): DeleteUserResponse
    {
        DeleteUserJob::dispatch($request->user()->fresh());

        $auth->logout();

        return $this->app(DeleteUserResponse::class);
    }
}
