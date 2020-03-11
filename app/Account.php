<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = "accounts";
    protected $primaryKey = "id";
    protected $connection = 'mysql';

    public $timestamps = false;

    protected $fillable = [
        'id_user', 'title', 'username', 'password', 'description'
    ];
}
