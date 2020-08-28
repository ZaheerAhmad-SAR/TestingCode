<?php

namespace App;

use Faker\Provider\Uuid;
use http\Env\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Admin\Entities\Study;
use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\Role;
use Modules\UserRoles\Entities\UserRole;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;
    //public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'role_id',
        'password',
        'user_type',
        'created_as_user_role',
        'created_by_id',
        'deleted_at'
    ];
    public $incrementing = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    private $have_role;

   /* public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Uuid::uuid();
            $model->role_id = '8b276df5-1a82-4264-8305-84a960221e85';
            //$role_id =
        });
    }*/


    public function allroles()
    {
        return $this->hasManyThrough(Role::class,UserRole::class,'role_id','id','id','id');
    }
    public function user_roles()
    {
        return $this->hasMany(UserRole::class);
    }
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function studies()
    {
        return $this->belongsToMany(Study::class,'study_user');
    }

    public function hasRole($roles)
    {
        $this->have_role = $this->getUserRole();


        if($this->have_role->name == 'Root') {
            return true;
        }

        if(is_array($roles)){
            foreach($roles as $need_role){
                if($this->checkIfUserHasRole($need_role)) {
                    return true;
                }
            }
        } else{
            return $this->checkIfUserHasRole($roles);
        }
        return false;
    }
    private function getUserRole()
    {
        return $this->role()->getResults();
    }
    private function checkIfUserHasRole($need_role)
    {
        return (strtolower($need_role)==strtolower($this->have_role->name)) ? true : false;
    }
    public function hasAnyRole($roles)
    {
        $this->have_roles = $this->getUserRoles();


        if(is_array($roles)){
            foreach($roles as $need_role){
                if($this->checkIfUserHasAnyRole($need_role)) {
                    return true;
                }
            }
        } else{
            return $this->checkIfUserHasAnyRole($roles);
        }
        return false;
    }
    private function getUserRoles()
    {
        /*return $this->role()->getResults();*/
        return $this->allroles()->getResults();
    }

    private function checkIfUserHasAnyRole($need_role)
    {
        foreach ($this->have_roles as $have_role){

            return (strtolower($need_role)==strtolower($have_role->name)) ? true : false;
        }

    }

    public function hasPermission($roles,$routeName)
    {
        $this->have_role = $this->getUserRole();

        if($this->have_role->name == 'Root') {
            return true;
        }
        foreach ($this->have_role->permissions as $permission_key=>$permission){
            if ($permission->name ==$routeName){
                return [
                    'success'       =>  true,
                    'role'          =>  $this->have_role,
                    'permission'    =>  $permission,
                    'routeName'     =>  $routeName
                ];
            }
        }
        $permission =   Permission::where('name','=',$routeName)->first();
        return [
            'success'       =>  false,
            'role'          =>  $this->have_role,
            'permission'    =>  $permission,
            'routeName'     =>  $routeName
        ];
        return false;
    }


}
