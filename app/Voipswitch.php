<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voipswitch extends Model
{
    protected $table = "voipswitchs";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
}
