<?php

use App\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::truncate();
        Comment::create(array('post_id'=>1, 'user_id'=>2, 'comment_text'=>'Impresive way of cooking!!'));
        Comment::create(array('post_id'=>1, 'user_id'=>3, 'comment_text'=>'Did not manage to complete :('));
        Comment::create(array('post_id'=>2, 'user_id'=>1, 'comment_text'=>'Will try next time!'));
        Comment::create(array('post_id'=>3, 'user_id'=>4, 'comment_text'=>'Maybe with different sauce it will be better'));
        Comment::create(array('post_id'=>3, 'user_id'=>2, 'comment_text'=>'Any suggestions?'));
        Comment::create(array('post_id'=>4, 'user_id'=>3, 'comment_text'=>'Difficul to accomplish actualy'));
           
    }
}
