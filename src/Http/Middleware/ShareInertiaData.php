<?php

namespace Cratespace\Preflight\Http\Middleware;

use Inertia\Inertia;
use Inertia\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class ShareInertiaData extends Middleware
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param callable                 $next
     *
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        Inertia::share(array_filter([
            'preflight' => function () use ($request) {
                return ['flash' => $request->session()->get('flash', [])];
            },

            'user' => function () use ($request) {
                if (! $request->user()) {
                    return;
                }

                return $request->user()->toArray();
            },

            'errorBags' => function () {
                return collect(optional(Session::get('errors'))->getBags() ?: [])
                    ->mapWithKeys(fn ($bag, $key) => [$key => $bag->messages()])
                    ->all();
            },

            'currentRouteName' => Route::currentRouteName(),
        ]));

        return $next($request);
    }
}
