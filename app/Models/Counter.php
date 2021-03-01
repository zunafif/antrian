<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counter extends Model
{
  use SoftDeletes;

	protected $table = "mst_counter";
	protected $fillable = [
		"code_alpha",
		"name",
		"ou_fk",
		"status",
		"counter_type"
	];

	protected $hidden = [
		"created_at",
		"update_at",
		"deleted_at"
	];
}
