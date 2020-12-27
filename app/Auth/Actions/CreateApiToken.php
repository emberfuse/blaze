<?php

namespace App\Auth\Actions;

use App\Auth\Api\Permission;
use Illuminate\Http\Request;
use App\Contracts\Auth\CreatesApiTokens;

class CreateApiToken implements CreatesApiTokens
{
    /**
     * Create new personal access token for authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function create(Request $request)
    {
        return $request->user()->createToken(
            $request->name, Permission::validPermissions(
                $request->input('permissions', [])
            )
        );
    }
}
