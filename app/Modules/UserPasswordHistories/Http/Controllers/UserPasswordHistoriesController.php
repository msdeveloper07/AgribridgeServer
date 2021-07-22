<?php

namespace App\Modules\UserPasswordHistories\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserPasswordHistoriesController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("UserPasswordHistories::welcome");
    }
}
