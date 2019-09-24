<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AverageCalls extends Model
{
    protected $table = "average_calls";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
    public $timestamps = false;
}
