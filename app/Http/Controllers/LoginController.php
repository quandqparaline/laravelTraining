<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('auth.index');
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function login(LoginRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {

        $credentials = $request->only('email','password');var_dump($credentials); die;
        if (Auth::attempt($credentials)){

            return redirect()->intended('teams/searchTeam')->with('success','Logged in!');
        }

        return redirect('auth')->with('success', 'Your credentials might be incorrect!');
    }

    public function customRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        return redirect("teams/searchTeam")->with('success', 'You have signed-in');
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function logOut() {
        Session::flush();
        Auth::logout();

        return Redirect('auth/index');
    }
}
