<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarkKeywordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmark_keyword', function (Blueprint $table) {
            $table->unsignedInteger('bookmark_id');
            $table->unsignedInteger('keyword_id');
            $table->foreign('bookmark_id')->references('id')->on('bookmarks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('keyword_id')->references('id')->on('keywords')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmark_keyword');
    }
}
