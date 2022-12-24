<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthEditor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('loggedEditor') && $request->path() != '/login') {
            return redirect('/login')->with('fail', 'anda bukan Editor');
        }

        if (session()->has('loggedEditor') && $request->path() == '/login') {
            return redirect('/')->with('logged', 'anda sudah login');
        }
        return $next($request);
    }
}
