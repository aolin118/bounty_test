<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    //
    public function user()
    {
        return $this->belongsTo('App\BountyUser');
    }
}
