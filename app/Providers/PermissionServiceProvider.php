<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            Gate::define($permissions->slug, function ($user) use($permission){
                return $user->hasPermission($permission->slug);
            });
        }

    }
}