<?php

namespace Cratespace\Preflight\Http\Controllers\Concerns;

use Cratespace\Preflight\Http\Responses\SimpleResponse;

trait ReturnsResponse
{
    /**
     * Return a new response from the application.
     *
     * @param \Illuminate\Contracts\View\View|string|array|null $content
     * @param int                                               $status
     * @param array                                             $headers
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function response($content = '', ?int $status = 200, ?array $headers = [])
    {
        $response = app(SimpleResponse::class);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response->make($content, $status, $headers);
    }
}
