<?php

namespace Cratespace\Preflight\Http\Controllers\Concerns;

use Illuminate\Routing\ResponseFactory;

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
    protected function response($content = '', $status = 200, array $headers = [])
    {
        $response = app(SimpleResponse::class);
    }
}
