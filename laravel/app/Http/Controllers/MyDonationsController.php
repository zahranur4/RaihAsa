<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MyDonationsController extends Controller
{
    public function index()
    {
        // If user is authenticated, show donasiku (user dashboard)
        if (Auth::check()) {
            return app(DonasikuController::class)->index();
        }

        // If user is not authenticated, show my-donations (public info page)
        return view('my-donations.index');
    }
}
