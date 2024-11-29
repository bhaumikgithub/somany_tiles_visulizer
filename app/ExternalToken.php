<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalToken extends Model
{

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
