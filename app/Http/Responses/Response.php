<?php

namespace App\Http\Responses;

use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
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
     * Get instance of route redirector.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function redirector(): Redirector
    {
        return $this->redirector;
    }

    /**
     * Create a new redirect response to the previous location.
     *
     * @param int $status
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function back(int $status = 302): RedirectResponse
    {
        return $this->redirector()->back($status);
    }

    /**
     * Default home URL.
     *
     * @return string
     */
    public function home(): string
    {
        return url(RouteServiceProvider::HOME);
    }

    /**
     * Create a new redirect response to the "home" route.
     *
     * @param int $status
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toHome(int $status = 302): RedirectResponse
    {
        return $this->redirector()->home($status);
    }
}
