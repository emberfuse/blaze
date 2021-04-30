<?php

namespace Cratespace\Preflight\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Cratespace\Preflight\API\Permission;
use Inertia\Response as InertiaResponse;
use Cratespace\Preflight\Http\Requests\CreateApiTokenRequest;
use Cratespace\Preflight\Http\Requests\DeleteApiTokenRequest;
use Cratespace\Preflight\Http\Requests\UpdateApiTokenRequest;

class ApiTokenController extends Controller
{
    /**
     * Show the user API token screen.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\Response
     */
    public function index(Request $request): InertiaResponse
    {
        return Inertia::render('API/Index', [
            'tokens' => $request->user()->tokens->map(function ($token) {
                return $token->toArray() + [
                    'last_used_ago' => optional($token->last_used_at)->diffForHumans(),
                ];
            }),
            'availablePermissions' => Permission::$permissions,
            'defaultPermissions' => Permission::$defaultPermissions,
        ]);
    }

    /**
     * Create a new API token.
     *
     * @param \Cratespace\Preflight\Http\Requests\CreateApiTokenRequest $request
     *
     * @return mixed
     */
    public function store(CreateApiTokenRequest $request)
    {
        $token = $request->user()->createToken(
            $request->name,
            Permission::validPermissions($request->input('permissions', []))
        );

        return $this->response()->back(303)->with('flash', [
            'token' => explode('|', $token->plainTextToken, 2)[1],
        ]);
    }

    /**
     * Update the given API token's permissions.
     *
     * @param \Cratespace\Preflight\Http\Requests\UpdateApiTokenRequest $request
     * @param string                                                    $tokenId
     *
     * @return mixed
     */
    public function update(UpdateApiTokenRequest $request, string $tokenId)
    {
        $token = $request->user()->tokens()->where('id', $tokenId)->firstOrFail();

        $token->forceFill([
            'abilities' => Permission::validPermissions($request->input('permissions', [])),
        ])->save();

        return $this->response()->back(303);
    }

    /**
     * Delete the given API token.
     *
     * @param \Cratespace\Preflight\Http\Requests\DeleteApiTokenRequest $request
     * @param string                                                    $tokenId
     *
     * @return mixed
     */
    public function destroy(DeleteApiTokenRequest $request, string $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return $this->response()->back(303);
    }
}
