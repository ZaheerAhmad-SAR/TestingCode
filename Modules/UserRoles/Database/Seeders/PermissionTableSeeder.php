<?php

namespace Modules\UserRoles\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Modules\UserRoles\Entities\Permission;
use Illuminate\Support\Str;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $routeCollection = Route::getRoutes();
        foreach ($routeCollection as $keys => $route) {
            if (search_auth($route->action['middleware'], 'auth')) {
                if (!empty($route->getName())) {
                    $permission = Permission::where('name', '=', $route->getName())->first();
                    list($permission_name) = explode('.', $route->getName());
                    if ($permission) {
                        $permission->update(['name' => $route->getName(), 'controller_name' => $permission_name]);
                    } else {
                        Permission::create([
                            'id' => Str::uuid(),
                            'name' => $route->getName(),
                            'for' => $route->getName(),
                            'controller_name' => $permission_name
                        ]);
                    }
                }
            }
        }
    }
}
