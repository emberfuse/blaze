<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class PasswordResetResponse extends Response implements Responsable
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
        return $request->wantsJson()
            ? $this->make()->json(['message' => trans($this->status)], 200)
            : $this->redirectToRoute('login', [], 303)->with('status', trans($this->status));
    }
}
