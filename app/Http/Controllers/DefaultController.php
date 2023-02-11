<?php

namespace App\Http\Controllers;

class DefaultController
{
    public function __invoke()
    {
        return view('layout');
    }
}
