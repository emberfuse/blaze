<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserProfileController extends Controller
{
    /**
     * Show user profile view.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\ResponseFactory|\Inertia\Response
     */
    public function __invoke(Request $request)
    {
        return inertia('Profile/Show', ['user' => $request->user()]);
    }
}
