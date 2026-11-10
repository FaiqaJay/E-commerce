<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->utype === 'ADM') {
            return $next($request);
        }

        // Logged in but not an admin
        return redirect()->route('user.index')->with('error', 'You are not allowed to access the admin area.');
    }
}