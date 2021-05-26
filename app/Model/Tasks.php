<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tasks extends Model
{
    protected $fillable = ['title','description','due_date','assignee_id','project_id','created_at','updated_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
    
    public function project(){
      return $this->belongsTo('App\Model\Project');
    }
}
