<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('orders', function ($user) {
            $allowedRoles = ['admin', 'sales_manager', 'warehouse_manager', 'logistics_manager'];
            return in_array($user->role, $allowedRoles);
        });
        
        Gate::define('create_orders', function ($user) {
            return $user->isRole('admin');
        });

        Gate::define('products', function ($user) {
            $allowedRoles = ['admin', 'warehouse_manager'];
            return in_array($user->role, $allowedRoles);
        });

        Gate::define('view-pending-orders', function ($user) {
            $allowedRoles = ['sales_manager', 'warehouse_manager'];
            return in_array($user->role, $allowedRoles);
        });

        Gate::define('approve-orders', function ($user) {
            $allowedRoles = ['warehouse_manager'];
            return in_array($user->role, $allowedRoles);
        });

        Gate::define('ship-orders', function ($user) {
            return $user->isRole('logistics_manager');
        }); 


         
    }
}
