<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify');
    }

    public function verify(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect('/login')->with('error', 'User not found');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('error', 'Email already verified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('dashboard')->with('success', 'Email verified successfully');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('error', 'Email already verified');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }

    public function resetPassword(Request $request)
    {
        return view('auth.recover-password', [
            'id' => $request->id
        ]);
    }

    public function resetPasswordAction(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        try {
            $user = User::find($id);
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('login')->with('success', 'Password changed Successfully');
        } catch (\Throwable $th) {
            $errors = [
                'failed' => 'Something happen on server wait until administrator repair it.',
            ];
            return redirect()->route('login')->withErrors($errors)->withInput();
        }
    }
}
