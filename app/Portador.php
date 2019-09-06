<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portador extends Model
{
    protected $table = "portadores";
    protected $primaryKey = "id";
    protected $connection = 'asterisk.portables';
    public $timestamps = false;
}
