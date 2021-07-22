<?php

namespace App\Modules\Locations\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationsController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Locations::welcome");
    }
}
