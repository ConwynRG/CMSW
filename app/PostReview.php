<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostReview extends Model
{
    protected $fillable=['user_id','post_id','review'];
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function post(){
        return $this->belongsTo('App\Post');
    }
}
