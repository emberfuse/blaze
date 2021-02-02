<?php

namespace Cratespace\Preflight\Http\Responses;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Contracts\Support\Responsable;
use Cratespace\Citadel\Http\Responses\Response;

class SimpleResponse extends Response implements Responsable
{
    /**
     * Content to be returned by the response.
     *
     * @var \Closure|string|null
     */
    protected $content;

    /**
     * Create a new response factory instance.
     *
     * @param \Illuminate\Contracts\View\Factory $view
     * @param \Illuminate\Routing\Redirector     $redirector
     * @param mixed|null                         $content
     *
     * @return void
     */
    public function __construct(ViewFactory $view, Redirector $redirector, $content = null)
    {
        parent::__construct($view, $redirector);

        $this->content = $content;
    }

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
