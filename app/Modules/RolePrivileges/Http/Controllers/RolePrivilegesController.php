<?php

namespace App\Modules\RolePrivileges\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolePrivilegesController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("RolePrivileges::welcome");
    }
}
