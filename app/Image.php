<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable=['post_id','title','description','filename','mime','original_fielname'];
    
    public function post() { 
        return $this->belongsTo('App\Post');
    }
}
