<?php

namespace App\Http\Controllers;

use App\Models\gallery;
use Illuminate\Http\Request;

class welcomeController extends Controller
{
    public function welcome(){
        $gallery = gallery::all();
        return view('welcome',compact(['gallery']));
    }
}
