<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Contracts\Auth\UpdatesUserPasswords;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Responses\UpdatePasswordResponse;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param \App\Http\Requests\UpdatePasswordRequest $request
     * @param \App\Contracts\Auth\UpdatesUserPasswords $updater
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePasswordRequest $request, UpdatesUserPasswords $updater)
    {
        $updater->update($request->user(), $request->validated());

        return $this->app(UpdatePasswordResponse::class);
    }
}
