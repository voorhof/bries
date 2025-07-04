<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Show the welcome page.
     */
    public function welcome(): View
    {
        return view('welcome');
    }

    /**
     * Show the dashboard page.
     */
    public function dashboard(): View
    {
        return view('dashboard');
    }

    /**
     * Show the cheatsheet page.
     */
    public function cheatsheet(): View
    {
        return view('cheatsheet');
    }
}
