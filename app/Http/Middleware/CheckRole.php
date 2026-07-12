<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu');
        }

        /** @var User $user */
        $user = auth()->user();

        $allowed = in_array('admin', $roles) && $user->isAdmin()
            || in_array('department', $roles) && $user->isDepartmentOfficer()
            || in_array('licensing', $roles) && $user->isLicensingOfficer();

        if (!$allowed) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
