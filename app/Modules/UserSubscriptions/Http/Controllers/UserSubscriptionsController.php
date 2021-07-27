<?php

namespace App\Modules\UserSubscriptions\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserSubscriptionsController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("UserSubscriptions::welcome");
    }
}
