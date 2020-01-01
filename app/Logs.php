<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $fillable = [
        'client', 'timeStamp', 'url', 'statusCode','resTime'
    ];
}
