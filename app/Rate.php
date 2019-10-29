<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = "rates";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
    public $timestamps = false;
}
