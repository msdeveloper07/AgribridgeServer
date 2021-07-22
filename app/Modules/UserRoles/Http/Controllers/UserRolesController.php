<?php

namespace App\Modules\UserRoles\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserRolesController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("UserRoles::welcome");
    }
}
