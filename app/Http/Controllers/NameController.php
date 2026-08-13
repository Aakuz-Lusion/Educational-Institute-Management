<?php

namespace App\Http\Controllers;

use App\Models\Name;
use Illuminate\Http\Request;

class NameController extends Controller
{
    public function index()
    {
        $allNames = Name::all();
        return view('welcome', ['names' => $allNames]);
    }

}