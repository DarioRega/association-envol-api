<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        return Product::all();
    }

    //TODO ADD METHOD TO GET ONLY THE MAIN FOR THE FRONT
}
