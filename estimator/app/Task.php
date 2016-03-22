<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //Fillable Data
    protected $fillable = ['name', 'task_id'];

    

    // Relationship with a Project
    public function project()
    {
    	return $this->belongsTo('App\Project');
    }

    public function subTasks()
    {
    	return $this->hasMany('App\Task');
    }

    public function parentTask()
    {
    	return $this->belongsTo('App\Task');
    }
}
