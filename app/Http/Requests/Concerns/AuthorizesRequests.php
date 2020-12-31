<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Support\Facades\Auth;

trait AuthorizesRequests
{
    /**
     * Determine if the user making the request is a guest.
     *
     * @return bool
     */
    protected function isGuest(): bool
    {
        return is_null($this->user());
    }

    /**
     * Determine if the user making the request is authenticated and is the currently authenticated user.
     *
     * @return bool
     */
    protected function isAuthenticated(): bool
    {
        return ! is_null($this->user()) &&
            $this->user()->is(Auth::user());
    }
}
