<?php

namespace App\Modules\Security\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Security::welcome");
    }
}
