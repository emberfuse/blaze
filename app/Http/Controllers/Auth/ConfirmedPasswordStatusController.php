<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfirmedPasswordStatusController extends Controller
{
    /**
     * Get the password confirmation status.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            'confirmed' => (time() - $request->session()->get('auth.password_confirmed_at', 0)) < $request->input('seconds', config('auth.password_timeout', 900)),
        ]);
    }
}
