<?php

use App\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Image::truncate();
        //$defaultImage = Storage::disk('public')->get('defaultPostImg.png');
        
       
        Image::create(array('post_id'=>1,'title'=>'default image', 'description'=>null,
            'filename'=>'defaultPostImg.png',
            'mime'=>'IANA', 'original_filename' => 'defaultPostImg.png'));
        Image::create(array('post_id'=>1,'title'=>'default image', 'description'=>null,
            'filename'=>'defaultPostImg.png',
            'mime'=>'IANA', 'original_filename' => 'defaultPostImg.png'));
        Image::create(array('post_id'=>1,'title'=>'default image', 'description'=>null,
            'filename'=>'defaultPostImg.png',
            'mime'=>'IANA', 'original_filename' => 'defaultPostImg.png'));
        Image::create(array('post_id'=>1,'title'=>'default image', 'description'=>null,
            'filename'=>'defaultPostImg.png',
            'mime'=>'IANA', 'original_filename' => 'defaultPostImg.png'));
    }
}
