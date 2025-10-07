<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Models\Menu;

class MenuServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Compose menus for sidebar
        View::composer('backend.layouts.sidebar', function ($view) {
            $user = auth()->user();
            $menus = collect();
            if ($user) {
                if ($user->hasRole('Super Admin (Wizards)')) {
                    $menus = Menu::with(['children' => function ($query) {
                        $query->active()->orderBy('order');
                    }])
                    ->parent()
                    ->active()
                    ->orderBy('order')
                    ->get();
                } else {
                    $menus = Menu::with(['children' => function ($query) use ($user) {
                        $query->active()
                              ->where(function ($q) use ($user) {
                                  $this->applyAccessFilter($q, $user);
                              })
                              ->orderBy('order');
                    }])
                    ->parent()
                    ->active()
                    ->where(function ($query) use ($user) {
                        $this->applyAccessFilter($query, $user);
                    })
                    ->orderBy('order')
                    ->get();
                }
            }
            $menus->each(function ($menu) {
                $this->processMenuUrl($menu);
                $menu->children->each(fn($child) => $this->processMenuUrl($child));
            });

            $view->with('menus', $menus);
        });
    }

    /**
     * Convert menu url or route name to full URL.
     */
    private function processMenuUrl($menuItem)
    {
        if (!$menuItem->url) {
            $menuItem->resolved_url = '#';
            return;
        }

        if (Route::has($menuItem->url)) {
            $menuItem->resolved_url = route($menuItem->url);
        } elseif (str_starts_with($menuItem->url, '/') || filter_var($menuItem->url, FILTER_VALIDATE_URL)) {
            $menuItem->resolved_url = url($menuItem->url);
        } else {
            $menuItem->resolved_url = url('/' . ltrim($menuItem->url, '/'));
        }
    }

    /**
     * Filter menus based on user roles and permissions.
     */
    private function applyAccessFilter($query, $user)
    {
        $query->where(function ($q) use ($user) {
            // Public menus (no role or permission)
            $q->doesntHave('roles')->doesntHave('permissions');

            // Menus assigned to user's roles
            if ($user->roles->count()) {
                $roleIds = $user->roles->pluck('id');
                $q->orWhereHas('roles', function ($roleQuery) use ($roleIds) {
                    $roleQuery->whereIn('roles.id', $roleIds); // specify table name
                });
            }

            // Menus assigned to user's permissions
            $userPermissionIds = $user->getAllPermissions()->pluck('id');
            if ($userPermissionIds->count()) {
                $q->orWhereHas('permissions', function ($permQuery) use ($userPermissionIds) {
                    $permQuery->whereIn('permissions.id', $userPermissionIds); // specify table name
                });
            }
        });
    }

}
