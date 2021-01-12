<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Auth\Actions\GenerateNewRecoveryCodes;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Responses\Auth\GenerateRecoveryCodesResponse;

class RecoveryCodeController extends Controller
{
    /**
     * Get the two factor authentication recovery codes for authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ResponseFactory $response): JsonResponse
    {
        if (! $request->user()->two_factor_secret ||
            ! $request->user()->two_factor_recovery_codes) {
            return $response->json([], 200);
        }

        return $response->json(json_decode(decrypt(
            $request->user()->two_factor_recovery_codes
        ), true));
    }

    /**
     * Generate a fresh set of two factor authentication recovery codes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Auth\Actions\GenerateNewRecoveryCodes  $generate
     * @return \App\Http\Responses\Auth\GenerateRecoveryCodesResponse
     */
    public function store(Request $request, GenerateNewRecoveryCodes $generate): GenerateRecoveryCodesResponse
    {
        $generate($request->user());

        return $this->app(GenerateRecoveryCodesResponse::class);
    }
}
