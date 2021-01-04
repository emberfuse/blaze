<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class UpdatePasswordResponse extends Response implements Responsable
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
        $request->wantsJson()
            ? $this->json('', 200)
            : $this->back()->with('status', 'password-updated');
    }
}
