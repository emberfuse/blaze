<?php

namespace Cratespace\Preflight\Http\Controllers\Concerns;

use Cratespace\Preflight\Http\Responses\SimpleResponse;
use Cratespace\Sentinel\Http\Controllers\Concerns\InteractsWithContainer;

trait ReturnsResponse
{
    use InteractsWithContainer;

    /**
     * Return a new response from the application.
     *
     * @param \Illuminate\Contracts\View\View|string|array|null $content
     * @param int                                               $status
     * @param array                                             $headers
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function response($content = null)
    {
        if (is_null($content)) {
            return $this->resolve(SimpleResponse::class);
        }

        return SimpleResponse::dispatch($content);
    }
}
