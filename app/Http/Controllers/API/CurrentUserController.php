<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;

class CurrentUserController extends Controller
{
    /**
     * Get all information regarding currently authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ResponseFactory $response): JsonResponse
    {
        return $response->json($request->user(), 200);
    }
}
