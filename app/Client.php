<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = "clients";
    protected $primaryKey = "id";
    protected $connection = 'mysql';

    public function numerations()
    {
        return $this->belongsToMany(Numeration::class);
    }
}
