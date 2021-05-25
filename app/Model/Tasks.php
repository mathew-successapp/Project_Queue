<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $fillable = ['title','description','due_date','assigned_to','project_id','created_at','updated_at'];
    
    public function project(){
      return $this->belongsTo('App\Model\Project');
    }
}
