<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Bookmark extends Model
{
    /**
     * The roles that belong to the user.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    /**
     * Set favicon from url
     * 
     * @param  string  $url
     * @return void
     */
    public function setFavicon($url)
    {
        $favicon = new \Favicon\Favicon();
        $favicon_file_name = null;
        
        if ($favicon_url = $favicon->get($url)) {

            $favicon_image = file_get_contents($favicon_url);

            $regex_matches = [];
            preg_match("/\.(\w+)(\?|$)/", $favicon_url, $regex_matches);

            do {

                $favicon_file_name = str_random(10) .'.'. $regex_matches[1];

            } while (Storage::exists('public/favicons/'. $favicon_file_name, $favicon_image));

            Storage::put('public/favicons/'. $favicon_file_name, $favicon_image);
        }

        $this->favicon = $favicon_file_name;
    }
}
