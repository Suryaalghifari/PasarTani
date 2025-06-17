<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // AMBIL user dari session, BUKAN $request->user()
        $user = $request->session()->get('user');
    
        if (!$user || !in_array($user['peran'], $roles)) {
            abort(403, 'Unauthorized');
        }
    
        return $next($request);
    }
    
}
