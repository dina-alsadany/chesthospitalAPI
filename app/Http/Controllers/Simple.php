<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Simple extends Controller
{
    public function index(){
      $fakeData = [
        'id'=>1,
        'name'=>'test deploy',
        'email'=>'dina@al.com',
      ];
      return response()->json($fakeData);
    }
}
