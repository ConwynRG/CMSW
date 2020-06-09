<?php

use App\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::truncate();
        Post::create(array('user_id'=>1, 'page_id'=>1,'mainImage_id'=>1,'title'=>'Test title one',
            'short_description'=>'Short description just to adjust sizes and other stuff', 'description'=>null,'rating'=>0,
            'isRecipe'=>false,'isPublic'=>true));
        Post::create(array('user_id'=>1, 'page_id'=>1,'mainImage_id'=>2,'title'=>'Test titke for recipe',
            'short_description'=>'Just for the esthetic will change this description','description'=>null, 'rating'=>0,
            'isRecipe'=>true,'isPublic'=>false));
        Post::create(array('user_id'=>2, 'page_id'=>2,'mainImage_id'=>3,'title'=>'Recipe title',
            'short_description'=>'Very short', 'description'=>null, 'rating'=>0,
            'isRecipe'=>true,'isPublic'=>true));
        Post::create(array('user_id'=>3, 'page_id'=>3,'mainImage_id'=>4,'title'=>'Long title in case difficult dish or post',
            'short_description'=>'Short description just to adjust sizes and other stuff','description'=>null,'rating'=>0,
            'isRecipe'=>false,'isPublic'=>true));
    }
}
