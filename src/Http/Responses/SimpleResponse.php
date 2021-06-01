<?php

namespace Emberfuse\Blaze\Http\Responses;

use Closure;
use Illuminate\Contracts\Support\Responsable;
use Emberfuse\Scorch\Http\Responses\Response;

class SimpleResponse extends Response implements Responsable
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
        if (is_string($this->content)) {
            $response = $request->wantsJson()
                ? $this->json($this->content)
                : $this->make($this->content);
        } elseif ($this->content instanceof Closure) {
            $response = call_user_func($this->content, $request);
        } else {
            $response = $this->make();
        }

        if ($response instanceof Responsable) {
            return $response->toResponse($request);
        }

        return $response;
    }
}
