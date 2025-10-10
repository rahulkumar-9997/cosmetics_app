<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Menu;

class CheckMenuAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $currentRoute = $request->route()->getName();
        //dd($currentRoute);
        if ($user->hasRole('Super Admin (Wizards)')) {
            return $next($request);
        }
        $assignedMenus = Menu::whereHas('roles', function ($q) use ($user) {
            $q->whereIn('roles.id', $user->roles->pluck('id')->toArray());
        })
            ->orWhereHas('permissions', function ($q) use ($user) {
                $q->whereIn('permissions.id', $user->getAllPermissions()->pluck('id')->toArray());
            })
            ->get();

        if ($assignedMenus->isEmpty()) {
            abort(403, 'Unauthorized access. No menu or permission assigned.');
        }
        $menu = Menu::where('url', $currentRoute)->first();

        if (!$menu) {
            abort(403, 'Unauthorized access. Route not assigned to any menu.');
        }
        $hasAccess = $menu->roles()
            ->whereIn('roles.id', $user->roles->pluck('id')->toArray())
            ->exists()
            ||
            $menu->permissions()
            ->whereIn('permissions.id', $user->getAllPermissions()->pluck('id')->toArray())
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access. You do not have permission to access this menu.');
        }

        return $next($request);
    }
}
