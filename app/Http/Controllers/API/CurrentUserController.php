<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class CurrentUserController extends Controller
{
    /**
     * Get details of currently authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request): Response
    {
        return response()->json($request->user());
    }
}
