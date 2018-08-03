<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    /**
     * The roles that belong to the user.
     */
    public function keywords()
    {
        return $this->belongsToMany('App\Keywords', 'bookmarks_to_keywords', 'bookmark_id', 'keyword_id');
    }
}
