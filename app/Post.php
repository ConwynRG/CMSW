<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable=['user_id','page_id','title','short_description','description','rating','isRecipe','isPublic'];
    
    public function page() { 
        return $this->belongsTo('App\Page');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function images() {
        return $this->hasMany('App\Image');
    }
    
    public function postReviews(){
        return $this->hasMany('App\PostReview');
    }
    
}
