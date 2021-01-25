<?php

namespace Cratespace\Preflight\Http\Middleware;

use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class ShareInertiaData
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
                return [
                    'flash' => $request->session()->get('flash', []),
                ];
            },

            'user' => function () use ($request) {
                if (! $request->user()) {
                    return;
                }

                return array_merge($request->user()->toArray(), [
                    'two_factor_enabled' => ! is_null($request->user()->two_factor_secret),
                ]);
            },

            'errorBags' => function () {
                return collect(optional(Session::get('errors'))->getBags() ?: [])->mapWithKeys(function ($bag, $key) {
                    return [$key => $bag->messages()];
                })->all();
            },
        ]));

        return $next($request);
    }
}
