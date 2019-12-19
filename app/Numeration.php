<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Numeration extends Model
{
    protected $table = "numerations";
    protected $primaryKey = "id";
    protected $connection = 'mysql';

    public function client()
    {
        return $this->belongsToMany(Client::class);
    }

    public function type()
    {
        return $this->hasMany(Type::class);
    }
}