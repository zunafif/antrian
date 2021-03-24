<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sound extends Model
{
    protected $table = 'mst_sound';

    protected $fillable = [
        "id",
        "name",
        "path"        
    ];

    protected $hidden = [
		"created_at",
		"update_at",
		"deleted_at"
	];
}
