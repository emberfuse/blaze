<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;

class TwoFactorQrCodeController extends Controller
{
    /**
     * Get the SVG element for the user's two factor authentication QR code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, ResponseFactory $response): JsonResponse
    {
        return $response->json(['svg' => $request->user()->twoFactorQrCodeSvg()]);
    }
}
