<?php

namespace App\Modules\Applications\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationsController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Applications::welcome");
    }
}
