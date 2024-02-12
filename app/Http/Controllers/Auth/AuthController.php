<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function action_login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];
        if (Auth::attempt($credentials, $request->remember)) {
            return redirect()->route('dashboard.index');
        } else {
            return redirect()->route('login')->with('failed', 'Wrong Email or Password');
        }
    }

    public function forgot_password()
    {
        return view('auth.forgot-password');
    }

    public function action_forgot_password(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required',
        ]);
        $user = User::where('username', $request->username)->where('email', $request->email)->first();
        if ($user) {
            Mail::send('emails.reset-password', ['id' => $user->uuid], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Join Invitation Mail');
            });
            return redirect()->route('login')->with('success', 'Request has been send in ' . $request->email . '.');
        } else {
            $errors = [
                'failed' => 'Email and Username doesn`t exist.',
            ];
            return redirect()->route('forgotpassword')->withErrors($errors)->withInput();
        }
    }

    public function register()
    {
        return view('auth.register');
    }
    public function action_register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $id = Uuid::uuid4()->toString();
        $data = [
            'id' => $id,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];
        try {
            User::create($data);
            Mail::send('emails.email-verification', ['id' => $id], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Email Verification');
            });
            return redirect()->route('login')->with('success', 'User registered Successfully');
        } catch (\Throwable $th) {
            $errors = [
                'failed' => 'Something happen on server wait until administrator repair it.',
            ];
            return redirect()->route('login')->withErrors($errors)->withInput();
        }
    }

    public function action_logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Anda berhasil logout');
    }
}
