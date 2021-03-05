<?php

namespace App;

use App\Models\Module;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\RoleModule;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    // use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'blacklist',
        'user_type',
        'ou_fk',
        'idrm',
        'is_active',
    ];

    /**
     * Create a new account login for user.
     *
     * @param  array, string
     * @return Response
     */
    public function addUser(array $user)
    {
        $user['password'] = bcrypt($user['password']);
        $user['confirmation_token'] = null;
        $user['active'] = true;
        return $this->create($user);

    }

    /**
     * Assign role and user relations
     *
     * @param  string
     * @return Response
     */
    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    /**
     * Check role user
     *
     * @param  string
     * @return bool
     */
    public function hasRole($role)
    {
         if (is_string($role)) {
             return $this->roles->contains('name', $role);
         }

         foreach ($role as $r) {
             if ($this->hasRole($r->name)) {
                 return true;
             }
         }

         return false;
    }

    public function getModules($roleid){
        // $hasrole= $this->hasRole($role);

        // if($hasrole){
            // $roleid= $this->getRoleId();
        $menu = [];

        $parent = RoleModule::query()->leftJoin('modules', function($join){
                    $join->on('modules.id','=','role_module.module_id');
                })
                ->select(
                    'role_module.role_id',
                    'role_module.module_id',
                    'modules.name',
                    'modules.id', 'modules.parent',
                    'modules.description_in',
                    'modules.description_en',
                    'modules.icon'
                )
                ->orderBy('modules.parent','ASC')
                ->orderBy('modules.id','ASC')
                ->orderBy('modules.z_order','ASC')
                ->where('modules.parent', 0)
                ->where('role_id', $roleid)->get();
//        dd($parent);
        $child = RoleModule::query()->leftJoin('modules', function($join){
                    $join->on('modules.id','=','role_module.module_id');
                })
                ->select(
                    'role_module.role_id',
                    'role_module.module_id',
                    'modules.name',
                    'modules.id', 'modules.parent',
                    'modules.description_in',
                    'modules.description_en',
                    'modules.icon'
                )
                ->orderBy('modules.parent','ASC')
                ->orderBy('modules.id','ASC')
                ->orderBy('modules.z_order','ASC')
                ->where('modules.parent','<>', 0)
                ->where('role_id', $roleid)->get();
//        dd($child);
        for ($i=0;$i<count($parent);$i++){
            array_push($menu,$parent[$i]);
            $parent_id = $parent[$i]->id;
            foreach ($child as $key){
                if($key->parent==$parent_id){
                    array_push($menu,$key);
                }
            }
//            $parent = Module::query()->select('id')->get();
        }
//        $data = array_push($menu,$parent);
//        for ($i=0;$i<count($result);$i++){
//            $child = Module::query()->select('name','parent',
//                    'icon','description_in','description_en')->get();
//        }
//        $data = array_push($menu,$child);
//        dd($menu);
        // }
//        print_r($menu);

//        return $result;
        return $menu;
    }
     /**
     * Relation table with roles.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role')->withTimestamps();

    }

     /**
     * Relation table with roles.
     */
    public function rolesUser()
    {
        return $this->hasOne('App\Models\RoleUser');
    }

    /**
     * Get Organization ID of User
     *
     * @return string
     */
    public function getOrganizationUnitId()
    {
        return $this->attributes['ou_fk'];
    }

    /**
     * Get Role ID User
     *
     * @return int
     */
    public function getRoleId()
    {
        return $this->rolesUser->role_id;
    }

    public function getRoleName()
    {
        return $this->rolesUser->name;
    }

    public function verifyUser()
    {
        return $this->hasOne('App\models\VerifyUser');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
