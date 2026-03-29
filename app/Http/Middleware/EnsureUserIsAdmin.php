<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->is_admin) {
            return Redirect::route('students.index')->with('error', 'Akses ditolak. Hanya admin yang dapat mengelola pengguna.');
        }

        return $next($request);
    }
}
