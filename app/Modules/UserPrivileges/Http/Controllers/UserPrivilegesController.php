<?php

namespace App\Modules\UserPrivileges\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserPrivilegesController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("UserPrivileges::welcome");
    }
}
