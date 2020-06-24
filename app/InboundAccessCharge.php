<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InboundAccessCharge extends Model
{
    protected $table = "inboundaccesscharges";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
    public $timestamps = false;

    protected $fillable = [
        'date', 'description', 'file_name'
    ];
}
