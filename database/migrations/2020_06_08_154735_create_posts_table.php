<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->foreignId('user_id')->constrained();
            $table->foreignId('page_id')->constrained();
            $table->integer('mainImage_id');
            $table->string('title', 100);
            $table->text('short_description')->nullable();
            $table->mediumText('description')->nullable();
            $table->integer('rating')->default(0);
            $table->boolean('isRecipe')->default(false);
            $table->boolean('isPublic')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
