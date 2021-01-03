<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Validation\ValidationException;

class FailedPasswordResetResponse extends Response implements Responsable
{
    /**
     * The response status language key.
     *
     * @var string
     */
    protected $status;

    /**
     * Create a new response instance.
     *
     * @param string $status
     *
     * @return void
     */
    public function __construct(string $status)
    {
        parent::__construct(app('view'), app('redirect'));

        $this->status = $status;
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
        if ($request->wantsJson()) {
            throw ValidationException::withMessages(['email' => [trans($this->status)]]);
        }

        return $this->redirector()->back(303)
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($this->status)]);
    }
}
