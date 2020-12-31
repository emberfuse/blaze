<?php

namespace App\Http\Responses;

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
        return $request->expectsJson()
            ? $this->make()->json(['two_factor' => false])
            : $this->make()->redirect()->intended($this->home());
    }
}
