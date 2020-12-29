<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Responsable;

class LoginResponse extends Response implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $token = $this->createInternalAccessToken($request);

        return $request->wantsJson()
            ? response()->json(['tfa' => false, 'token' => $token])
            : redirect()->intended(config('auth.defaults.home'));
    }

    /**
     * Create temporary API token for authentiated user for internal access purposses.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function createInternalAccessToken(Request $request): string
    {
        $token = $request->user()
            ->createToken($request->userAgent())
            ->plainTextToken;

        return explode('|', $token, 2)[1];
    }
}
