<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Http\Requests\Auth\LogoutOtherBrowsersRequest;

class OtherBrowserSessionsController extends Controller
{
    /**
     * Logout from other browser sessions.
     *
     * @param  \App\Http\Requests\Auth\LogoutOtherBrowsersRequest  $request
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @param  \Illuminate\Routing\Redirector  $redirector
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(LogoutOtherBrowsersRequest $request, StatefulGuard $guard, Redirector $redirector): RedirectResponse
    {
        $guard->logoutOtherDevices($request->password);

        $this->deleteOtherSessionRecords($request);

        return $redirector->back(303);
    }

    /**
     * Delete the other browser session records from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function deleteOtherSessionRecords(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->delete();
    }
}
