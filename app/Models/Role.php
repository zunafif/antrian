<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoleUser;

class Role extends Model
{
    protected $table = 'roles';

    public function users()
    {
        return $this->belongsToMany('App\Models\RoleUser');
    }
}