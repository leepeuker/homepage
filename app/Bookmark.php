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
        return $this->belongsToMany('App\Keyword');
    }
}
