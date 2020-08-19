<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SessionsMovistar extends Model
{
    protected $table = "sessions_movistar";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
    public $timestamps = false;

    protected $fillable = [
        'date', 'description', 'file_name'
    ];
}
