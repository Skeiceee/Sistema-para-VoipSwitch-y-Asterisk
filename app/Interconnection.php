<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interconnection extends Model
{
    protected $table = "interconnections";
    protected $primaryKey = "id";
    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'connection_name',
        'connection_no_strict_name'
    ];
}
