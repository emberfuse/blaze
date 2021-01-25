<?php

namespace Cratespace\Preflight\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\StatefulGuard;

class OtherBrowserSessionsController extends Controller
{
    /**
     * Logout from other browser sessions.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Contracts\Auth\StatefulGuard $guard
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, StatefulGuard $guard)
    {
        $request->validate([
            'password' => 'password',
        ]);

        $guard->logoutOtherDevices($request->password);

        $this->deleteOtherSessionRecords($request);

        return back(303);
    }

    /**
     * Delete the other browser session records from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function deleteOtherSessionRecords(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->delete();
    }
}
