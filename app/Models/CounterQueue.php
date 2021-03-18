<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CounterQueue extends Model
{
    use SoftDeletes;

	protected $table = "counter_registration_queue";
	// protected $fillable = [
	// 	"schedule_poly_id",
	// 	"date_visit",
	// 	"current_registration_number",
	// 	"current_queue"
	// ];

	protected $hidden = [
		"created_at",
		"update_at",
		"deleted_at"
	];
}
