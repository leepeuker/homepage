<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The tag that belong to the bookmarks.
     */
    public function bookmarks()
    {
        return $this->belongsToMany('App\Bookmark');
    }
}
