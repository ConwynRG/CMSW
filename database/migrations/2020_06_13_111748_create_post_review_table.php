<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_reviews', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->foreignId('user_id')->constrained();
            $table->foreignId('post_id')->constrained();
            $table->integer('review');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_review');
    }
}
