<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = "documents";
    protected $primaryKey = "id";
    protected $connection = 'mysql';
}
