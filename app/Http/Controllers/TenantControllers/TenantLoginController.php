<?php

namespace App\Http\Controllers\TenantControllers;

use App\Http\Controllers\Controller;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsEmail;

class TenantLoginController extends Controller
{
    public function tenantlogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->route('tenantdashboard');
        }

        // Authentication failed...
        return redirect()->back()->withInput()->withErrors(['email' => 'Invalid credentials']);
    }

    public function tenantlogout()
    {
        Auth::logout();
        return redirect()->route('tenanthome');
    }

    public function register(Request $request)
{
    $request->validate([
        'username' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'role' => 'required|string|max:255',
    ]);

    // Generate a random password
    $generatedPassword = Str::random(12);

    $user = new User([
        'name' => $request->username,
        'email' => $request->email,
        'role' => $request->role,
        'password' => Hash::make($generatedPassword),
    ]);

    $user->save();

    // Send the credentials to the provided email
    Mail::to($request->email)->send(new UserCredentialsEmail($request->username, $request->email, $generatedPassword));

    return redirect()->route('tenantdashboard')->with('success', 'User added successfully. Credentials sent to the provided email.');
}
}
