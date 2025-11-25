<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function landing()
    {
        return view('landing', [
            'heroImage' => asset('uploads/mbg-hero.jpg'), // tinggal ganti file
        ]);
    }
}
