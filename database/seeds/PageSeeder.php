<?php

use App\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::truncate();
        Page::create(array('user_id' => 1));
        Page::create(array('user_id' => 2));
        Page::create(array('user_id' => 3));
        Page::create(array('user_id' => 4));
        Page::create(array('user_id' => 5));
        Page::create(array('user_id' => 6));
    }
}
