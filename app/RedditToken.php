<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RedditToken extends Model
{
    //
    public function user()
    {
        return $this->belongsTo('App\BountyUser');
    }
}
