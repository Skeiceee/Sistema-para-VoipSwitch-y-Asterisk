<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    protected $table = "hosts";
    protected $primaryKey = "id";
    public $timestamps = false;
}