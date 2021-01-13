<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;
use App\Http\Requests\API\NewApiTokenRequest;
use App\Http\Requests\API\UpdateApiTokenRequest;

class ApiTokenController extends Controller
{
    /**
     * Show the user API token screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request): InertiaResponse
    {
        return inertia('API/Index', [
            'tokens' => $request->user()->tokens->map(function ($token) {
                return $token->toArray() + [
                    'last_used_ago' => optional($token->last_used_at)->diffForHumans(),
                ];
            }),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
        ]);
    }

    /**
     * Create a new API token.
     *
     * @param  \App\Http\Requests\NewApiTokenRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NewApiTokenRequest $request, Redirector $redirector)
    {
        $token = $request->user()->createToken(
            $request->name,
            Jetstream::validPermissions($request->input('permissions', []))
        );

        return $redirector->back(303)->with('flash', [
            'token' => explode('|', $token->plainTextToken, 2)[1],
        ]);
    }

    /**
     * Update the given API token's permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Routing\Redirector  $redirector
     * @param  string  $tokenId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateApiTokenRequest $request, Redirector $redirector, string $tokenId): RedirectResponse
    {
        $token = $request->user()->tokens()->whereId($tokenId)->firstOrFail();

        $token->forceFill([
            'abilities' => Jetstream::validPermissions($request->input('permissions', [])),
        ])->save();

        return $redirector->back(303);
    }

    /**
     * Delete the given API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Routing\Redirector  $redirector
     * @param  string  $tokenId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Redirector $redirector, string $tokenId): RedirectResponse
    {
        $request->user()->tokens()->whereId($tokenId)->delete();

        return $redirector->back(303);
    }
}
