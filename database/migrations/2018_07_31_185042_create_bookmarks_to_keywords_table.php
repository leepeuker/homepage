<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarksToKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks_to_keywords', function (Blueprint $table) {
            $table->unsignedInteger('bookmark_id');
            $table->unsignedInteger('keyword_id');
            $table->foreign('bookmark_id')->references('id')->on('bookmarks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('keyword_id')->references('id')->on('keywords')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmarks_to_keywords');
    }
}
