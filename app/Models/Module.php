<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = "modules";

    protected $fillable = [
        "name",
		"description_en",
		"description_id",
        "is_active",
    ];

    public function roleModule(){
        return $this->belongsTo('App\Models\RoleModule');
    }
}
