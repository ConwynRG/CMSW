<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::create(array('name' => 'Conwyn', 'email' => 'conwyn@test.com', 'password' => bcrypt('123'), 'avatar_filename' => 'defaultAvatar.png'));
        User::create(array('name' => 'Brayan', 'email' => 'brayan@test.com', 'password' => bcrypt('123'), 'avatar_filename' => 'defaultAvatar.png'));
        User::create(array('name' => 'Steven', 'email' => 'steven@test.com', 'password' => bcrypt('123'), 'avatar_filename' => 'defaultAvatar.png'));
        User::create(array('name' => 'Silvia', 'email' => 'silvia@test.com', 'password' => bcrypt('123'), 'avatar_filename' => 'defaultAvatar.png'));
        User::create(array('name' => 'Dwayne', 'email' => 'dwaynen@test.com', 'password' => bcrypt('123'), 'avatar_filename' => 'defaultAvatar.png'));
    }
}
