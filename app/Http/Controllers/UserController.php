<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct(new User());
    }
    public function homeView()
    {
        return view('user.home', ['title' => 'Home']);
    }

    public function registerUserView()
    {
        return view('user.regist.anggota');
    }

    public function submitView(){
        return view('user.submit');
    }
}