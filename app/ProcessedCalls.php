<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessedCalls extends Model
{
    protected $table = "processed_calls";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
    public $timestamps = false;
}
