<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleModule extends Model
{
    protected $table = "role_module";

    protected $fillable = [
        "role_id",
        "role_modul",
    ];

    public function hasModule(){
        return $this->hasMany('App\Models\Module');
    }
    public function role(){
        return $this->belongsTo('App\Models\Role');
    }
}
