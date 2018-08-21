<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BountyUser extends Model
{
    //
    public function telegram()
    {
        return $this->hasOne('App\TelegramUser');
    }

    public function twitter()
    {
        return $this->hasOne('App\TwitterToken');
    }

    public function youtube()
    {
        return $this->hasOne('App\YoutubeToken');
    }

    public function reddit()
    {
        return $this->hasOne('App\RedditToken');
    }

    public function medium()
    {
        return $this->hasOne('App\MediumToken');
    }
}
