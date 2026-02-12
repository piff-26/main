<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{
    protected $admin;

    public function __construct()
    {
        $this->admin = new Admin();
    }
    public function googleAuth()
    {
        return Socialite::driver('google')
            ->redirectUrl(route('admin.auth.google.callback'))
            ->redirect();
    }

     public function processLogin()
    {
        $user = Socialite::driver('google')
            ->redirectUrl(route('admin.auth.google.callback'))
            ->stateless()
            ->user();
        if ($user) {
            $email = strtolower($user->getEmail());
            if (strpos($email, '@john.petra.ac.id') === false) {
                return redirect()->route('admin.login')->with('error', 'Mohon gunakan email Petra dengan @john.petra.ac.id');
            }

            $nrp = strtolower(explode('@', $email)[0]);
            $name = $user->getName();

            $admin = $this->admin->where('nrp', $nrp)->first();

            if ($admin) {
                session()->put('role', 'admin');
                session()->put('email', $email);
                session()->put('nrp', $nrp);
                session()->put('name', $name);
                session()->put('division_id', $admin->division_id);
                session()->put('division_name', $admin->division->name);
                session()->put('division_slug', $admin->division->slug);
                return redirect()->route('admin.dashboard')->with('success', 'Login success!');
            } else {
                return redirect()->route('admin.login')->with('error', 'You are not registered as PIFF 2026 admin!');
            }
        }
    }

    public function logout()
    {
        if (session()->has('role')) {
            session()->flush();
            return redirect()->route('admin.login')->with('success', 'Logout success!');
        }
        session()->flush();
        return redirect()->route('user.home')->with('success', 'Logout success!');
    }
    
    // redirect dan callback Untuk register email
    public function redirect()
    {
        return Socialite::driver('google')
            ->redirectUrl(route('google.callback.register')) 
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            
            $email = strtolower($user->getEmail());
            if (strpos($email, '@john.petra.ac.id') === false) {
                return redirect()->route('user.home')->with('error', 'Mohon gunakan email Petra dengan @john.petra.ac.id');
            }

            session()->put('register_email', $email);
            session()->put('register_name', $user->getName());
            
            return redirect()->route('user.register')->with('success', 'Email verified successfully!');
        } catch (\Exception $e) {
            Log::error('Google callback error: ' . $e->getMessage());
            return redirect()->route('user.home')->with('error', 'Authentication failed!');
        }
    }
}
