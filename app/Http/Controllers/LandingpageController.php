<?php

namespace App\Http\Controllers;

use App\Mail\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LandingpageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('landingpage');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact(Request $request)
    {
        $this->validate($request, [
            'contactName' => 'required',
            'contactEmail' => 'required',
            'contactSubject' => 'required',
            'contactMessage' => 'required',
            'g-recaptcha-response' => 'required|recaptcha',
        ]);

        Mail::to('lee.peuker@protonmail.com')->send(new Contact($request->all()));
        return 'OK';
    }
}
