<?php

namespace App\Modules\Screens\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScreensController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Screens::welcome");
    }
}
