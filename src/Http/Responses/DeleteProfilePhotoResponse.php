<?php

namespace Cratespace\Preflight\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Cratespace\Citadel\Http\Responses\Response;

class DeleteProfilePhotoResponse extends Response implements Responsable
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
        return $request->expectsJson() ? $this->json('', 204) : $this->back(303);
    }
}
