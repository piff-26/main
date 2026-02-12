<?php

namespace App\Http\Controllers;

use Faker\Provider\Base;
use Illuminate\Http\Request;
use App\Models\Admin;;
use App\Models\User;

class AdminController extends BaseController
{
    protected $user;
    public function __construct()
    {
        parent::__construct(new Admin());
        $this->user = new User();
    }

    public function loginView()
    {
        return view('admin.login', ['title' => 'Admin Login']);
    }

    public function index()
    {
        return view('admin.dashboard', ['title' => 'Dashboard']);
    }
}
