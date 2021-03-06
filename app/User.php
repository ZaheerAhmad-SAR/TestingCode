<?php

namespace App;

use Faker\Provider\Uuid;
use http\Env\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Admin\Entities\Study;
use Modules\Admin\Entities\TrailLog;
use Modules\Admin\Entities\TransmissionUpdateDetail;
use Modules\CertificationApp\Entities\CertificationData;

use Modules\UserRoles\Entities\Permission;
use Modules\UserRoles\Entities\Role;
use Modules\Admin\Entities\RoleStudyUser;
use Modules\UserRoles\Entities\UserRole;
use Modules\UserRoles\Entities\UserSystemInfo;
use Modules\UserRoles\Entities\UserLog;
use App\UserPrefrences;
use Modules\CertificationApp\Entities\TransmissionDataDevice;
use Modules\CertificationApp\Entities\TransmissionDataPhotographer;
class User extends Authenticatable
{
    use Notifiable;
    use softDeletes;

    //public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected  $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];
    protected $fillable = [
        'id',
        'title',
        'phone',
        'profile_image',
        'name',
        'email',
        'role_id',
        'password',
        'google_auth',
        'notification_type',
        'bug_report',
        'is_form',
        'is_subject',
        'qr_flag',
        'browser_name',
        'two_factor_code',
        'two_factor_expires_at',
        'created_by',
        'deleted_at'
    ];
    public $incrementing = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token', 'google2fa_secret',
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

    public function allroles()
    {
        return $this->hasManyThrough(Role::class, UserRole::class, 'role_id', 'id', 'id', 'id');
    }
    public function user_roles()
    {
        return $this->hasMany(UserRole::class);
    }

    public static function getUserRolesString($user)
    {
        $roleNamesArray = [];
        foreach ($user->user_roles as $userRole) {
            $role = Role::find($userRole->role_id);
            $roleNamesArray[] = ucfirst($role->name);
        }
        return implode(', ', $roleNamesArray);
    }

    public static function getUserRolesInStudyString($user)
    {
        $userRoleIds = RoleStudyUser::where('study_id', 'like', session('current_study'))
            ->where('user_id', $user->id)->pluck('role_id')->toArray();

        $roleNamesArray = [];
        foreach ($userRoleIds as $roleId) {
            $role = Role::find($roleId);
            $roleNamesArray[] = ucfirst($role->name);
        }
        return implode(', ', $roleNamesArray);
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function studies()
    {
        return $this->belongsToMany(Study::class, 'study_user');
    }

    public function hasRole($roles)
    {
        $this->have_role = $this->getUserRole();


        if ($this->have_role->name == 'Root') {
            return true;
        }

        if (is_array($roles)) {
            foreach ($roles as $need_role) {
                if ($this->checkIfUserHasRole($need_role)) {
                    return true;
                }
            }
        } else {
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
        return (strtolower($need_role) == strtolower($this->have_role->name)) ? true : false;
    }
    public function hasAnyRole($roles)
    {
        $this->have_roles = $this->getUserRoles();


        if (is_array($roles)) {
            foreach ($roles as $need_role) {
                if ($this->checkIfUserHasAnyRole($need_role)) {
                    return true;
                }
            }
        } else {
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
        foreach ($this->have_roles as $have_role) {

            return (strtolower($need_role) == strtolower($have_role->name)) ? true : false;
        }
    }

    public function hasPermission($roles, $routeName)
    {
        $this->have_role = $this->getUserRole();

        if ($this->have_role->name == 'Root') {
            return true;
        }
        foreach ($this->have_role->permissions as $permission_key => $permission) {
            if ($permission->name == $routeName) {
                return [
                    'success'       =>  true,
                    'role'          =>  $this->have_role,
                    'permission'    =>  $permission,
                    'routeName'     =>  $routeName
                ];
            }
        }
        $permission =   Permission::where('name', '=', $routeName)->first();
        return [
            'success'       =>  false,
            'role'          =>  $this->have_role,
            'permission'    =>  $permission,
            'routeName'     =>  $routeName
        ];
        return false;
    }

    public function logs()
    {
        //return $this->hasOne(User::class, 'user_id', 'id');
        //return $this->hasOne(User::class);
        return $this->hasMany(TrailLog::class);
    }
    public function getImageAttribute()
    {
        return $this->profile_image;
    }

    public function generateTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }


    public function systemInfo()
    {
        return $this->hasMany(UserSystemInfo::class);
    }


    public function getProfileImage()
    {
        if (!empty($this->profile_image)) {
            return '<img src="' . url('images/' . $this->profile_image) . '"/>';
        }
    }

    public function transmissionDetails()
    {
        return $this->hasMany(TransmissionUpdateDetail::class);
    }

    public function certificationOfficer()
    {
        return $this->hasMany(CertificationData::class);
    }

    public function user_log()
    {
        return $this->hasMany(UserLog::class);
    }

    public static function getUserStatus($userId) {
        return self::where('id', $userId)
                    ->whereNull('deleted_at')
                    ->first();
    }

    public function user_prefrences()
    {
        return $this->hasOne(UserPrefrences::class)->withDefault();
    }

    public function transmissionDevices()
    {
        return $this->hasMany(TransmissionDataDevice::class, 'assign_to');
    }

    public function transmissionPhotographer()
    {
        return $this->hasMany(TransmissionDataPhotographer::class, 'assign_to');
    }
}
