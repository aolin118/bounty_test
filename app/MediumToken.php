<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediumToken extends Model
{
    //
    public function user()
    {
        return $this->belongsTo('App\BountyUser');
    }
}
