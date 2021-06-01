<?php

namespace Emberfuse\Blaze\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Emberfuse\Blaze\API\Permission;
use Inertia\Response as InertiaResponse;
use Emberfuse\Blaze\Contracts\Actions\UpdatesApiTokens;
use Emberfuse\Blaze\Contracts\Actions\CreatesNewApiTokens;
use Emberfuse\Blaze\Http\Requests\CreateApiTokenRequest;
use Emberfuse\Blaze\Http\Requests\DeleteApiTokenRequest;
use Emberfuse\Blaze\Http\Requests\UpdateApiTokenRequest;

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
     * @param \Emberfuse\Blaze\Http\Requests\CreateApiTokenRequest $request
     * @param \Emberfuse\Blaze\Contracts\API\CreatesNewApiTokens   $creator
     *
     * @return mixed
     */
    public function store(CreateApiTokenRequest $request, CreatesNewApiTokens $creator)
    {
        $token = $creator->create($request->validated(), ['user' => $request->user()]);

        return $this->response()
            ->back(303)
            ->with('flash', [
                'token' => explode('|', $token->plainTextToken, 2)[1],
            ]);
    }

    /**
     * Update the given API token's permissions.
     *
     * @param \Emberfuse\Blaze\Http\Requests\UpdateApiTokenRequest $request
     * @param \Emberfuse\Blaze\Contracts\API\UpdatesApiTokens      $updater
     * @param string                                                    $tokenId
     *
     * @return mixed
     */
    public function update(
        UpdateApiTokenRequest $request,
        UpdatesApiTokens $updater,
        string $tokenId
    ) {
        $updater->update($request->user(), $request->merge([
            'token_id' => $tokenId,
        ])->all());

        return $this->response()->back(303);
    }

    /**
     * Delete the given API token.
     *
     * @param \Emberfuse\Blaze\Http\Requests\DeleteApiTokenRequest $request
     * @param string                                                    $tokenId
     *
     * @return mixed
     */
    public function destroy(DeleteApiTokenRequest $request, string $tokenId)
    {
        $request->user()
            ->tokens()
            ->where('id', $tokenId)
            ->first()
            ->delete();

        return $this->response()->back(303);
    }
}
