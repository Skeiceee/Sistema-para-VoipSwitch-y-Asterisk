<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public function numerations()
    {
        return $this->belongsTo(Numeration::class);
    }
}