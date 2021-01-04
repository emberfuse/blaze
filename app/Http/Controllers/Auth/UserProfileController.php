<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\DeleteUserJob;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteUserRequest;
use Inertia\Response as InertiaResponse;
use App\Http\Responses\DeleteUserResponse;
use App\Contracts\Auth\UpdatesUserProfiles;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Http\Responses\UpdateProfileResponse;
use App\Http\Requests\UpdateProfileInformationRequest;

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
     * @param \App\Http\Requests\UpdateProfileInformationRequest $request
     * @param \App\Contracts\Auth\UpdatesUserProfiles            $updator
     *
     * @return \App\Http\Responses\Response
     */
    public function update(UpdateProfileInformationRequest $request, UpdatesUserProfiles $updator): Response
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
     * @return \App\Http\Responses\Response
     */
    public function destroy(DeleteUserRequest $request, StatefulGuard $auth): Response
    {
        DeleteUserJob::dispatch($request->user()->fresh());

        $auth->logout();

        return $this->app(DeleteUserResponse::class);
    }
}
