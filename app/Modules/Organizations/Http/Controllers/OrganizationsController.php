<?php

namespace App\Modules\Organizations\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrganizationsController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Organizations::welcome");
    }
}
