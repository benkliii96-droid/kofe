<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        return view('account.index', ['user' => auth()->user()]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Пароль неизменен, вы ввели не верный текущий пароль',
            ]);
        }

        $user->password = $request->password;
        $user->save();

        return back()->with('success', 'Пароль успешно изменен');
    }
}
