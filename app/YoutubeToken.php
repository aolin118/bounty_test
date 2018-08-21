<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YoutubeToken extends Model
{
    //
    public function user()
    {
        return $this->belongsTo('App\BountyUser');
    }
}
