<?php

namespace App\Models;

use App\Traits\Uuid;
//use Illuminate\Database\Eloquent\Model;
use App\Models\ModelBase;
use Illuminate\Database\Eloquent\SoftDeletes;
use Config;

class OrganizationalUnit extends ModelBase
{
	use SoftDeletes;
	use Uuid;

	/**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = "organizational_unit";

	/**
	* The primary key associated with the table.
	*
	* @var string
	*/
	protected $primaryKey = 'uuid'; 

	/**
	* Indicates if the IDs are auto-incrementing.
	*
	* @var bool
	*/
    public $incrementing = false;

    protected $fillable = [
		"uuid" ,
		"ou_code" ,
		"ou_name" ,
		"ou_address" ,
		"ou_city" ,
		"ou_province" ,
		"ou_country" ,
		"ou_postal_code" ,
		"ou_phone" ,
		"ou_phone_alt" ,
		"ou_fax" ,
		"ou_email" ,
		"ou_website" ,
		"ou_ownership" ,
		"ou_ceo" ,
		"ou_rating" ,
		"ou_sk" ,
		"ou_avatar" ,
		"ou_registration_number" ,
		"ou_operational_permit_number" ,
		"ou_operational_permit_date" ,
		"ou_land_area" ,
		"ou_building_area" ,
    ];
    
    protected $appends = [
        'avatar_url',
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    public function getAvatarUrlAttribute()
    {
        if(isset($this->attributes['ou_avatar'])){
        	$config = "/storage/app/public" . Config::get('site.path.organization');
        	return url($config . $this->attributes['ou_avatar']);
        }
        return null;
    }
}