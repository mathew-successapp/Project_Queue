<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = ['id','user_id','project_title','due_date','status','created_at','updated_at'];

    public static function boot()
	{
	    parent::boot();
	    self::creating(function ($model) {
	        $model->id = Uuid::generate(1)->time;
	    });
	}

	public function getRouteKeyName()
	{
	    return 'uuid';
	}

}
