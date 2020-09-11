<?php

namespace Modules\UserRoles\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
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
                    $get_info = explode('.',$route->getName());
                    $current_method_name = $get_info;
                    $permission = Permission::where('name', '=', $route->getName())->first();
                    if ($permission) {
                        if (isset($current_method_name[1])){
                            $permission->update(['name' => $route->getName(),'method_name' => $current_method_name[1]]);
                        }else{
                            $permission->update(['name' => $route->getName(),'method_name' => Null]);
                        }
                    } else {
                        if (isset($current_method_name[1])){
                            Permission::create([
                                'id'    => Str::uuid(),
                                'name' => $route->getName(),
                                'for' => $route->getName(),
                                'method_name' => $current_method_name[1],
                            ]);
                        }
                        else{
                            Permission::create([
                                'id'    => Str::uuid(),
                                'name' => $route->getName(),
                                'for' => $route->getName(),
                            ]);
                        }
                    }
                }
            }
        }
    }
}
