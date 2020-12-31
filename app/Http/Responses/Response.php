<?php

namespace App\Http\Responses;

use App\Providers\RouteServiceProvider;
use Illuminate\Routing\ResponseFactory;

abstract class Response extends ResponseFactory
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
    public function make($content = '', $status = 200, array $headers = [])
    {
        if (func_num_args() === 0) {
            return $this;
        }

        return parent::make($content, $status, $headers);
    }

    /**
     * Default home URL.
     *
     * @return string
     */
    protected function home(): string
    {
        return url(RouteServiceProvider::HOME);
    }
}
