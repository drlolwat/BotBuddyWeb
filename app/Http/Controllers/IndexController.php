<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index(): RedirectResponse
    {
        return redirect(route('login'));
    }
}
