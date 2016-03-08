<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //Fillable Data
    protected $fillable = ['name'];

    // Relation of Project with Tasks
    public function tasks()
    {
		return $this->hasMany('App\Task');
    }

}
