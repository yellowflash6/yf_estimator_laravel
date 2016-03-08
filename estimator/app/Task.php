<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //Fillable Data
    protected $fillable = ['name'];

    // Relationship with a Project
    public function project()
    {
    	return $this->belongsTo('App\Project');
    }
}
