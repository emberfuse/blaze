<?php

namespace App\Http\Controllers\Business;

use Inertia\Response;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show home view.
     *
     * @return \Inertia\Response
     */
    public function __invoke(): Response
    {
        return inertia('Business/Home');
    }
}
