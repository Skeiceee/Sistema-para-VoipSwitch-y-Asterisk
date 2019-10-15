<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = "types";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
    public $timestamps = false;

    public function numerations()
    {
        return $this->belongsTo(Numeration::class);
    }
}