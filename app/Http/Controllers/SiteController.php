<?php

namespace App\Http\Controllers;

class SiteController extends Controller
{
    public function landing()
    {
        return view('site.landing');
    }

    public function loanCalculator()
    {
        return view('site.loan-calculator');
    }

}
