<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    protected $table = "revenues";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
    public $timestamps = false;

    protected $fillable = [
        'date', 'description', 'file_name'
    ];
}
