<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable=['user_id'];
    
    public function user() { 
        return $this->belongsTo('App\User');
    }
    
    public function posts() { 
        return $this->hasMany('App\Post');
    }

}
