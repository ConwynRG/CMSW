<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=['post_id','user_id','comment_text'];
    
    public function user() { 
        return $this->belongsTo('App\User');
    }
    
    public function post() { 
        return $this->belongsTo('App\Post');
    }
}
